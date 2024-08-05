<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Queue;

use Illuminate\Queue\SerializesModels as BaseSerializesModels;

/**
 * Laravel serialize method doesn't include exception for "empty models" without Id.
 * These models shouldn't restore from the database, only make a new class.
 */
trait SerializesModels
{
    use BaseSerializesModels {
        BaseSerializesModels::restoreModel as baseRestoreModel;
    }

    /**
     * Restore the model from the model identifier instance.
     *
     * @param  \Illuminate\Contracts\Database\ModelIdentifier  $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restoreModel($value)
    {
        if (is_null($value->id)) {
            /** @var \Illuminate\Database\Eloquent\Model */
            return new ($value->class)();
        }

        return $this->baseRestoreModel($value);
    }
}
