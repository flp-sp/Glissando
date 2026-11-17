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

        public function serveCover(string $filename){
            $path = __DIR__ . '/../storage/covers/' . $filename;

            header('Content-Type: ' . mime_content_type($path));
            header('Content-Length: ' . filesize($path));
            header('Cache-Control: private, max-age=86400');
            readfile($path);
            exit;    
        }
        
        public function serveMusic(string $filename){
             $path = __DIR__ . '/../storage/musics/' . $filename;

            // Libera o lock da sessão para não travar outras requisições durante o stream
            session_write_close();

            $size  = filesize($path);
            $mime  = mime_content_type($path);
            $start = 0;
            $end   = $size - 1;

            header('Content-Type: ' . $mime);
            header('Accept-Ranges: bytes');
            header('Cache-Control: private, max-age=86400');

            if (isset($_SERVER['HTTP_RANGE'])
                && preg_match('/bytes=(\d*)-(\d*)/', $_SERVER['HTTP_RANGE'], $r)) {

                if ($r[1] === '' && $r[2] !== '') {
                    // "bytes=-500" = últimos 500 bytes
                    $start = max(0, $size - (int)$r[2]);
                } else {
                    $start = (int)$r[1];
                    if ($r[2] !== '') {
                        $end = min((int)$r[2], $size - 1);
                    }
                }

                if ($start > $end || $start >= $size) {
                    http_response_code(416);
                    header("Content-Range: bytes */$size");
                    exit;
                }

                http_response_code(206);
                header("Content-Range: bytes $start-$end/$size");
            }

            header('Content-Length: ' . ($end - $start + 1));

            $fp = fopen($path, 'rb');
            fseek($fp, $start);

            $remaining = $end - $start + 1;
            while ($remaining > 0 && !feof($fp)) {
                $chunk = min(8192, $remaining);
                echo fread($fp, $chunk);
                $remaining -= $chunk;
                flush();
            }

            fclose($fp);
            exit;
        }
    }
?>