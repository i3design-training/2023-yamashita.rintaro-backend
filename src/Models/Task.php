<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	use HasUuids;
	protected $table = 'tasks';
	protected $dates = ['created_at', 'updated_at'];
	protected $keyType = 'string';
	public $incrementing = false;
	protected $fillable = [
		'id',
		'user_id',
		'category_id',
		'title',
		'description',
		'due_date',
		'status_id',
		'created_at',
		'updated_at',
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function category()
	{
		return $this->belongsTo('App\Models\Category');
	}

	public function status()
	{
		return $this->belongsTo('App\Models\TaskStatus');
	}
}
