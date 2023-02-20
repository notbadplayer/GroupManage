<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;


class FileController extends Controller
{
    public function storeFile(Request $request, string $assignedTo)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '.' . $extension;


            if ( File::whereName($originName)->first() ) {
                if($assignedTo == 'publication'){
                    return response()->json([
                        'error' => [
                            'message' => 'Nie można dodać pliku. Plik o takiej nazwie już istnieje!'
                        ]
                    ]);
                }else {
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'upload' => 'Plik o takiej nazwie już istnieje na serwerze.'
                   ]);

                   throw $error;
                }
            }

            $request->file('upload')->move(public_path('files/'.$assignedTo), $fileName);

            $url = asset('files/'.$assignedTo.'/' . $fileName);

            $fileModel = File::create([
                'name' => $fileName,
                'extension'  => $extension,
                'model' => $assignedTo,
                'location' => public_path('files/'.$assignedTo).'\\'.$fileName,
                'url' => $url,
            ]);


            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url,'id' => $fileModel->id]);
        }
    }
}
