<?php

namespace App\Helper;

class CreateToken
{
	public static function createToken(): string
	{
		$token = bin2hex(random_bytes(32));

		return $token;
	}
}
