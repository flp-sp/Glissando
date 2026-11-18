<?php
    namespace App\Models;

    use DateTime;

    class Music{
        public function __construct(
            public int $id,
            public string $name,
            public string $artist,
            public ?string $coverPath,
            public int $views,
            public DateTime $datetimeUpload,
            public int $fkUserUpload,
            public string $musicPath,
            public string $uploaderName
            # likes
        ){}
    }
?>