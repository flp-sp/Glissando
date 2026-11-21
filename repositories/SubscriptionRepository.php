<?php
    namespace App\Repositories;

    use PDO;

    class SubscriptionRepository{
        public function __construct(public PDO $pdo){}

        public function fetchUsersSubscriptions($idFollowed){
            $sql = "SELECT COUNT(*) FROM Subscriptions WHERE fk_id_followed = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $idFollowed]);

            return (int)$stmt->fetchColumn();
        }

        public function addNewSubscription($idFollowed, $idFollowing){
            $sql = 'INSERT INTO Subscriptions(fk_id_followed, fk_id_following) SELECT :fk_id_followed, :fk_id_following
                    WHERE NOT EXISTS(
                        SELECT 1 FROM Subscriptions
                        WHERE fk_id_followed = :fk_id_followed_chk AND fk_id_following = :fk_id_following_chk
                    )';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':fk_id_followed'=> $idFollowed,
                            ':fk_id_following' => $idFollowing,
                            ':fk_id_followed_chk' => $idFollowed,
                            ':fk_id_following_chk' => $idFollowing]);
        }
    }