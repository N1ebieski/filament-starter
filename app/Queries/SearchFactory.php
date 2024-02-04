<?php

declare(strict_types=1);

namespace App\Queries;

use App\Queries\Search;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Builder as Schema;

final class SearchFactory
{
    private string $search;

    public function __construct(
        private readonly Schema $schema,
        private readonly Str $str
    ) {
    }

    private function splitAttributes(Model $model): ?array
    {
        $columns = implode('|', $this->schema->getColumnListing($model->getTable()));

        preg_match_all('/attr:(' . $columns . '):\"(.*?)\"/', $this->search, $matches);

        $attributes = [];

        foreach ($matches[0] as $key => $value) {
            $attributes[trim($matches[1][$key])] = trim(str_replace('"', '', $matches[2][$key]));

            $this->search = trim(str_replace($value, '', $this->search));
        }

        return !empty($attributes) ? $attributes : null;
    }

    private function splitRelations(): ?array
    {
        preg_match_all('/rel:([a-z]+):\"(.*?)\"/', $this->search, $matches);

        foreach ($matches[0] as $key => $value) {
            $relations[trim($matches[1][$key])] = '+"' . trim($matches[2][$key]) . '"';

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

    protected function isContainsSymbol(string $match): bool
    {
        return $this->str->contains($match, ['.', '-', '+', '<', '>', '@', '*', '(', ')', '~']);
    }

    public function make(string $search, ?Model $model = null): Search
    {
        $this->search = $search;

        $attributes = !is_null($model) ? $this->splitAttributes($model) : null;

        $relations = $this->splitRelations();

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
