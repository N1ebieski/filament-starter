<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Commands;

use App\Filament\Resources\GlobalSearchInterface;
use App\Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightSearchResult;
use Override;
use pxlrbt\FilamentSpotlight\Commands\ResourceCommand as BaseResourceCommand;

/**
 * @property-read FilamentResource&GlobalSearchInterface $resource
 */
final class ResourceCommand extends BaseResourceCommand
{
    #[Override]
    protected function hasDependencies(): bool
    {
        $hasDependencies = parent::hasDependencies();

        if ($hasDependencies && ! ($this->resource instanceof GlobalSearchInterface)) {
            throw new \Exception('The resource with dependencies must implement GlobalSearchInterface.');
        }

        return $hasDependencies;
    }

    /**
     * @param  string  $query
     */
    #[Override]
    public function searchRecord($query): Collection
    {
        $globalSearchQuery = $this->resource::getGlobalSearchEloquentQuery();

        $this->resource::applyGlobalSearchAttributeConstraints($globalSearchQuery, $query);

        $this->resource::modifyGlobalSearchQuery($globalSearchQuery, $query);

        return $globalSearchQuery
            ->limit(50)
            ->get()
            ->map(fn (Model $record) => new SpotlightSearchResult(
                $record->getKey(),
                $this->resource::getGlobalSearchResultTitle($record), // @phpstan-ignore-line
                collect($this->resource::getGlobalSearchResultDetails($record))
                    ->map(fn ($value, $key) => $key.': '.$value)
                    ->join(' – ')
            ));
    }

    /**
     * @param  null|int|string  $record
     */
    #[Override]
    public function execute(Spotlight $spotlight, $record = null): void
    {
        $spotlight->redirect($this->getUrl($record), navigate: true);
    }
}
