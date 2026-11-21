<?php
    namespace App\Controllers;

    use App\Services\LikeService;

    class LikesController{
        public function __construct(private LikeService $likeService){}

        public function likeMusic($userId, $musicId){
            $this->likeService->likeMusic($userId, $musicId);
        }

        public function dislikeMusic($userId, $musicId){
            $this->likeService->dislikeMusic($userId, $musicId);
        }

        public function userHasLiked($userId, $musicId){
            return $this->likeService->userHasLiked($userId, $musicId);
        }
    }
?>