<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Support;

use JoeCianflone\HasProperties\Exceptions\PropMassAssignmentException;

class MassAssignment
{
    public function __construct(
        private bool $fillable = true,
        private bool $guarded = false,
        private bool $unguarded = false,
    ) {
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
