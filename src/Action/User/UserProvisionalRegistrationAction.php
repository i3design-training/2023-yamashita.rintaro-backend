<?php

namespace App\Action\User;

use App\Models\User;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log\Log;
use PHPMailer\PHPMailer\PHPMailer;

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

            // メールの送信
            $this->sendConfirmEmail($decodedRequestBody->email);

            $response->getBody()->write("New user registered successfully");
            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write("Error: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }

    public function sendConfirmEmail(string $email): array
    {
        //言語、内部エンコーディングを指定
        mb_language("japanese");
        mb_internal_encoding("UTF-8");

        // インスタンスを生成（引数に true を指定して例外 Exception を有効に）
        $mail = new PHPMailer(true);

        //日本語用設定
        $mail->CharSet = "iso-2022-jp";
        $mail->Encoding = "7bit";

        try {
            Log::info(sprintf('送信開始'));
            //サーバの設定
            $mail->isSMTP();   // SMTP を使用
            $mail->Host = $_ENV['MAIL_HOST']; // SMTP サーバーを指定
            $mail->SMTPAuth   = true;   // SMTP authentication を有効に
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $_ENV['MAIL_PORT'];

            $mail->SMTPDebug = 2;

            //受信者設定 
            //※名前などに日本語を使う場合は文字エンコーディングを変換
            //差出人アドレス, 差出人名
            $mail->setFrom('sender@example.com', mb_encode_mimeheader('差出人名'));
            //受信者アドレス, 受信者名（受信者名はオプション）
            $mail->addAddress("yamashita.rintaro@i3design.co.jp", mb_encode_mimeheader("受信者名"));
            //コンテンツ設定
            $mail->isHTML(true);   // HTML形式を指定
            //メール表題（文字エンコーディングを変換）
            $mail->Subject = mb_encode_mimeheader('日本語メールタイトル');
            //HTML形式の本文（文字エンコーディングを変換）
            $mail->Body  = mb_convert_encoding('メッセージ <b>BOLD</b>', "JIS", "UTF-8");
            //テキスト形式の本文（文字エンコーディングを変換）
            $mail->AltBody = mb_convert_encoding('テキストメッセージ', "JIS", "UTF-8");

            // ここで送れていない！
            $mail->send();  //送信

            Log::info(sprintf('送信完了'));

            return ['status' => true, 'message' => 'Message has been sent'];
        } catch (Exception $e) {
            //エラー（例外：Exception）が発生した場合
            return ['status' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
    }
}
