<?php
    namespace App\Repositories;

    use App\Models\NewMusic;
    use PDO;

    session_start();

    class MusicRepository{
        public function __construct(public PDO $pdo){}

        public function insert(NewMusic $newMusic){
            $sql = "INSERT INTO Musics(name, artist, cover_path, music_path, fk_user_upload)
                    VALUES(:name, :artist, :cover_path, :music_path, 
                    (SELECT id FROM Users WHERE user_name = :user_name))";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([':name' => $newMusic->name,
                            ':artist' => $newMusic->artist,
                            ':cover_path' => $newMusic->coverPath,
                            ':music_path' => $newMusic->musicPath,
                            ':user_name' => $_SESSION['user_name']]);
        }
    }
?>