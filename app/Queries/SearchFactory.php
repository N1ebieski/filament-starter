<?php

declare(strict_types=1);

namespace App\Queries;

use App\Queries\Search;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

final class SearchFactory
{
    private string $search;

    private function splitAttributes(Model $model): ?array
    {
        $searchableColumns = implode('|', $model->searchableAttributes ?? []);

        preg_match_all('/attr:(' . $searchableColumns . '):\"(.*?)\"/', $this->search, $matches);

        $attributes = [];

        foreach ($matches[0] as $key => $value) {
            $attributes[trim($matches[1][$key])] = trim(str_replace('"', '', $matches[2][$key]));

            $this->search = trim(str_replace($value, '', $this->search));
        }

        return !empty($attributes) ? $attributes : null;
    }

    private function splitRelations(Model $model): ?array
    {
        $searchableRelations = implode('|', $model->searchableRelations ?? []);

        preg_match_all('/rel:(' . $searchableRelations . '):\"(.*?)\"/', $this->search, $matches);

        $relations = [];

        foreach ($matches[0] as $key => $value) {
            $looseMatches = explode(' ', trim($matches[2][$key]));

            $looses = [];

            foreach ($looseMatches as $match) {
                if (strlen($match) >= 3) {
                    $match = $this->isContainsSymbol($match) ?
                        '"' . str_replace('"', '', $match) . '"' : $match;

                    if ($match === end($looseMatches)) {
                        $match .= '*';
                    }

                    $looses[] = '+' . $match;
                }
            }

            $relations[trim($matches[1][$key])] = $looses;

            $this->search = trim(str_replace($value, '', $this->search));
        }

        return !empty($relations) ? $relations : null;
    }

    private function splitExacts(): ?array
    {
        preg_match_all('/"(.*?)"/', $this->search, $matches);

        foreach ($matches[0] as $match) {
            $exacts[] = '+' . $match;

            $this->search = trim(str_replace($match, '', $this->search));
        }

        return !empty($exacts) ? $exacts : null;
    }

    private function splitLosses(): ?array
    {
        $matches = explode(' ', $this->search);

        foreach ($matches as $match) {
            if (strlen($match) >= 3) {
                $match = $this->isContainsSymbol($match) ?
                    '"' . str_replace('"', '', $match) . '"' : $match;

                if ($match === end($matches)) {
                    $match .= '*';
                }

                $looses[] = '+' . $match;
            }
        }

        return !empty($looses) ? $looses : null;
    }

    private function isContainsSymbol(string $match): bool
    {
        return Str::contains($match, ['.', '-', '+', '<', '>', '@', '*', '(', ')', '~']);
    }

    public static function make(string $search, ?Model $model = null): Search
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getSearch($search, $model);
    }

    public function getSearch(string $search, ?Model $model = null): Search
    {
        $this->search = $search;

        $attributes = !is_null($model) ? $this->splitAttributes($model) : null;

        $relations = !is_null($model) ? $this->splitRelations($model) : null;

        $exacts = $this->splitExacts();

        $looses = $this->splitLosses();

        return new Search(
            attributes: $attributes,
            relations: $relations,
            exacts: $exacts,
            looses: $looses
        );
    }
}
