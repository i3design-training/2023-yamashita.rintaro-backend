<?php

namespace App\Action\User;

use App\Helper\Email;
use App\Helper\CreateToken;
use App\Models\User;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserProvisionalRegistrationAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // リクエストからデータを収集
        try {
            $requestBody = $request->getBody()->getContents();
            $decodedRequestBody = json_decode($requestBody);

            $useCaseRequest = User::create(
                [
                    'username' => $decodedRequestBody->username,
                    'password' => $decodedRequestBody->password,
                    'email' => $decodedRequestBody->email
                ]
            );

            // メールのリンクを押したらemailVerificationカラムをtrueにする処理はどこのファイルで行うべき？
            //      UserFullRegistration.phpで行う
            // emailVerifiedToken = bin2hex(random_bytes(32));はHelperで問題なさそう
            // emailVerifiedTokenをインポートしてきて、sendEmailの引数として送信する

            $subject = "ユーザ仮登録完了通知";
            $body = 'ユーザ仮登録が完了しました。以下のURLから本登録を完了してください。';

            // 本登録メールの送信
            Email::sendEmail($decodedRequestBody->email, $subject, $body, CreateToken::createToken());

            $response->getBody()->write("New user registered successfully");
            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write("Error: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
