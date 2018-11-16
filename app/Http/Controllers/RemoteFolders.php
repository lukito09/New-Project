<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

function directoryParse ($relativeDirectory, $dir) {
    // $dir is the new request directory from user
    // Get the old relative directory from user
    $remoteDirectory = $relativeDirectory;

    // To store the new relative directory
    $newDir = '';

    // Check if the last character is a '/'
    // Then remove the '/'
    if (substr($remoteDirectory, -1) == '/') {
        $remoteDirectory = rtrim($remoteDirectory, '/');
    }

    // Split the relative directory to list of directories
    $directories = explode('/', $remoteDirectory);

    // Get the list count of the directory list
    $directorySize = sizeof($directories);
    
    // Check if the new directory is '..' (up directory)
    // If the new directory is '..', pop the last element in
    // the directory list
    if ($dir == '..') { 
        $directorySize -= 1;
        
        // If there is no more element in the list, return
        // empty directory
        if ($directorySize < 0) { 
            return ''; 
        } 
        
        array_pop($directories);
    } else { 
        // If the new directory is a directory name, add to the list of directories
        array_push($directories, $dir); 
        $directorySize += 1; 
    } 

    // Create new relative directory string from the list of directory
    for ($i = 0; $i < $directorySize; $i++) { 
        $newDir .= $directories[$i] . '/'; 
    } 
    
    return substr($newDir,1); 
}

class RemoteFolders extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createDirectory (Request $request) {
        // Construct S3 URL
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        
        // Get location to create new directory in
        $folder = UserRemoteFolder::getFolder($request);

        // Check if the new name exist as a file
        $files = Storage::disk('s3')->files($folder);
        $nameExist = false;
        foreach ($files as $file) {
            if ($file == $request->folderName) {
                $nameExist = true;

                // TO-DO: Show error message
                return View('createFolder');
            }
        }

        // Create the new directory in S3
        Storage::disk('s3')->makeDirectory($folder . str_replace('/','',$request->folderName));

        // TO-DO: Show success message
        return View('createFolder');
    }

    public function index () {
        return View('createFolder');
    }

    public function changeDirectory (Request $request) {
        // User's current relative directory
        $remoteDirectory = $request->session()->get('requestDirectory');

        // Get new directory from user
        $changeDir = $request->dir;

        // Create new relative directory
        $newDir = directoryParse($remoteDirectory, $changeDir);

        // Store new relative directory in session
        $request->session()->put('requestDirectory',$newDir);

        // Redirect user back to home
        return redirect('/');
    }
}
