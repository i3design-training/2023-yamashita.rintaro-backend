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

	public function verifyPassword(string $password): bool
	{
		// password_verify: パスワードがハッシュにマッチするかどうかを調べる
		return password_verify($password, $this->password);
	}

	public function token()
	{
		return $this->hasOne('App\Models\Token');
	}

	public function emailVerification()
	{
		return $this->hasOne('App\Models\EmailVerification');
	}

	public function tasks()
	{
		return $this->hasMany('App\Models\Task');
	}

	public function getAllTasks()
	{
		return $this->tasks()->get();
	}
}
