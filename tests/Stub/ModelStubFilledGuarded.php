<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Tests\Stub;

use Illuminate\Database\Eloquent\Model;
use JoeCianflone\HasProperties\MassAssignment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use JoeCianflone\HasProperties\Traits\HasProperties;

class ModelStubFilledGuarded extends Model
{
    use HasProperties;

    public $incrementing = false;

    protected array $props = [
        'id' => ['casts' => 'string', 'attributes' => '1123'],
        'name' => ['guarded'],
        'email' => ['fillable'],
        'password' => ['hidden'],
        'is_boolean' => ['casts' => 'boolean'],
        'is_enum' => ['casts' => EnumTestStub::class, 'attributes' => EnumTestStub::ONE],
    ];

    protected function setMassAssignment(): MassAssignment
    {
        return new MassAssignment(
            fillable: true,
            guarded: true,
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value).'__SECRET__'
        );
    }
}
