<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;

class FileService {
    /**
     * Get size of all files in directory or root
     * 
     * @param Request $request
     * @return int
     */
    public function getFilesSize(Request $request) {
        $disk = $request->user()->diskConnection();
        $files = $disk->files($request->folder);
        return $this->calculateSize($files, $disk);
    }

    /**
     * Get size of all files on disk
     * 
     * @param Request $request
     * @return int
     */
    public function getDiskSize(Request $request) {
        $disk = $request->user()->diskConnection();
        $files = $disk->allFiles();
        
        return $this->calculateSize($files, $disk);
    }

    /**
     * Calculate file size
     * 
     * @param array $files
     * @param FilesystemAdapter $disk
     * @return int
     */
    private function calculateSize(array $files, FilesystemAdapter $disk) {
        $size = 0;
        foreach ($files as $file) {
            $size += $disk->size($file);
        }
        return $size;
    }
}