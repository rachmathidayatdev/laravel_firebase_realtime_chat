<?php

namespace App\Http\Controllers;

use App\PostData;
use Illuminate\Http\Request;

class PostDataController extends Controller
{
    public function post_data(Request $request){
        $postData = new PostData;
        $postData->name = $request->name;
        $postData->save();

        return response()->json([
            "id" => $postData->id,
            "name" => $request->name
        ]);
    }

    public function delete_post_data(Request $request){
        $postData = PostData::find($request->id);
        $postData->delete();

        return response()->json([
            "status" => "berhasil"
        ]);
    }
}
