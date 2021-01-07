<?php


namespace App\Handlers\LogProcessor;



use Illuminate\Log\Logger;
use Monolog\Processor\UidProcessor;

class ElasticsearchProcessor
{
    /**
     * @param Logger $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(function ($record) {
                $record['request_id_1'] = ($_SERVER['request_id'] ?? REQUEST_ID) . '-' . (new UidProcessor())->getUid();
                return $record;
            });
        }
    }
}
