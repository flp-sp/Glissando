<?php
    namespace App\Services;

    use App\Repositories\LikesRepository;

    class LikeService{
        public function __construct(private LikesRepository $likesRepository){}

        public function likeMusic($userId, $musicId){
            $this->likesRepository->addNewLike($userId, $musicId);
        }

        public function getMusicLikes($musicId){
            return $this->likesRepository->fetchMusicLikes($musicId);
        }
    }
?>