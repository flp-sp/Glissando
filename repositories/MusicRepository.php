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

        public function getMusics(int $step, $filter_order, $order_orientation,
                                    $artist_filter, $id_user_filter){

            $filters = [];
            $params = [];
            $where = '';

            if($artist_filter != 'all'){
                $filters[] = 'artist = :artist'; 
                $params[':artist'] = $artist_filter;
            }

            if($id_user_filter != 0){
                $filters[] = 'fk_user_upload = :fk_user_upload';
                $params[':fk_user_upload'] = $id_user_filter;
            }

            if($filters){
                $where .= 'WHERE '. implode(' AND ', $filters);
            }

            $sql = "SELECT Musics.*, Users.user_name AS uploader_name FROM Musics
                    INNER JOIN Users ON Musics.fk_user_upload = Users.id
                    $where
                    ORDER BY $filter_order $order_orientation
                    LIMIT :max_per_page OFFSET :step";
            
            $stmt = $this->pdo->prepare($sql);

            $params[':max_per_page'] = MAX_PER_PAGE;
            $params[':step'] = (MAX_PER_PAGE * $step);

            $stmt->execute($params);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($rows){
                return $this->dataToArray($rows);
            }
            else{
                return [];
            }
        }

        public function getUserMusics(string $userName){
            $sql = 'SELECT Musics.name, artist, cover_path, views, datetime_uploaded, fk_user_upload, music_path, Users.user_name AS uploader_name FROM Musics
                    INNER JOIN Users ON Musics.fk_user_upload = Users.id
                    WHERE user_name = :user_name';
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['user_name'=> $userName]);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->dataToArray($rows);
        }

        public function fetchColumnData(string $column){
            $sql = "SELECT $column FROM Musics";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        private function dataToArray(array $rows){
            return array_map(fn(array $row) => new Music(
                id: (int) $row['id'],
                name: $row['name'],
                artist: $row['artist'],
                coverPath: $row['cover_path'],
                views: (int) $row['views'],
                datetimeUpload: new \DateTime($row['datetime_uploaded']),
                fkUserUpload: (int) $row['fk_user_upload'],
                musicPath: $row['music_path'],
                uploaderName: $row['uploader_name'],
            ), $rows);
        }
    }
?>