<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use Illuminate\Http\Request;
use App\Http\Requests\File\RenameFileRequest;
use App\Http\Requests\File\DeleteFileRequest;
use Facades\App\Services\FileService;
use App\Http\Requests\File\DownloadFileRequest;
use App\Http\Requests\File\SizeFileRequest;
use App\Models\File;
use Carbon\Carbon;

class FileController extends Controller
{
    /**
     * List of files
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) {
        $files = $request->user()->diskConnection()->files($request->folder);   
        return response()->json($files);
    }

    /**
     * Upload file
     * 
     * @param UploadFileRequest $request
     * @return string
     */
    public function upload(UploadFileRequest $request) {
        $disk = $request->user()->diskConnection();
        $filename = $request->file('userfile')->getClientOriginalName();
        $disk->putFileAs($request->folder, $request->file('userfile'), $filename);
        $lifetime = isset($request->lifetime) ? Carbon::now()->addMinutes($request->lifetime) : null;
        $request->user()->files()->create([
            'filename' => $filename,
            'path' => $request->folder.'/'.$filename,
            'size' => $request->file('userfile')->getSize(),
            'lifetime' => $lifetime
        ]);
        return 'File '.$filename.' uploaded successful!';
    }

    /**
     * Rename file
     * 
     * @param RenameFileRequest $request
     * @return string
     */
    public function rename(RenameFileRequest $request) {
        $disk = $request->user()->diskConnection();
        $disk->move($request->old_name, $request->new_name);
        $request->user()->files()
            ->where('path', $request->folder.'/'.$request->file)
            ->first()
            ->update([
                'filename' => $request->file,
                'path' => $request->folder.'/'.$request->file
            ]);
        return 'File has been renamed!';
    }

    /**
     * Delete file
     * 
     * @param DeleteFileRequest $request
     * @return string
     */
    public function delete(DeleteFileRequest $request) {
        $disk = $request->user()->diskConnection();
        $disk->delete($request->filename);
        $request->user()->files()
            ->where('path', $request->folder.'/'.$request->file)
            ->delete();
        return 'File '.$request->filename.' has been deleted!';
    }

    /**
     * Download file
     * 
     * @param DownloadFileRequest $request
     * @return File
     */
    public function download(DownloadFileRequest $request) {
        $disk = $request->user()->diskConnection();
        return $disk->download($request->folder.'/'.$request->file);
    }

    /**
     * Size of files in directory
     * 
     * @param SizeFileRequest $request
     * @return string
     */
    public function size(SizeFileRequest $request) {
        return FileService::getFilesSize($request);
    }

    /**
     * Size of disk
     * 
     * @param Request $request
     * @return string
     */
    public function diskSize(Request $request) {
        return FileService::getDiskSize($request);
    }

    /**
     * Get public link for download
     * 
     * @param DownloadFileRequest $request
     * @return string
     */
    public function publicLink(DownloadFileRequest $request) {
        $hash = md5(microtime());
        $request->user()->files()
            ->where('path', $request->folder.'/'.$request->file)
            ->first()
            ->update(['public_link' => $hash]);
        return route('download.link', ['publiclink' => $hash]);
    }

    /**
     * Download file with public link
     * 
     * @param Request $request
     * @return string
     */
    public function downloadPublic(Request $request) {
        $file = File::where('public_link', $request->publiclink)->first();
        $user = $file->user;
        $disk = $user->diskConnection();
        return $disk->download($file->path);
    }
}
