<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
// use App\Helpers\UserFolder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        $folder = UserRemoteFolder::getFolder($request);

        $images = [];

        // Get all folder in the current directory
        $directories = Storage::disk('s3')->directories($folder);
        foreach ($directories as $directory) {
            $images[] = [
                'name' => str_replace($folder, '', $directory),
                'src' => ''
            ];
        }

        // die($folder);

        // Get all files in the current directory 
       $files = Storage::disk('s3')->files($folder);
           foreach ($files as $file) {
               $images[] = [
                   'name' => str_replace($folder, '', $file),
                   'src' => $url . $file
                   
               ];
           }
    //    return view('viewfile');
        return view('home', [
            'images' => $images,
            'folderName' => $folder
        ]);
    }
}
