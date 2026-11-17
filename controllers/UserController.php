<?php
namespace App\Controllers;

use App\Services\UserService;

class UserController{
    public function __construct(private UserService $userService){}

    public function create(){
        try{
            $name = $_POST["name"] ?? '';
            $username = $_POST["username"] ?? '';
            $email = $_POST["email"] ?? '';
            $password = $_POST["password"] ?? '';

            $this->userService->createUser($name, $username, $email, $password);

            echo 'chama!'; # Mudar para o endpoint das musicas 
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    public function login(){
        try{
            $userName = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if($this->userService->authenticate($userName, $password)){
                echo 'chama';# Mudar para o endpoint das musicas 
            }
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }
}
?>