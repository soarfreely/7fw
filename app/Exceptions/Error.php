<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class Error extends HttpException
{
    /**
     * 详细的debug信息
     */
    const DEBUG  = 100;

    /**
     * 关键事件
     */
    const INFO = 200;

    /**
     * 普通但是重要的事件
     */
    const NOTICE = 250;

    /**
     * 出现非错误的异常
     */
    const WARNING = 300;

    /**
     * 运行时错误，但是不需要立刻处理
     */
    const ERROR = 400;

    /**
     * 严重错误
     */
    const CRITICA = 500;

    /**
     * 系统不可用
     */
    const EMERGENCY = 600;

    /**
     * Error constructor.
     *
     * @param int $code
     * @param string $message
     * @param int $statusCode
     * @param Exception|null $previous
     * @param array $headers
     */
    public function __construct(int $code, string $message, int $statusCode = self::CRITICA, Exception $previous = null, array $headers = [])
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
