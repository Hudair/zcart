<?php

namespace App\Http\Controllers\Api;

use App\Attachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class AttachmentController extends Controller
{
	/**
	 * download attachment file
	 *
	 * @param  Request    $request
	 * @param  Attachment $attachment
	 *
	 * @return file
	 */
	public function download(Request $request, Attachment $attachment)
	{
	    if (Storage::exists($attachment->path))
	        return Storage::download($attachment->path, $attachment->name);

        return response()->json(['message' => trans('messages.file_not_exist')], 404);
	}

}
