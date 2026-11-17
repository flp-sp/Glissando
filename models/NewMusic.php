<?php
    namespace App\Models;

    class NewMusic{
        public function __construct(
            public string $name,
            public string $artist,
            public ?string $coverPath,
            public string $musicPath
        ){}
    }
?>