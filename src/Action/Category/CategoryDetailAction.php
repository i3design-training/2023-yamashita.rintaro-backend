<?php

namespace App\Action\Category;

use App\Models\Category;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryDetailAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			// ルートパラメータを取得
			// "/category/123"の場合、getAttribute('id')は"123"を返す
			$categoryId = $request->getAttribute('id');

			if (!$categoryId) {
				throw new \InvalidArgumentException('CategoryIDが必要です');
			}

			$category = Category::find($categoryId);

			if (!$category) {
				throw new Exception('指定されたIDのカテゴリーは存在しません');
			}

			Log::info('Category詳細取得: ' . $category->id);

			$response->getBody()->write(json_encode($category));

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
