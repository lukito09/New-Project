<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class UploadController extends Controller
{
   public function uploadFile()
   {
       return view('upload');
   }
   public function viewFile()
   {
       $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
       $folder = UserRemoteFolder::getFolder($request);

       $images = [];
       $files = Storage::disk('s3')->files($folder);
           foreach ($files as $file) {
               $images[] = [
                   'name' => str_replace($folder, '', $file),
                   'src' => $url . $file
               ];
           }
       return view('viewfile', compact('images'));
   }
   public function store(Request $request)
   {
    $folder = UserRemoteFolder::getFolder($request);
       $this->validate($request, [
           'image' => 'required|max:10000'
       ]);
       if ($request->hasFile('image')) {
           $file = $request->file('image');
           $name = time() . '_' . $file->getClientOriginalName();
           $filePath = $folder . $name;

            $directoryWithNameFound = false;
            foreach (Storage::disk('s3')->directories($folder) as $directory) {
                if ($directory == $filePath) {
                    $directoryWithNameFound = true;
                    break;
                }
            }
           
            if (!$directoryWithNameFound) {
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                return back()->withSuccess('File uploaded successfully');
            }
       }
       return back()->withSuccess('An existing directory with the same name exist. Please rename file.');
   }
   public function destroy(Request $request, $image)
   {
    $folder = UserRemoteFolder::getFolder($request);

    $fileOrFolderExist = Storage::disk('s3')->exists($folder . $image);
    if ($fileOrFolderExist == false) {
        return back()->withSuccess('File or folder not found');
    }

    // Check if the current item exist in list of folders. If not, it is a file
    $isDirectory = false;
    foreach (Storage::disk('s3')->directories($folder) as $directory) {
        if ($directory == $folder . $image) {
            $isDirectory = true;
            break;
        }
    }

        if (!$isDirectory) {
            Storage::disk('s3')->delete($folder . $image);
            return back()->withSuccess('File was deleted successfully');
        } else {
            Storage::disk('s3')->deleteDirectory($folder . $image);
            return back()->withSuccess('Folder was deleted successfully');
        }
   }
}