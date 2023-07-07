<?php

namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Log\Log;

class Email
{
	public static function sendEmail(string $email, string $subject, string $body, string $token): array
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
			Log::info(sprintf('送信開始しました'));
			//サーバの設定
			$mail->isSMTP();   // SMTP を使用
			$mail->Host       = $_ENV['MAIL_HOST']; // SMTP サーバーを指定
			$mail->SMTPAuth   = true;   // SMTP authentication を有効に
			$mail->Username   = $_ENV['MAIL_USERNAME'];
			$mail->Password   = $_ENV['MAIL_PASSWORD'];
			$mail->SMTPSecure = 'tls';
			$mail->Port       = $_ENV['MAIL_PORT'];

			$mail->SMTPDebug = 2;

			$url = 'http://localhost:5173/registrationComplete?token=' . $token;

			//受信者設定 
			//※名前などに日本語を使う場合は文字エンコーディングを変換
			//差出人アドレス, 差出人名
			$mail->setFrom($_ENV['MAIL_USERNAME'], mb_encode_mimeheader($_ENV['MAIL_SENDER']));
			//受信者アドレス, 受信者名（受信者名はオプション）
			$mail->addAddress("yamashita.rintaro@i3design.co.jp");
			//コンテンツ設定
			$mail->isHTML(true);   // HTML形式を指定
			//メール表題（文字エンコーディングを変換）
			$mail->Subject = mb_encode_mimeheader($subject);
			//HTML形式の本文（文字エンコーディングを変換）
			$mail->Body  = mb_convert_encoding($body . "\n" . $url, "JIS", "UTF-8");

			$mail->send();

			Log::info(sprintf('送信完了'));

			return ['status' => true, 'message' => 'Message has been sent'];
		} catch (Exception $e) {
			//エラー（例外：Exception）が発生した場合
			return ['status' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
		}
	}
}
