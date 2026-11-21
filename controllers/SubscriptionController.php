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
    }
?>