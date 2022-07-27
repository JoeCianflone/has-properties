<?php declare(strict_types=1);

namespace Inizio\HasProperties\Tests\Stub;

use Illuminate\Database\Eloquent\Model;
use Inizio\HasProperties\Traits\HasProperties;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ModelStub extends Model
{
    use HasProperties;

    public $incrementing = false;

    protected array $props = [
        'id' => ['casts' => 'string', 'attributes' => '1123'],
        'name',
        'email' => ['guarded'],
        'password' => ['hidden'],
        'is_boolean' => ['casts' => 'boolean'],
        'is_enum' => ['casts' => EnumTestStub::class, 'attributes' => EnumTestStub::ONE],
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value).'__SECRET__'
        );
    }
}
