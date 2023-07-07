<?php

namespace App\Action\User;

use App\Models\EmailVerification;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;

class UserFullRegistrationAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        try {
            Log::debug(sprintf('本登録開始'));

            // getQueryParams: クエリパラメーターを連想配列として取得
            $queryParams = $request->getQueryParams();
            // トークンが存在するか確認する
            if (!isset($queryParams['token'])) {
                throw new \InvalidArgumentException('トークンが存在しません', 400);
            }

            $token = $queryParams['token'];
            Log::debug(sprintf('トークン: %s', $token));

            // $tokenと一致するemail_verificationsを取得
            $emailVerification = EmailVerification::where('token', $token)->first();

            $user = User::where('id', $emailVerification->user_id)->first();
            $user->email_verified = true;
            $user->save();

            // JSON_UNESCAPED_UNICODE: Unicode文字（日本語など）をエスケープさせない
            $response->getBody()->write(json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\InvalidArgumentException $e) {
            Log::error($e->getMessage());

            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
