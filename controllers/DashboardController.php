<?php
    namespace App\Controllers;

    use App\Services\DashboardService;
    use App\Services\UserService;

    class DashboardController{
        public function __construct(private DashboardService $dashboardService, private UserService $userService){}

        public function getTotalViewsByUser(){
            $user = $this->userService->searchUserByUserName($_GET['username']);

            return $this->dashboardService->getTotalViewsByUser((int)$user['id']);
        }

        public function getMeanViewsByUser(){
            $user = $this->userService->searchUserByUserName($_GET['username']);

            return $this->dashboardService->getMeanViewsByUser((int)$user['id']);
        }

        public function getMeanLikesByUser(){
            $user = $this->userService->searchUserByUserName($_GET['username']);

            return $this->dashboardService->getMeanLikesByUser((int)$user['id']);
        }

        public function getTopMusicsByViews(){
            $user = $this->userService->searchUserByUserName($_GET['username']);

            return $this->dashboardService->getTopMusicsByViews((int)$user['id']);
        }
    }
?>