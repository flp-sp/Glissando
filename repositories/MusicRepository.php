<?php
    namespace App\Repositories;

    use App\Models\NewMusic;
    use App\Models\Music;

    use PDO;

    define("MAX_PER_PAGE", 5);

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

        public function getMusics(int $step){
            $sql = "SELECT * FROM Musics ORDER BY datetime_uploaded
                    LIMIT :max_per_page OFFSET :step";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':max_per_page' => MAX_PER_PAGE,
                            ':step' => (MAX_PER_PAGE * $step)]);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

          return array_map(fn(array $row) => new Music(
                id: (int) $row['id'],
                name: $row['name'],
                artist: $row['artist'],
                coverPath: $row['cover_path'],
                views: (int) $row['views'],
                datetimeUpload: new \DateTime($row['datetime_uploaded']),
                fkUserUpload: (int) $row['fk_user_upload'],
                musicPath: $row['music_path'],
            ), $rows);
        }
    }
?>