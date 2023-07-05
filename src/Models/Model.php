<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

abstract class Model extends BaseModel
{
    // 主キーをUUIDとする
    use HasUuids;

    // モデルにタイムスタンプをつけない
    public $timestamps = false;
}
