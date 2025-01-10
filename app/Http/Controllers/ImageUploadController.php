<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {

        $uploadedFiles = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                dd($uploadedFiles);
                // Generate unique filename
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Store the file
                $path = $image->storeAs('uploads', $filename, 'public');
                
                // Create database record
                $galleryImage = GalleryImage::create([
                    'filename' => $filename,
                    'file_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize()
                ]);
                
                $uploadedFiles[] = [
                    'id' => $galleryImage->id,
                    'filename' => $filename,
                    'url' => $galleryImage->full_url
                ];
            }
            
            return response()->json([
                'message' => 'Images uploaded successfully',
                'files' => $uploadedFiles
            ], 200);
        }

        return response()->json([
            'message' => 'No images were uploaded'
        ], 400);
    }

    public function index()
    {
        $images = GalleryImage::latest()->get();
        return response()->json([
            'images' => $images->map(function($image) {
                return [
                    'id' => $image->id,
                    'filename' => $image->filename,
                    'url' => $image->full_url,
                    'created_at' => $image->created_at
                ];
            })
        ]);
    }

    public function destroy($id)
    {
        $image = GalleryImage::findOrFail($id);
        
        // Delete file from storage
        Storage::disk('public')->delete($image->file_path);
        
        // Delete database record
        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
        ]);
    }
}