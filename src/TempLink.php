<?php

namespace RezafDev\LaravelTempLink;

use Illuminate\Support\Facades\Storage;

class TempLink
{
    private $temp_path = 'temp/';
    private $disk = 'public';

    public function __construct()
    {
        $this->temp_path = config('laravel_templink.temp_link_path');
        $this->disk = config('laravel_templink.disk');
        if(!Storage::disk($this->disk)->exists($this->temp_path)){
            Storage::disk($this->disk)->makeDirectory($this->temp_path);
        }
    }

    /**
     * @param $target
     * @param int $seconds_to_live don't use less than 600 seconds (it depends on the cron job interval and cannot be less than 100)
     * @return string
     */
    public function generateTempLink($target, $seconds_to_live = 3600): string
    {
        $expire_at = now()->timestamp + $seconds_to_live;
        $exp_1 = intval(substr($expire_at, 0,  -5));
        $exp_2 = intval(substr($expire_at, -5, -2));
        $folder = $this->temp_path . $exp_1 . '/' . $exp_2.'/';
        if(!Storage::disk($this->disk)->exists($folder)){
            Storage::disk($this->disk)->makeDirectory($folder);
        }
        $extension = pathinfo($target)['extension'];
        $link_name = uniqid().'.'.$extension;
        symlink($target, Storage::disk($this->disk)->path($folder).$link_name);
        return Storage::disk($this->disk)->url($folder.$link_name);
    }

    public function removeExpiredLinks()
    {
        $now = now()->timestamp;
        $level1_time = intval(substr($now, 0,  -5));
        $level2_time = intval(substr($now, -5, -2));

        $directories = Storage::disk($this->disk)->directories($this->temp_path);
        foreach ($directories as $dir){
            if(intval($this->removeBase($dir, $this->temp_path)) < $level1_time){
                $this->deleteDirectory(Storage::disk($this->disk)->path($dir));
            }
        }

        if(Storage::disk($this->disk)->exists($this->temp_path.$level1_time)){
            $directories = Storage::disk($this->disk)->directories($this->temp_path.$level1_time);
            foreach ($directories as $dir){
                if(intval($this->removeBase($dir, $this->temp_path.$level1_time.'/')) <= $level2_time){
                    $this->deleteDirectory(Storage::disk($this->disk)->path($dir));
                }
            }
        }
    }

    public function removeBase(string $path, string $base)
    {
        return preg_replace('/^'.str_replace('/', '\/', $base).'/', '', $path);
    }

    private function deleteDirectory(string $directory)
    {
        if ( ! is_dir($directory)) {
            return false;
        }

        $items = new \FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && ! $item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            }
            else {
                unlink($item->getPathname());
            }
        }

        rmdir($directory);
    }
}