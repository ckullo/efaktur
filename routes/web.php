<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/download/customerFile', function () {
    $filePath = request('filePath');

    // ✅ Ensure the file exists before attempting to download
    if (!Storage::exists($filePath)) {
        abort(404, 'File not found.');
    }

    // ✅ Get the original file name
    $originalFileName = basename($filePath);

    // ✅ Return a streamed response for downloading
    return response()->download(storage_path("app/" . $filePath), $originalFileName);
})->name('download.customerFile');

Route::get('/', function () {
    return view('welcome');
});
