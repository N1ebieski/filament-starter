<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Auth\Access;

use Illuminate\Contracts\Auth\Access\Gate as BaseGate;

interface Gate
{
    /**
     * Perform an on-demand authorization check. Throw an authorization exception if the condition or callback is false.
     *
     * @param  \Illuminate\Auth\Access\Response|\Closure|bool  $condition
     * @param  string|null  $message
     * @param  string|null  $code
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allowIf($condition, $message = null, $code = null);

    /**
     * Perform an on-demand authorization check. Throw an authorization exception if the condition or callback is true.
     *
     * @param  \Illuminate\Auth\Access\Response|\Closure|bool  $condition
     * @param  string|null  $message
     * @param  string|null  $code
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function denyIf($condition, $message = null, $code = null);
}
