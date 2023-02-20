<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;


class FileController extends Controller
{
    public function storeFile($request, string $assignedTo)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '.' . $extension;



            $request->file('upload')->move(public_path('files/'.$assignedTo), $fileName);

            $url = asset('files/'.$assignedTo.'/' . $fileName);

            $fileModel = File::create([
                'name' => $fileName,
                'extension'  => $extension,
                'model' => $assignedTo,
                'location' => public_path('files/'.$assignedTo).'\\'.$fileName,
                'url' => $url,
            ]);

            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
