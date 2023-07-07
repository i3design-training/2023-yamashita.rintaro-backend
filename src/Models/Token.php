<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
	// 主キーが UUID であることを Eloquent に認識させる
	use HasUuids;

	// このモデルが tokens テーブルに対応していることを Eloquent に伝える
	protected $table = 'tokens';

	// 主キーのタイプが文字列（この場合は UUID）であることを Eloquent に伝える
	protected $keyType = 'string';

	//  主キーが自動的に増加しないこと（今回の場合、UUID が手動で設定されること）を Eloquent に伝える
	public $incrementing = false;

	// このモデルがタイムスタンプ（created_at、updated_at）を自動的に管理しないことを Eloquent に伝える
	public $timestamps = false;

	// 配列内のカラム以外の値は弾く
	// この配列内のカラムは、一括代入（Mass Assignment）が可能
	protected $fillable = ['id', 'user_id', 'token', 'expiry_date'];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
