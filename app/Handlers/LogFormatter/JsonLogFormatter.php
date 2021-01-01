<?php


namespace App\Handlers\LogFormatter;


use Monolog\Formatter\JsonFormatter;
use Monolog\Processor\UidProcessor;

class JsonLogFormatter
{
    /**
     * @param $logger
     */
    public function __invoke($logger)
    {
        $jsonLogFormatter = new class() extends JsonFormatter {
            /**
             * @param array $record
             * @return string
             */
            public function format(array $record):string
            {
                $record['request_id'] = ($_SERVER['request_id'] ?? REQUEST_ID) . '_' . (new UidProcessor(16))->getUid();
                return $this->toJson($this->normalize($record), true) . ($this->appendNewline ? "\n" : '');
            }
        };

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($jsonLogFormatter);
        }
    }
}
