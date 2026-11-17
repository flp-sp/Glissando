<?php
    namespace App\Controllers;

    use App\Services\MusicService;

    class MusicController{
        public function __construct(private MusicService $musicService){}

        public function upload(){
            try{
                $fileCover = $_FILES['cover'];
                $fileMusic = $_FILES['music'];

                $name = $_POST["name"];
                $artist = $_POST["artist"];
                
                $paths = $this->musicService->save($fileCover, $fileMusic);

                $this->musicService->create($name, $artist, $paths['cover'], $paths['music']);

                header('Location: /homepage');
            } catch(\Exception $e){
                echo $e->getMessage();
            }
        }
    }
?>