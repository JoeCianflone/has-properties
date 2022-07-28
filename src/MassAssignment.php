<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties;

use JoeCianflone\HasProperties\Exceptions\PropMassAssignmentException;

class MassAssignment
{
    public function __construct(
        private ?bool $fillable = null,
        private ?bool $guarded = null,
        private ?bool $unguarded = null,
    ) {
        $this->fillable = $fillable ?? config('hasproperties.fillable');
        $this->guarded = $guarded ?? config('hasproperties.guarded');
        $this->unguarded = $unguarded ?? config('hasproperties.unguarded');

        if ($this->fillable && $this->guarded) {
            throw new PropMassAssignmentException('Cannot set properties to both Fillable AND Guarded');
        }
    }

    public function isFillable(): bool
    {
        return $this->fillable;
    }

    public function isGuarded(): bool
    {
        return $this->guarded;
    }

    public function isUnguarded(): bool
    {
        return $this->unguarded;
    }
}
