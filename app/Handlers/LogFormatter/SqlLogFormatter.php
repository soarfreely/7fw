<?php

namespace App\Handlers\LogFormatter;


use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

class SqlLogFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $sqlJsonFormatter = new class () extends JsonFormatter {
            /**
             * @param array $record
             * @return string
             */
            public function format(array $record):string
            {
                $message = explode('---', $record['message']);
                $newRecord = [
                    'request_id' => ($_SERVER['x_request_id'] ?? REQUEST_ID) . '_' . (new UidProcessor(16))->getUid(),
                    'time'       => ($message[1] ?? 0) .' ms',
                    'sql'        => $message[0],
                    'context'    => $record['context'],
                ];

                return $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');
            }
        };

        foreach ($logger->getHandlers() as $handler) {
            method_exists($handler, 'setFormatter') && $handler->setFormatter($sqlJsonFormatter);
        }
    }
}
