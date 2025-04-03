<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Auth\Access;

use App\Overrides\Illuminate\Contracts\Auth\Access\Gate as ContractsGate;
use Illuminate\Auth\Access\Gate as BaseGate;

final readonly class Gate implements ContractsGate
{
    public function __construct(private BaseGate $gate) {}

    /**
     * Laravel doesn't define allowIf method in Contract.
     *
     * Perform an on-demand authorization check. Throw an authorization exception if the condition or callback is false.
     *
     * @param  \Illuminate\Auth\Access\Response|\Closure|bool  $condition
     * @param  string|null  $message
     * @param  string|null  $code
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allowIf($condition, $message = null, $code = null)
    {
        return $this->gate->allowIf($condition, $message, $code);
    }

    /**
     * Laravel doesn't define denyIf method in Contract.
     *
     * Perform an on-demand authorization check. Throw an authorization exception if the condition or callback is true.
     *
     * @param  \Illuminate\Auth\Access\Response|\Closure|bool  $condition
     * @param  string|null  $message
     * @param  string|null  $code
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function denyIf($condition, $message = null, $code = null)
    {
        return $this->gate->denyIf($condition, $message, $code);
    }
}
