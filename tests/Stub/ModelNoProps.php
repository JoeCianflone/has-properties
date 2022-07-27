<?php declare(strict_types=1);

namespace Inizio\HasProperties\Tests\Stub;

use Illuminate\Database\Eloquent\Model;
use Inizio\HasProperties\Traits\HasProperties;

class ModelNoProps extends Model
{
    use HasProperties;
}
