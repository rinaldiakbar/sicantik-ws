<?php

$config['Beanstalkd']['host'] = '127.0.0.1';
$config['Beanstalkd']['tube'] = 'codeless-development';
$config['Beanstalkd']['delay_time'] = 0; // Delay time for Queue in seconds
$config['Beanstalkd']['requeue_wait_time'] = 900; // Time to wait before a job can be requeue (seconds)
$config['Beanstalkd']['max_trial'] = 5; // Max number of requeue trial

return $config;