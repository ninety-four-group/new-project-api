<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\MediaInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Models\Media;
use Image;

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

        $img = Image::make($image->path());

        $img->resize(110, 110, function ($const) {
            $const->aspectRatio();
        })->save(storage_path('app/public/media/thumbnails').'/'.$file_name);

        $image->move(storage_path('app/public/media'), $file_name);

        $media = Media::create([
            'file' => $file_name
        ]);

        return $this->success($media, 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
    public function update(Request $request, $id)
    {

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

        $data->delete();
        return $this->success(null, 'Successfully delete');
    }
}
