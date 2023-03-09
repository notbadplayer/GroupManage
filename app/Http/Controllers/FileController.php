<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response as FacadesResponse;

class FileController extends Controller
{
    public function storeFile(Request $request, string $assignedTo)
    {
        Gate::authorize('admin-level');
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '.' . $extension;

            //walidacja:
            if (File::whereName($originName)->first()) {
                if ($assignedTo == 'publication') {
                    return response()->json([
                        'error' => [
                            'message' => 'Nie można dodać pliku. Plik o takiej nazwie już istnieje!'
                        ]
                    ]);
                } else {
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'upload' => 'Plik o takiej nazwie już istnieje na serwerze.'
                    ]);

                    throw $error;
                }
            }

            $fileSize = $request->file('upload')->getSize();
            $fileSize = $fileSize / 1048576;

            if ($fileSize > 10) {
                $message = 'Plik zbyt duży. Maksymalny rozmiar pliku to 10 MB.';
                if ($assignedTo == 'publication') {
                    return response()->json([
                        'error' => [
                            'message' => $message
                        ]
                    ]);
                } else {
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'upload' => $message
                    ]);

                    throw $error;
                }
            }

            $request->file('upload')->move(public_path('files/' . $assignedTo), $fileName);

            $url = url('public/files') .'/'. $assignedTo . '/' . $fileName;

            $fileModel = File::create([
                'name' => $fileName,
                'size' => $fileSize,
                'extension'  => $extension,
                'model' => $assignedTo,
                'location' => 'files/' . $assignedTo . '/' . $fileName,
                'url' => $url,
            ]);


            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url, 'id' => $fileModel->id]);
        }
    }

    public function downloadFile(string $type, string $id)
    {
        $model_prefix = "App\Models";
        $model = $model_prefix . '\\' . $type;

        $model = $model::find($id);
        $path = $model->file->url;

        return response()->download(public_path($model->file->location));


    }

    public function downloadZip(Request $request)
    {
        $category = ($request->category > 0) ? $request->category : null;

        $zip_file = 'nuty.zip'; // Name of our archive to download

        // Initializing PHP class
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if (Gate::allows('admin-level')) {
            $notes = Note::latest()->when($category, function ($query, $category) {
                return $query->where('category_id', $category);
            })->get();
        } else {
            $user_id = Auth::id();
            $groups = auth()->user()->groups;
            $subgroups = auth()->user()->subgroups;

            $notes = Note::where(function ($query) use ($groups, $subgroups, $user_id) {
                $query->where(function ($query) use ($groups) {
                    $query->whereHas('groups', function ($query) use ($groups) {
                        $query->whereIn('id', $groups->pluck('id'));
                    });
                })
                ->orWhere(function ($query) use ($subgroups) {
                    $query->whereHas('subgroups', function ($query) use ($subgroups) {
                        $query->whereIn('id', $subgroups->pluck('id'));
                    });
                })
                ->orWhere(function ($query) use ($user_id) {
                    $query->whereHas('users', function ($query) use ($user_id) {
                        $query->where('id', $user_id);
                    });
                })
                ->orWhere(function ($query) {
                    $query->where('restrictedVisibility', '0');
                });
            })->when($category, function ($query, $category) {
                return $query->where('category_id', $category);
            })->get();

        }

        foreach($notes as $note){
            $zip->addFile(public_path($note->file->location), $note->file->name);

        }

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        // Return the file download response
        return response()->download($zip_file, 'nuty.zip', $headers);
    }
}
