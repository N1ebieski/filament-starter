<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\LaravelData\Normalizers\Normalized;

use App\Models\Shared\Attributes\AttributesInterface;
use Override;
use Spatie\LaravelData\Normalizers\Normalized\NormalizedModel as BaseNormalizedModel;
use Spatie\LaravelData\Normalizers\Normalized\UnknownProperty;
use Spatie\LaravelData\Support\DataProperty;

final class NormalizedModel extends BaseNormalizedModel
{
    #[Override]
    public function getProperty(string $name, DataProperty $dataProperty): mixed
    {
        if (! is_null($dataProperty->type->lazyType)) {
            return UnknownProperty::create();
        }

        if (
            $this->model instanceof AttributesInterface
            && $this->model->hasAttribute($name)
            && ! $this->model->isAttributeLoaded($name)
        ) {
            return UnknownProperty::create();
        }

        return parent::getProperty($name, $dataProperty);
    }
}
