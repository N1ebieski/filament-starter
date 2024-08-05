<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Exception as BaseException;
use App\Overrides\Illuminate\Contracts\Logger\LoggerInterface;

class Exception extends BaseException
{
    /**
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        public readonly array $context = []
    ) {
        parent::__construct(
            !empty($this->message) && empty($message) ? $this->message : $message,
            !empty($this->code) && empty($code) ? $this->code : $code,
            $previous
        );
    }

    /**
     * Report the exception.
     *
     * @param LoggerInterface $logger
     * @return bool|void
     */
    public function report(LoggerInterface $logger)
    {
        return false;
    }

    /**
     *
     * @param Request $request
     * @return bool|void
     */
    public function render(Request $request)
    {
        return false;
    }
}
