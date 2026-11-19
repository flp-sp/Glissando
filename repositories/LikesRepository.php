<?php
    namespace App\Repositories;

    use PDO;

    class LikesRepository{
        public function __construct(public PDO $pdo){}

        public function fetchMusicLikes($musicId){
            $sql = "SELECT COUNT(*) FROM Likes WHERE fk_id_music = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $musicId]);

            return (int)$stmt->fetchColumn();
        }

        public function addNewLike($userId, $musicId){
            $sql = 'INSERT INTO Likes(fk_id_music, fk_id_user) SELECT :fk_id_music, :fk_id_user
                    WHERE NOT EXISTS(
                        SELECT 1 FROM Likes
                        WHERE fk_id_music = :fk_id_music_chk AND fk_id_user = :fk_id_user_chk
                    )';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_id_music'=> $musicId,
                            ':fk_id_user' => $userId,
                            ':fk_id_music_chk' => $musicId,
                            ':fk_id_user_chk' => $userId]);
        }
    }