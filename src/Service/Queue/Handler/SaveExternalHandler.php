<?php

/**
 * Handler for External Save
 * Created by Orami Development Team <development@bilna.com>
 * Date: 21/12/16
 * Time: 18:40
 */

namespace App\Service\Queue\Handler;

use Cake\Http\Client;
use Cake\Log\Log;
use Psr\Log\LogLevel;
use App\Service\Queue\HandlerInterface;

class SaveExternalHandler implements HandlerInterface
{
    public function handle(\App\Model\Entity\QueueJob $queueJob, \App\Model\Table\QueueJobsTable $queueJobTable)
    {   
        try {
            $success = false;
            $message = null;
            $responseData = null;

            $queueBody = json_decode($queueJob->body, true); // decode as array
            
            $http = new Client();
            $response = $http->post(
                $queueBody['target_url'], 
                $queueBody['request_body'], 
                $queueBody['options']
            );

            // If Response is not OK or Accepted
            if (!$response->isOk()) {
                throw new \Exception($response->getStatusCode() . ' - ' . $response->getReasonPhrase());
            }

            // Read the response
            $responseJson = $response->json;
            Log::write(LogLevel::INFO, "SaveExternalHandler - " . json_encode($queueBody) . ' - ' .json_encode($responseJson));

            if (isset($responseJson['data'])) {
                $responseData = $responseJson['data'];
            }
            if (isset($responseJson['success'])) {
                $success = $responseJson['success'];
            }
            if (isset($responseJson['message'])) {
                $message = $responseJson['message'];
            }

            if (!$success) {
                throw new \Exception($message);
            }

            // Delete the job from table
            $queueJobTable->delete($queueJob);
            
            // Update status to finished
            $queueJob->status = $queueJobTable::STATUS_FINISHED;
            $queueJobTable->save($queueJob);

        } catch (\Exception $ex) {
            // Update the job status to FAILED
            $queueJob->status = $queueJobTable::STATUS_FAILED;
            $queueJobTable->save($queueJob);
                
            // Log the error
            Log::write(LogLevel::ERROR, "SaveExternalHandler - {$ex->getMessage()}");
        }
    }
}
