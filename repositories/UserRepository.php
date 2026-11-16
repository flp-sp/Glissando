<?php
namespace App\Repositories;

use App\Models\NewUser;
use PDO;


class UserRepository{
    public function __construct(
        private PDO $pdo
    ){}

    public function insert(NewUser $user){
        $sql = "INSERT INTO Users (name, user_name, email, pass_hash) VALUES(:name, :user_name, :email, :pass_hash)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([':name' => $user->name,
                        ':user_name' => $user->userName,
                        ':email' => $user->email,
                        ':pass_hash' => $user->passHash]);
    }
}
?>