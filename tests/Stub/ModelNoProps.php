<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties\Tests\Stub;

use Illuminate\Database\Eloquent\Model;
use JoeCianflone\HasProperties\Traits\HasProperties;

class ModelNoProps extends Model
{
    use HasProperties;
}
