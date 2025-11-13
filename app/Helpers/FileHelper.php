<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FileHelper
{
    /**
     * Simpan file ke storage private/public dengan nama custom + tanggal
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param string $prefix
     * @param string $disk
     * @return string path file
     */
    public static function saveFile($file, $folder, $prefix = 'file', $disk = 'local')
    {
        // Buat slug prefix biar rapi
        $safePrefix = Str::slug($prefix, '_');

        // Ambil ekstensi asli file
        $ext = $file->getClientOriginalExtension();

        // Nama file unik: prefix_tanggaljam.ext
        $filename = $safePrefix . '_' . Carbon::now()->format('Ymd_His') . '.' . $ext;

        // Simpan file
        $path = $file->storeAs($folder, $filename, $disk);

        return [$path, $filename];
    }

    public static function saveSignBase64($base64, $folder='private/signatures/', $disk='local')
    {

        // Convert base64 to image
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);

        $fileName = 'signature_' . Carbon::now()->format('Ymd_His') . '.png';
        $filePath = $folder.$fileName;

        Storage::disk($disk)->put($filePath, base64_decode($image));

        return $filePath;
    }
}
