<?php

namespace App\Action\TaskStatus;

use App\Models\TaskStatus;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskStatusDetailAction
{
	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface {
		try {
			// ルートパラメータを取得
			// "/TaskStatus/123"の場合、getAttribute('id')は"123"を返す
			$taskStatusId = $request->getAttribute('id');

			if (!$taskStatusId) {
				throw new \InvalidArgumentException('taskStatusIDが必要です');
			}

			$taskStatus = TaskStatus::find($taskStatusId);

			if (!$taskStatus) {
				throw new Exception('指定されたIDのステータスは存在しません');
			}

			Log::info('TaskStatus詳細取得: ' . $taskStatus->id);

			$response->getBody()->write(json_encode($taskStatus));

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
