<?php


namespace App\Handlers\LogFormatter;


use App\Handlers\LogHandler\AggregateLogHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BufferHandler;
use Monolog\Logger;

class LogstashFormatter
{
    public function __invoke()
    {
        $handler = new AggregateLogHandler(storage_path('logs') . '/logstash-gavin.log');

        $formatter = "[%datetime%]%level_name% %message% %context% %extra%\n";
        $handler->setFormatter(new LineFormatter($formatter, 'i:s', true, true));

        (new Logger('logstash'))->pushHandler(new BufferHandler($handler));
    }
}
