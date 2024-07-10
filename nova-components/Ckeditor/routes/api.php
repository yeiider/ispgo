<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::post('/upload', function (Request $request) {
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        $path = $file->store('uploads', 'public');

        return response()->json([
            'url' => Storage::url($path)
        ]);
    }

    return response()->json(['error' => 'No se ha podido cargar la imagen.'], 400);
});
