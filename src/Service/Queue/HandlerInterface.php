<?php

/**
 * Interface for Queue Job Handler
 * @author Indra Halim
 */

namespace App\Service\Queue;

interface HandlerInterface
{
    /**
     * @param App\Model\Entity\QueueJob $queueJob
     * @return mixed
     */
    public function handle(\App\Model\Entity\QueueJob $queueJob, \App\Model\Table\QueueJobsTable $queueJobTable);
}