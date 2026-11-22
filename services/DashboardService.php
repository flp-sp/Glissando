<?php
    namespace App\Services;

    use App\Repositories\DashboardRepository;

    class DashboardService{
        public function __construct(private DashboardRepository $dashboardRepository){}

        public function getTotalViewsByUser(int $userId){
            return $this->dashboardRepository->fetchTotalViewsByUser($userId);
        }

        public function getMeanViewsByUser(int $userId){
            return $this->dashboardRepository->fetchMeanViewsByUser($userId);
        }

        public function getMeanLikesByUser(int $userId){
            return $this->dashboardRepository->fetchMeanLikesByUser($userId);
        }

        public function getTopMusicsByViews(int $idUser){
            return $this->dashboardRepository->fetchTopMusicsByViews($idUser);
        }
    }
?>