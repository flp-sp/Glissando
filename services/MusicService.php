<?php
    namespace App\Services;

    use App\Repositories\MusicRepository;
    use App\Models\NewMusic;

    class MusicService{
        public function __construct(private MusicRepository $musicRepository){}
        public function create(string $name, string $artist, ?string $coverPath, string $musicPath){
            $newMusic = new NewMusic($name, $artist, $coverPath, $musicPath);
            $this->musicRepository->insert($newMusic);

        }

        public function getMusicsTab(int $step){
            if($step > 0){
                $step--;
            }
            return $this->musicRepository->getMusics($step);
        }    

        public function save(array $fileCover, array $fileMusic){
            $finfoCover = new \finfo(FILEINFO_MIME_TYPE);
            $mimeCover = $finfoCover->file($fileCover['tmp_name']);

            $finfoMusic = new \finfo(FILEINFO_MIME_TYPE);
            $mimeMusic = $finfoMusic->file($fileMusic['tmp_name']);  

            $allowed = [
                // Imagens
                'image/jpeg'   => 'jpg',
                'image/png'    => 'png',
                'image/webp'   => 'webp',

                // Áudio
                'audio/mpeg'   => 'mp3',
                'audio/mp4'    => 'm4a',
                'audio/x-m4a'  => 'm4a',
                'audio/webm'   => 'webm',
                'video/webm'   => 'webm',
                'audio/ogg'    => 'ogg',
                'audio/wav'    => 'wav',
                'audio/x-wav'  => 'wav',
                'audio/flac'   => 'flac',
            ];
            
            $filenameCover = bin2hex(random_bytes(16)) . '.' . $allowed[$mimeCover];
            $filenameMusic = bin2hex(random_bytes(16)) . '.' . $allowed[$mimeMusic];

            $dirCovers = __DIR__ . '/../storage/covers';
            if (!is_dir($dirCovers)) {
                mkdir($dirCovers, 0755, true);
            }

            $dirMusics = __DIR__ . '/../storage/musics';
            if (!is_dir($dirMusics)) {
                mkdir($dirMusics, 0755, true);
            }

            move_uploaded_file($fileCover['tmp_name'], $dirCovers . '/' . $filenameCover);

            move_uploaded_file($fileMusic['tmp_name'], $dirMusics . '/' . $filenameMusic);

            return ['cover' => $filenameCover, 'music'=> $filenameMusic];
        }
    }
?>