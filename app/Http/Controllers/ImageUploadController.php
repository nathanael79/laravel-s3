<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function image_upload()
    {
        return view('image-upload');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload_post_image(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $imageName=time().$file->getClientOriginalName();
            $filePath = 'images/' . $imageName;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            $url = 'https://'. env('AWS_BUCKET') .'.s3-'. env('AWS_DEFAULT_REGION') .'.amazonaws.com/images/'.$imageName;
            return back()->with('success', $url);
        }
    }
}
