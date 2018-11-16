<?php
namespace App\Http\Controllers;
use Auth;

class UserRemoteFolder {
    public static function getFolder ($request) {
        // Get user's relative directory from the base directory
        $directory = $request->session()->get('requestDirectory');
        
        // Get default directory
        $baseDirectory = env('DEFAULT_UPLOAD_DIRECTORY');
    
        // If user does not have all access, set the user's base directory
        // based on their group
        $groupDirectory = '';
        if (Auth::user()->hasAllAccess == false) {
            $groupDirectory = Auth::user()->group . '/' . $directory;
        }

        // The new combined directory
        $finalDirectory = $baseDirectory . '/' . $groupDirectory;
    
        // If the last character is not a '/', append the '/' character
        if (substr($finalDirectory, -1) != '/') {
            $finalDirectory .= '/';
        }

        return $finalDirectory;
    }
}

?>