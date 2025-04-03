<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Overrides\Illuminate\Contracts\Logger\LoggerInterface;
use Exception as BaseException;
use Illuminate\Http\Request;
use Throwable;

class Exception extends BaseException
{
    /**
     * @return void
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        public readonly array $context = []
    ) {
        parent::__construct(
            ! empty($this->message) && strlen($message) > 0 ? $this->message : $message,
            ! empty($this->code) && $code > 0 ? $this->code : $code,
            $previous
        );
    }

    /**
     * Report the exception.
     *
     * @return bool|void
     */
    public function report(LoggerInterface $logger)
    {
        return false;
    }

    /**
     * @return bool|void
     */
    public function render(Request $request)
    {
        return false;
    }
}
