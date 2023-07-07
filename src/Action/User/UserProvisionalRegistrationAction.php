<?php

namespace App\Action\User;

use App\Helper\Email;
use App\Helper\CreateToken;
use App\Models\User;
use App\Models\EmailVerification;
use Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserProvisionalRegistrationAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        try {
            $requestBody = $request->getBody()->getContents();
            $decodedRequestBody = json_decode($requestBody);

            // 入力値の検証
            if ($decodedRequestBody === null || !isset($decodedRequestBody->username) || !isset($decodedRequestBody->password) || !isset($decodedRequestBody->email)) {
                throw new \InvalidArgumentException('無効な入力データ');
            }

            // ユーザーの作成
            // パスワードはハッシュ化して保存
            $newUser = User::create(
                [
                    'username' => $decodedRequestBody->username,
                    'password' => password_hash($decodedRequestBody->password, PASSWORD_DEFAULT),
                    'email' => $decodedRequestBody->email
                ]
            );

            $subject = "ユーザ仮登録完了通知";
            $body = 'ユーザ仮登録が完了しました。以下のURLから本登録を完了してください。';
            $token = CreateToken::createToken();

            // メールの送信結果を確認
            $result = Email::sendEmail($decodedRequestBody->email, $subject, $body, $token);
            if (!$result['status']) {
                throw new \Exception('メールの送信に失敗しました: ' . $result['message']);
            }

            Log::info('新規EmailVerifications作成を開始');

            // EmailVerificationの作成
            $newEmailVerification = EmailVerification::create(
                [
                    'user_id' => $newUser->id,
                    'token' => $token
                ]
            );

            Log::info('新規EmailVerificationを作成: ' . json_encode($newEmailVerification));

            $response->getBody()->write("新規ユーザー登録が成功しました");
            return $response->withStatus(201);
        } catch (\InvalidArgumentException $e) {
            Log::error('無効な入力データ: ' . $e->getMessage());
            $response->getBody()->write("無効な入力データ");
            return $response->withStatus(400);
        } catch (\Exception $e) {
            Log::error('エラー: ' . $e->getMessage());
            $response->getBody()->write("エラーが発生しました");
            return $response->withStatus(500);
        }
    }
}
