<?php
    namespace App\Controllers;

    use App\Services\LikeService;

    class LikesController{
        public function __construct(private LikeService $likeService){}

        public function likeMusic($userId, $musicId){
            $this->likeService->likeMusic($userId, $musicId);
        }
    }
?>