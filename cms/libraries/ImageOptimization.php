<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class ImageOptimization
{
    public $key = TINYPNG;
    public $supported = array('jpg', 'png', 'jpeg');

    public function optimize($path)
    {
        try {
            require __DIR__ . '/tinypng/autoload.php';

            $info = pathinfo($path);

            if(!in_array(strtolower($info['extension']), $this->supported))
                return false;

            Tinify\setKey($this->key);
            $source = Tinify\fromFile($path);
            $source->toFile($path);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}