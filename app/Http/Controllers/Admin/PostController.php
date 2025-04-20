<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PostModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function list()
    {
        $data['getRecord'] = PostModel::getRecord();
        $data['header_title'] = 'Posts';
        return view('admin.post.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Post';
        return view('admin.post.add', $data);
    }

    public function insert(Request $request)
    {
        $post = new PostModel;
        $post->description = trim($request->description);
        $post->location = trim($request->location);

        // Handle checkbox values safely
        $post->allow_commenting = $request->has('allow_commenting') ? 1 : 0;
        $post->hide_like_view = $request->has('hide_like_view') ? 1 : 0;
        $post->lost = $request->has('lost') ? 1 : 0;
        $post->found = $request->has('found') ? 1 : 0;

        // Set default status if not passed
        $post->status = $request->status ?? 0;
        $post->user_id = Auth::id();

        // Handle image upload
        if (!empty($request->file('image_name'))) {
            $file = $request->file('image_name');
            $ext = $file->getClientOriginalExtension();
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move(public_path('upload/post/'), $filename);
            $post->image_name = trim($filename);
        }

        $post->save();

        return redirect('admin/post/list')->with('success', "Post Successfully created");
    }

    public function edit($id)
    {
        $data['getRecord'] = PostModel::getSingle($id);
        $data['header_title'] = 'Edit Post';
        return view('admin.post.edit', $data);
    }

    public function update($id, Request $request)
    {

        $post = PostModel::getSingle($id); // Ensure getSingle method exists in your model
        $post->description = trim($request->description);
        $post->location = trim($request->location);
        $post->status = $request->status ?? 0; // Set a default value (e.g., 0) if status is empty

        $post->hide_like_view = !empty($request->hide_like_view) ? 1 : 0;
        $post->allow_commenting = !empty($request->allow_commenting) ? 1 : 0;

        $post->lost = !empty($request->lost) ? 1 : 0;
        $post->found = !empty($request->found) ? 1 : 0;

        if(!empty($request->file('image_name')))
        {
            $file = $request->file('image_name');
            $ext = $file->getClientOriginalExtension();
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move(public_path('upload/post/'), $filename);
            $post->image_name = trim($filename);
        }

        $post->save();

        return redirect('admin/post/list')->with('success', "Post Successfully Updated"); 
    }

    public function delete($id)
    {
        $post = PostModel::getSingle($id);
        $post->is_delete =1;
        $post->save();

        return redirect()->back()->with('success', "Post Successfully Deleted"); 
    }

}
