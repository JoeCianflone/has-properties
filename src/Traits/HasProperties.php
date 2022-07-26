<?php declare(strict_types=1);

namespace Inizio\HasProperties\Traits;

use Illuminate\Support\Collection;
use Inizio\HasProperties\Exceptions\PropArrayMissingException;

trait HasProperties
{
    private bool $isFillable = true;

    private bool $isGuarded = false;

    private bool $isUnguarded = false;

    protected function initializeHasProperties(): void
    {
        $this->checkMassAssignment();
        $this->checkPropsArrayExists();

        if (count($this->props) > 0) {
            $normalized = $this->generateProperties($this->props);

            $this->attributes = $normalized['attributes'];
            $this->setHidden($normalized['hidden']);
            $this->mergeCasts($normalized['casts']);
            $this->fillable($normalized['fillable']);

            if ($this->isUnguarded || count($normalized['guarded']) > 0) {
                $this->guard($normalized['guarded']);
            }
        }
    }

    private function checkMassAssignment(): void
    {
        if (property_exists($this, 'massAssignment')) {
            if (strtolower($this->massAssignment) === 'guarded') {
                $this->isFillable = false;
                $this->isGuarded = true;
            } elseif (strtolower($this->massAssignment) === 'unguarded') {
                $this->isUnguarded = true;
            }
        }
    }

    private function checkPropsArrayExists(): void
    {
        if ( ! property_exists($this, 'props')) {
            throw new PropArrayMissingException('HasPropertyException: in class '.get_class($this).', $props does not exist');
        }
    }

    private function generateProperties(array $props): array
    {
        $initialPropArray = [
            'fillable' => [],
            'guarded' => [],
            'hidden' => [],
            'casts' => [],
            'attributes' => [],
        ];

        return collect($props)->reduce(function ($acc, $propList, $propName) {
            if ( ! is_int($propName)) {
                $acc = collect(new Collection($propList))->reduce(function ($accumulator, $propValue, $propKey) use ($propName) {
                    match (is_int($propKey)) {
                        true => $accumulator[$propValue][] = $propName,
                        false => $accumulator[$propKey][$propName] = $propValue
                    };

                    return $accumulator;
                }, $acc);

                return $this->setGuardedOrFillable($acc, $propName);
            }

            return $this->setGuardedOrFillable($acc, $propList);
        }, $initialPropArray);
    }

    private function setGuardedOrFillable($acc, $prop): array
    {
        if ($this->isFillable && ! in_array($prop, $acc['guarded']) && ! in_array($prop, $acc['fillable'])) {
            $acc['fillable'][] = $prop;
        }

        if ($this->isGuarded && ! in_array($prop, $acc['fillable']) && ! in_array($prop, $acc['guarded'])) {
            $acc['guarded'][] = $prop;
        }

        return $acc;
    }
}
