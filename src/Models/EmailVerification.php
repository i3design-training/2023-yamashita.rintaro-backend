<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

// 注意❗️
// メール認証のトークンを保存する
// ログイン後の認証トークンではない
class EmailVerification extends Model
{
	use HasUuids;
	public const UPDATED_AT = null;
	protected $table = 'email_verifications';
	protected $dates = ['created_at'];
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	protected $fillable = ['user_id', 'token'];

	public function getToken()
	{
		return $this->token;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
