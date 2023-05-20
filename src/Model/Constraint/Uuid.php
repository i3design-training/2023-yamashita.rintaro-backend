<?php

declare(strict_types=1);

namespace App\Model\Constraint;

use Ramsey\Uuid\Uuid as UuidGenerator;

trait Uuid
{
    public function newUniqueId()
    {
        return UuidGenerator::uuid6()->toString();
    }
}
