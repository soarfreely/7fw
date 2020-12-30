<?php

namespace App\Libraries\LogFormatter;


use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

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
                // 这个就是最终要记录的数组，最后转成Json并记录进日志
                $messageArr = explode('---', $record['message']);
                $newRecord = [
                    'request_id' => $_SERVER['x_request_id'] ?? md5(uniqid() . time()),
                    'time'       => ($messageArr[1] ?? 0) .' ms',
                    'sql'        => $messageArr[0],
                    'context'    => $record['context'],
                ];

                return $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');
            }
        };

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($sqlJsonFormatter);
        }
    }
}
