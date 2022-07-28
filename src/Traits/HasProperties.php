<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Traits;

use Illuminate\Support\Collection;
use JoeCianflone\HasProperties\Support\MassAssignment;
use JoeCianflone\HasProperties\Exceptions\PropArrayMissingException;

trait HasProperties
{
    private MassAssignment $massAssignment;

    protected function setMassAssignment(): MassAssignment
    {
        return new MassAssignment();
    }

    protected function initializeHasProperties(): void
    {
        $this->props = $this->getProps();
        $this->massAssignment = $this->setMassAssignment();

        if (count($this->props) <= 0) {
            return;
        }

        $normalized = $this->generateProperties($this->props);

        $this->attributes = $normalized['attributes'];
        $this->setHidden($normalized['hidden']);
        $this->mergeCasts($normalized['casts']);
        $this->fillable($normalized['fillable']);

        if ($this->massAssignment->isUnguarded() || count($normalized['guarded']) > 0) {
            $this->guard($normalized['guarded']);
        }
    }

    private function getProps(): array
    {
        if ( ! property_exists($this, 'props')) {
            throw new PropArrayMissingException('$props array does not exist in '.__CLASS__);
        }

        return $this->props;
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

    private function setGuardedOrFillable(array $acc, mixed $prop): array
    {
        if ($this->massAssignment->isFillable() && ! in_array($prop, $acc['guarded']) && ! in_array($prop, $acc['fillable'])) {
            $acc['fillable'][] = $prop;

            return $acc;
        }

        if ($this->massAssignment->isGuarded() && ! in_array($prop, $acc['fillable']) && ! in_array($prop, $acc['guarded'])) {
            $acc['guarded'][] = $prop;

            return $acc;
        }

        return $acc;
    }
}
