<?php
    namespace App\Services;

    use App\Repositories\SubscriptionRepository;

    class SubscriptionService{
        public function __construct(private SubscriptionRepository $subscriptionRepository){}

        public function subscribe(int $idFollowed, int $idFollowing){
            $this->subscriptionRepository->addNewSubscription($idFollowed, $idFollowing);
        }

        public function getSubscriptionNumber($idUser){
            return $this->subscriptionRepository->fetchUsersSubscriptions($idUser);
        }

        public function unsubscribe(int $idFollowed, int $idFollowing){
            $this->subscriptionRepository->removeSubscription($idFollowed, $idFollowing);
        }

        public function isSubscribed(int $idFollowed, int $idFollowing){
            return $this->subscriptionRepository->isSubscribed($idFollowed, $idFollowing);
        }
    }
?>