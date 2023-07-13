<?php

namespace App\Action\User;

use App\Models\User;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserUpdateAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			Log::info('ユーザーの更新開始');
			$username = $request->getAttribute('username');

			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody, true);

			Log::info('Request Body: ' . print_r($decodedRequestBody));

			if (!$username) {
				throw new \InvalidArgumentException('usernameが必要です');
			}

			$user = User::where('username', $username)->first();

			if (!$user) {
				throw new Exception('指定された名前のuserは存在しません');
			}

			// $fillable プロパティに指定された属性だけをマスアサインメント
			$user->fill($decodedRequestBody);
			$user->save();

			Log::info('タスクの更新後: ' . print_r($user->toArray(), true));

			$response->getBody()->write(json_encode($user));

			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		} catch (\InvalidArgumentException $e) {
			Log::error('無効な入力データ: ' . $e->getMessage());
			$response->getBody()->write(json_encode(['error' => $e->getMessage()]));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(400);
		} catch (Exception $e) {
			Log::error('エラー: ' . $e->getMessage());
			$response->getBody()->write(json_encode(['error' => $e->getMessage()]));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(500);
		}
	}
}
