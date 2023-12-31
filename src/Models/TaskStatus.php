<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
	use HasUuids;
	public const UPDATED_AT = null;
	public const CREATED_AT = null;
	protected $table = 'taskstatus';
	protected $keyType = 'string';
	public $incrementing = false;
	protected $fillable = ['id', 'name'];

	public function task()
	{
		return $this->hasMany('App\Models\Task');
	}
}
