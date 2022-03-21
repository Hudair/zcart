<?php

namespace App\Http\Controllers;

use App\Image;
use League\Glide\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{

	private $disk;

    /**
     * The constructor.
     *
     * @param Request        $request
     */
    public function __construct(Request $request)
    {
        $this->disk = Storage::disk(config('filesystems.default'));
    }

    // public function getImages()
    // {
    //     return view('images')->with('images', auth()->user()->images);
    // }

    public function show(Request $request, Server $server, $path)
	{
		$this->setConfigs($request);

        return $server->getImageResponse($path, $request->all());
	}

	/**
	 * upload Image via ajax. the associated model id and other information will be provided in the request
	 *
	 * @param  Request $request
	 *
	 * @return json
	 */
	public function upload(Request $request)
	{
	    $fileBlob = 'fileBlob';		// the parameter name that stores the file blob
	    if ($request->hasFile($fileBlob)) {
	        if (! $request->has('model_id') || ! $request->has('model_name')) {
	            return Response::json(['error' => trans('responses.model_not_defined')]);
	        }

	       	// Uploaded file info
			$rawFile = $request->file($fileBlob);
	        $file = $rawFile->getPathName();
	        $realName = $rawFile->getClientOriginalName();
	        $fileExtension = $rawFile->getClientOriginalExtension();
	        $fileSize = $request->input('fileSize') ?? $rawFile->getClientSize();

			// Linked model info
        	$model_name = $request->input('model_name');
        	$model_id = $request->input('model_id');

			// Chunk info
	        // $fileId = $request->input('fileId');         //  the file identifier
	        $index =  $request->input('chunkIndex');        // the current file chunk index
	        $totalChunks = $request->input('chunkCount');   // the total number of chunks for this file

	        // Prepare system info
        	$allChunksUploaded = False;
		    $targetDir = image_storage_dir();
		    $tempDir = temp_storage_dir("$model_name/$model_id");
	        $uniqFileName = uniqid() . '.' . $fileExtension;
	        $targetFile = $targetDir . '/' . $uniqFileName;  			// The target file path

	        if ($totalChunks > 1) {	// create chunk files only if chunks are greater than 1
				if (! file_exists($tempDir)) {	// Make the temp directory if not exist
					mkdir($tempDir, 0777, true);
				}

	            $chunkFile = $tempDir . "chunk_" . str_pad($index, 4, '0', STR_PAD_LEFT);

		        if (move_uploaded_file($file, $chunkFile)) {
		            $chunks = glob($tempDir . "chunk_*");	// get list of all chunks uploaded so far to server
		            $allChunksUploaded = count($chunks) == $totalChunks;	// all chunks were uploaded

		            if (! $allChunksUploaded) { // Return to procceed if all chunks are not uploaded yet
			            return Response::json(['chunkIndex' => $index]);
					}

		            // All chunks are uploaded, combines all file chunks to one file
	                $file = $tempDir.$uniqFileName;
	                $this->combineChunks($chunks, $file);
	            }
			}

        	if ($totalChunks == 1 || ($totalChunks > 1 && $allChunksUploaded)) {
		        if ($this->disk->put($targetFile, file_get_contents($file))) {
					if (is_dir($tempDir)) {	// Delete the temp directory if exist
				        File::deleteDirectory($tempDir);
					}

		        	$model = get_qualified_model($model_name);
		        	$attachable = (new $model)->find($model_id);

					$data = [
			            'path' => $targetFile,
			            'name' => $realName,
			            'extension' => $fileExtension,
			            'size' => $fileSize,
			        ];

			        // Success
					if ($attachable->images()->create($data)) {
			            return Response::json(['chunkIndex' => $index]);
					}
		        }

	            return Response::json(['error' => trans('responses.error_uploading_file') . ' ' . $realName]);
			}

		    return Response::json(['error' => trans('responses.no_file_was_uploaded')]);
	    }

        // $request->session()->flash('global_msg', trans('messages.img_upload_failed'));

	    return Response::json(['error' => trans('responses.no_file_was_uploaded')]);
	}

	/**
	 * download Image file
	 *
	 * @param  Request    $request
	 * @param  Image $image
	 *
	 * @return file
	 */
	public function download(Request $request, Image $image)
	{
	    if (Storage::exists($image->path)) {
	        return Storage::download($image->path, $image->name);
	    }

		return back()->with('error', trans('messages.file_not_exist'));
	}

	/**
	 * delete Image via ajax request
	 *
	 * @param  Request    $request
	 * @param  Image $image
	 *
	 * @return json
	 */
	public function delete(Request $request, Image $image)
	{
    	$image->delete();

	    if (Storage::exists($image->path)) {
	        if (Storage::delete($image->path)) {
				Storage::deleteDirectory(image_cache_path($image->path));
				return Response::json(['success' => trans('response.success')]);
	        }

			return Response::json(['error' => trans('response.error')]);
	    }

		return Response::json(['error' => trans('messages.file_not_exist')]);
	}

	/**
	 * sort images order via ajax.
	 *
	 * @param  Request $request
	 *
	 * @return json
	 */
	public function sort(Request $request)
	{
        $order = $request->all();
        $images = Image::find(array_keys($order));

        foreach ($images as $image) {
        	$image->update([ 'order' => $order[$image->id] ]);
        }

		return Response::json(['success' => trans('response.success')]);
	}

	/**
	 * Set Config settings for the image manupulation
	 *
	 * @param Request $request [description]
	 */
	private function setConfigs(Request $request)
	{
		if (config('image.background_color')) {
			$request->merge(['bg' => config('image.background_color')]);
		}

		return $request;
	}

	// combine all chunks
	private function combineChunks($chunks, $targetFile)
	{
	    $handle = fopen($targetFile, 'a+');				// open target file handle

	    foreach ($chunks as $chunk) {
	        fwrite($handle, file_get_contents($chunk));
	    }

	    fclose($handle);								// close the file handle
	}
}