<?php
namespace App\Models;

use DateTime;

class User{
    public function __construct(
        public int $id,
        public string $name,
        public string $userName,
        public string $email,
        public string $passHash,
        public DateTime $dateTimeCreated 
    ){}
}
?>