<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use HasUuids;

	protected $table = 'users';
	protected $dates = ['created_at', 'updated_at'];
	protected $keyType = 'string';
	public $incrementing = false;
	protected $fillable = ['id', 'username', 'email', 'password', 'email_verified', 'created_at', 'updated_at'];


	public $timestamps = false;
}
