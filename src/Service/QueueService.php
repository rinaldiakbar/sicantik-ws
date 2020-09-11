<?php
/**
 * Upload Service
 * Created by Indra.
 * Date: 3/22/17
 * Time: 8:39 PM
 */

namespace App\Service;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Inflector;
use Cake\Log\Log;
use Psr\Log\LogLevel;
use App\Model\Entity\QueueJob;
use Pheanstalk\Exception;
use Pheanstalk\Pheanstalk;

class QueueService extends AuthService
{
    /** Beanstalk host */
    private static $host;

    /** Beanstalk tube */
    private static $tube;

    /** Instance of \App\Model\Table\QueueJobTable */
    private static $jobTableInstance;

    /** Delay time in seconds */
    private static $delayTime = 0;

    /** Priority in integer. The smaller the number, the higher priority */
    private static $priority = 10;

    private static function prepare()
    {
        Configure::load('beanstalkd', 'default', false);
        self::$host = Configure::read('Beanstalkd.host');
        self::$tube = Configure::read('Beanstalkd.tube');
        self::$delayTime = Configure::read('Beanstalkd.delay_time');
        self::$jobTableInstance = TableRegistry::get('QueueJobs');
    }

    /**
     * Send Queue Job to Beanstalkd Service
     * 
     * @param QueueJob $job
     * 
     * @return boolean
     */
    public static function send(QueueJob $queueJob)
    {
        try {
            self::prepare();
            $queueJobTable = self::$jobTableInstance;

            $pheanstalk = new Pheanstalk(self::$host);

            if (!$pheanstalk->getConnection()->isServiceListening()) {
                throw new \Exception('Cannot connect to Beanstalkd Service');
            }

            $data = [$queueJob->type, $queueJob->id];
            $dataString = implode('|', $data);

            $pheanstalk
                ->useTube(self::$tube)
                ->put($dataString, null, $queueJob->delay_time);
            
            Log::write(LogLevel::INFO, 'Send Job To Queue: ' . $dataString);

            // Update status to IN QUEUE
            $queueJob->status = $queueJobTable::STATUS_IN_QUEUE;
            $queueJobTable->save($queueJob);

            return true;

        } catch (\Exception $ex) {
            Log::write(LogLevel::ERROR, $ex->getMessage());
        }
    }

    /**
     * Watch and Perform Job
     * 
     * @return boolean
     */
    public static function watch()
    {
        try {
            self::prepare();
            $queueJobTable = self::$jobTableInstance;

            $pheanstalk = new Pheanstalk(self::$host);
            if (!$pheanstalk->getConnection()->isServiceListening()) {
                throw new \Exception('Cannot connect to Beanstalkd Service');
            }

            // Watch Beanstalk Job
            $beanstalkdJob = $pheanstalk
                ->watch(self::$tube)
                ->ignore('default');

            while (($job = $beanstalkdJob->reserve())) {
                $jobDataString = $job->getData();
                $jobData = explode('|', $jobDataString);

                // Get The Job Type and Job Body
                $jobType = $jobData[0];
                $jobId = $jobData[1];

                // Load the Handler
                $handlerClassName = "App\\Service\\Queue\\Handler\\" . Inflector::camelize($jobType) . 'Handler';
                if (!class_exists($handlerClassName)) {
                    echo "Handler does not exists:  . $handlerClassName\n";
                }

                // Immediately delete the Job to prevent double Queue
                $pheanstalk->delete($job);

                // Get the QueueJob Instance
                $queueJob = $queueJobTable->find('all', [
                    'conditions' => ['id' => $jobId]
                ])->first();

                if ($queueJob) {
                    // Update status to PROCESSING
                    $queueJob->status = $queueJobTable::STATUS_PROCESSING;
                    $queueJobTable->save($queueJob);

                    // Initialize the Handler
                    $handler = new $handlerClassName();
                    $handler->handle($queueJob, $queueJobTable);
                }     
            }

        } catch (\Exception $ex) {
            Log::write(LogLevel::ERROR, $ex->getMessage());
        }
    }

    /**
     * Create New Job
     *
     * @param string $jobType job type
     * @param array $jobData array of data
     * 
     * @return boolean true if job created successfully
     */
    public static function createJob($jobType, $jobData) {
        try {
            $queueJobTable = TableRegistry::get('QueueJobs');
            $queueJob = $queueJobTable->newEntity();
            $instansiId = (self::$instansi && self::$instansi->id) ? self::$instansi->id : null;
            
            $now = Time::now(); 
            $executionTime = $now->modify('+' . self::$delayTime . ' seconds');
            $data = [
                'type'           => $jobType,
                'body'           => json_encode($jobData),
                'status'         => $queueJobTable::STATUS_WAITING,
                'delay_time'     => self::$delayTime,
                'priority'       => self::$priority,
                'execution_time' => $executionTime,
                'instansi_id'    => $instansiId
            ];

            $queueJob = $queueJobTable->patchEntity($queueJob, $data);
            
            // Save Job to table
            if ($queueJobTable->save($queueJob)) {

                // Send to queue
                if (self::send($queueJob)) {
                    return true;
                }

            } else {
                throw new \Exception($queueJob->errors());
            }

        } catch (\Exception $ex) {
            Log::write(LogLevel::ERROR, $ex->getMessage());
            return false;
        }
    }
}