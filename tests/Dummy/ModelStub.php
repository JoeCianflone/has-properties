<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use JoeCianflone\HasProperties\Traits\HasProperties;

class ModelStub extends Model
{
    use HasProperties;

    public $incrementing = false;

    protected array $props = [
        'id' => ['casts' => 'string', 'attributes' => '1123'],
        'name' => ['fillable'],
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
