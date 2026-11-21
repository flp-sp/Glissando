<?php
    namespace App\Controllers;

    use App\Services\SubscriptionService;

    class SubscriptionController{
        public function __construct(private SubscriptionService $subscriptionService){}

        public function subscribeUser($idFollowed, $idFollowing){
            $this->subscriptionService->subscribe($idFollowed, $idFollowing);
        }

        public function getSubscriptionNumber($idUser){
            return $this->subscriptionService->getSubscriptionNumber($idUser);
        }

        public function unsubscribeUser($idFollowed, $idFollowing){
            $this->subscriptionService->unsubscribe($idFollowed, $idFollowing);
        }

        public function isSubscribed($idFollowed, $idFollowing){
            return $this->subscriptionService->isSubscribed($idFollowed, $idFollowing);
        }
    }
?>