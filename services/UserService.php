<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\NewUser;

class UserService{
    public function __construct(private UserRepository $userRepository){}

    public function createUser(string $name, string $userName, string $email, string $passHash){
        $passHash = password_hash($passHash, PASSWORD_DEFAULT);

        $user = new NewUser($name, $userName, $email, $passHash);

        $this->userRepository->insert($user);
    }
}
?>