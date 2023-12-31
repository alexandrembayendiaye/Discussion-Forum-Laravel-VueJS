<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    //index
    public function tagList(){
        $data = Tag::withCount('question')->orderBy('question_count','DESC')->get();
        return Inertia::render('Admin/Tag/TagList')->with(['data'=>$data]);
    }

    //create tag
    public function createTag(Request $request){
        $request->validate([
            'name' => 'required'
        ]);
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return response()->json([
            'success'=> true,
            'tag'=>$tag
        ]);
    }

    //delete tag
    public function deleteTag($id){
        Tag::where('id',$id)->delete();
        return response()->json(
            ['success'=>true]
        );

    }
}