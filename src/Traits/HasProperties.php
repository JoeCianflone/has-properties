<?php declare(strict_types=1);

namespace Inizio\HasProperties\Traits;

use Illuminate\Support\Collection;

trait HasProperties
{
    protected bool $unsetProps = true;

    protected function initializeHasProperties(): void
    {
        $normalized = $this->generateProperties($this->props);

        $this->attributes = $normalized['attributes'];
        $this->setHidden($normalized['hidden']);
        $this->mergeCasts($normalized['casts']);
        $this->fillable($normalized['fillable']);
        $this->guard($normalized['guarded']);

        if ($this->unsetProps) {
            unset($this->props);
        }
    }

    private function generateProperties(array $props): array
    {
        return collect($props)->flatMap(function ($item, $type) {
            return (new Collection($item))->reduce(function ($carry, $i, $k) use ($type) {
                return match ( ! is_array($i)) {
                    true => $this->pushToAcc($carry, $type, $i),
                    false => $this->reduceArray($i, $k, $carry),
                };
            }, [
                'fillable' => [],
                'guarded' => ['*'],
                'hidden' => [],
                'casts' => [],
                'attributes' => [],
            ]);
        })->toArray();
    }

    private function reduceArray(array $value, int|string $key, array $acc = []): array
    {
        return collect($value)->reduce(function ($c, $i, $k) use ($key) {
            return match (is_int($k)) {
                true => $this->pushToAcc($c, $i, $key),
                false =>  $this->pushToAcc($c, $k, $i, $key),
            };
        }, $acc)->toArray();
    }

    private function pushToAcc(array $acc, string|int $key, mixed $value, string|int|null $secondary = null): array
    {
        if (is_null($secondary)) {
            $acc[$key][] = $value;
        } else {
            $acc[$key][$secondary] = $value;
        }

        return $acc;
    }
}
