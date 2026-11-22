<?php
    namespace App\Repositories;

    use PDO;

    class DashboardRepository{
        public function __construct(public PDO $pdo){}

        public function fetchTotalViewsByUser(int $idUser){
            $sql = "SELECT SUM(views) FROM Musics
                    WHERE fk_user_upload = :fk_user_upload";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_user_upload' => $idUser]);

            return (int)$stmt->fetchColumn();
        }

        public function fetchMeanViewsByUser(int $idUser){
            $sql = "SELECT AVG(views) FROM Musics
                    WHERE fk_user_upload = :fk_user_upload";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_user_upload' => $idUser]);

            return (int)$stmt->fetchColumn();
        }

        public function fetchMeanLikesByUser(int $idUser){
            $sql = "SELECT AVG(contagem) FROM(
                        SELECT Musics.id, COUNT(Likes.id) AS contagem 
                        FROM Musics
                        LEFT JOIN Likes ON Musics.id = Likes.fk_id_music
                        WHERE fk_user_upload = :fk_user_upload
                        GROUP BY Musics.id
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_user_upload' => $idUser]);

            return (int)$stmt->fetchColumn();
        }

        public function fetchTopMusicsByViews(int $idUser){
            $sql = "SELECT id, name, views FROM Musics
                    WHERE fk_user_upload = :fk_user_upload
                    ORDER BY views DESC
                    LIMIT 3";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_user_upload' => $idUser]);

            return $stmt->fetchAll();
        }
    }
?>