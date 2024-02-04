<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Exception as BaseException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Exception extends BaseException
{
    /**
     * Undocumented function
     *
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            !empty($this->message) && empty($message) ? $this->message : $message,
            !empty($this->code) && empty($code) ? $this->code : $code,
            $previous
        );
    }

    /**
     * Report the exception.
     *
     * @return bool|void
     */
    public function report()
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
        if (Config::get('app.debug') === true) {
            return false;
        }

        App::abort($this->getCode(), $this->getMessage());
    }
}
