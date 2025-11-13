<?php
namespace App\Http\Controllers\Backend\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    ## Private Storage
    public function viewFile($filepath)
    {
        $disk = 'local';
        $path = decrypt($filepath);
        $fp = explode('/', $path);
        $filename = array_pop($fp);

        if (!Storage::disk($disk)->exists($path)) {
            abort(404, "File not found");
        }

        // Ambil isi file
        $file = Storage::disk($disk)->get($path);

        // Ambil mime type
        // $mime = Storage::disk($disk)->mimeType($path);
        $mime = mime_content_type(Storage::disk($disk)->path($path));

        // Return response inline (dibuka di tab baru)
        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
