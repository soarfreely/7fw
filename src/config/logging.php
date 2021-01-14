<?php

use App\Handlers\LogFormatter\JsonLogFormatter;
use App\Handlers\LogFormatter\SqlLogFormatter;
use App\Handlers\LogProcessor\ElasticsearchProcessor;
use Elasticsearch\ClientBuilder;
use Monolog\Formatter\ElasticsearchFormatter;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
            'tap' => [JsonLogFormatter::class], // 挂载日志格式接口
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Lumen Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'sqllog' => [
            'driver' => 'single',
            'path' => storage_path('logs/' . date('Y/m/d/\h-H', time()).'sql.txt'),
            'tap' => [SqlLogFormatter::class], // 挂载日志格式接口
            'level' => 'debug',
        ],

        // https://learnku.com/articles/3567/monolog-optimization-and-elk-friendly-log-format
//        'logstash' => [
//            'driver' => 'monolog',
//            'handler' => AggregateLogHandler::class,
//            'formatter' => LogstashFormatter::class,
//            'path' => storage_path('logs') . '/logstash-gavin.log',
//            'level' => 'debug',
//        ],

        'elasticsearch' => [
            'driver' => 'monolog',
            'handler' => ElasticsearchHandler::class,
            'formatter' => ElasticsearchFormatter::class,
            'tap' => [ElasticsearchProcessor::class],
            'formatter_with' => [
                'index' => sprintf('lumen7-log-%s', date('Y-m-d')),
                'type' => '_doc',
            ],
            'with' => [
                'client' => ClientBuilder::create()
                    ->setHosts([
                        [
                            'host'   => env('ELASTICSEARCH_HOST', '127.0.0.1'),
                            'port'   => env('ELASTICSEARCH_PORT', '9200'),
                            'scheme' => env('ELASTICSEARCH_SCHEME', 'http'),
                            'user'   => env('ELASTICSEARCH_USERNAME', null),
                            'pass'   => env('ELASTICSEARCH_PASSWORD', null),
                        ]
                    ])
                    ->build(),
//                'options' => [
//                    'index' => 'storage-log',
//                    'type' => '_doc',
//                    'ignore_error' => false,
//                ],
            ],
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],

];
