<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\CreateFolderRequest;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * List of folders
     * @param Request $request
     * @return JsonResponse 
     */
    public function index(Request $request) { 
        $directories = $request->user()->diskConnection()->directories();   
        return response()->json($directories);
    }

    /**
     * Create folder
     * @param CreateFolderRequest $request
     * @return JsonResponse 
     */
    public function create(CreateFolderRequest $request) {
        $name = $request->input('name');
        $request->user()->diskConnection()->makeDirectory($name);
        return 'Folder '.$name.' created succesfull!';
    }
}
