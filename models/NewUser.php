<?php
namespace App\Models;

class NewUser{
    public function __construct(
        public string $name,
        public string $userName,
        public string $email,
        public string $passHash
    ){}
}
?>