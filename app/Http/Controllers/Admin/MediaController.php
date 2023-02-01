<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Contracts\MediaInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;

class MediaController extends Controller
{
    use HttpResponses;

    protected $interface;

    public function __construct(MediaInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->interface->all($request);
        return $this->success($data, 'Media List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMediaRequest $request)
    {
        $image = $request->file('file');
        $file_name = time() . '_' . $image->getClientOriginalName();

        if (!File::exists(storage_path('app/public/media'))) {
            File::makeDirectory(storage_path('app/public/media'), 0777, true, true);
        }

        if (!File::exists(storage_path('app/public/media/thumbnails'))) {
            File::makeDirectory(storage_path('app/public/media/thumbnails'), 0777, true, true);
        }

        $img = Image::make($image->path());

        $img->resize(110, 110, function ($const) {
            $const->aspectRatio();
        })->save(storage_path('app/public/media/thumbnails').'/'.$file_name);

        $image->move(storage_path('app/public/media'), $file_name);

        $media = Media::create([
            'file' => $file_name
        ]);

        return $this->success(new MediaResource($media), 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return $this->error(null, 'Media not found', 404);
        }

        $data = $this->interface->get($id);
        return $this->success($data, 'Media Detail');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMediaRequest $request, $id)
    {
        $media = Media::find($id);

        if (!$media) {
            return $this->error(null, 'Media not found', 404);
        }

        if ($request->file('file')) {
            Storage::disk('public')->delete('media/' . $media->file);
            Storage::disk('public')->delete('media/thumbnails/' . $media->file);

            $image = $request->file('file');

            $file_name = time() . '_' . $image->getClientOriginalName();

            if (!File::exists(storage_path('app/public/media'))) {
                File::makeDirectory(storage_path('app/public/media'), 0777, true, true);
            }

            if (!File::exists(storage_path('app/public/media/thumbnails'))) {
                File::makeDirectory(storage_path('app/public/media/thumbnails'), 0777, true, true);
            }

            $img = Image::make($image->path());

            $img->resize(110, 110, function ($const) {
                $const->aspectRatio();
            })->save(storage_path('app/public/media/thumbnails').'/'.$file_name);

            $image->move(storage_path('app/public/media'), $file_name);

            $media->file = $file_name;
        }

        $media->title = $request->title ?? $media->title;
        $media->caption = $request->caption ?? $media->caption;
        $media->alt_text = $request->alt_text ?? $media->alt_text;
        $media->description = $request->description ?? $media->description;
        $media->update();


        return $this->success($media, 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Media::find($id);

        if (!$data) {
            return $this->error(null, 'Media not found', 404);
        }
        Storage::disk('public')->delete('media/' . $data->file);
        Storage::disk('public')->delete('media/thumbnails/' . $data->file);

        $data->forceDelete();
        return $this->success(null, 'Successfully delete');
    }
}
