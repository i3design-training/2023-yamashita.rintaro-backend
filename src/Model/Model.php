<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Constraint\Uuid;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use Uuid;
}
