<?php

namespace App\Domain\User\Repository;

// PDO（PHP Data Objects）を使ってデータベースと通信
use PDO;

class UserCreatorRepository
{
 	// データベースとの通信に使用
	private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertUser(array $user): int
    {
				// SQLクエリのテンプレートを作成
				//:username, :email, :passwordはプレースホルダ。それぞれ後から実際の値に置き換えられる
        $sql = "INSERT INTO users SET username=:username, email=:email, password=:password;";
        
				// prepare()：SQLインジェクション攻撃からの保護や、パラメータのバインディング
				$stmt = $this->connection->prepare($sql);
        
				// SQLクエリを実行
				$stmt->execute($user);

				// 新たに挿入されたレコードのIDを返す
        return (int)$this->connection->lastInsertId();
    }
}