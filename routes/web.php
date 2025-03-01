<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/download/customerFile', function () {
    // ✅ Get the file path from the request
    $filePath = request('filePath');
    // ✅ Replace dashes with slashes in the file path
    $relativePath = str_replace('-', '/', $filePath);

    // ✅ Construct the full path within the private storage
    $storagePath = storage_path('app/private/' . $relativePath);

    // ✅ Ensure the file exists before attempting to download
    if (!file_exists($storagePath)) {
        abort(404, 'File not found at: ' . $storagePath);
    }

    // ✅ Return a streamed response for downloading
    return response()->download($storagePath);
})->name('download.customerFile');

Route::get('/', function () {
    return view('welcome');
});
