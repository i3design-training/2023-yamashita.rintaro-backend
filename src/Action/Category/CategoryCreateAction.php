<?php

namespace App\Action\Category;

use App\Models\Category;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryCreateAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			$requestBody = $request->getBody()->getContents();
			$decodedRequestBody = json_decode($requestBody);
			$categoryName = $decodedRequestBody->name;

			$newUser = Category::create(
				[
					'name' => $decodedRequestBody->name
				]
			);

			$response->getBody()->write("カテゴリー登録が成功しました");
			return $response->withStatus(201);
		} catch (\InvalidArgumentException $e) {
			Log::error('無効な入力データ: ' . $e->getMessage());
			$response->getBody()->write("無効な入力データ");
			return $response->withStatus(400);
		} catch (Exception $e) {
			Log::error('エラー: ' . $e->getMessage());
			$response->getBody()->write("エラーが発生しました");
			return $response->withStatus(500);
		}
	}
}
