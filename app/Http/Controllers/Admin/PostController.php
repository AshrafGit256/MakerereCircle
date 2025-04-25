<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PostModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function list(Request $request)
    {
        $query = PostModel::query();
        $query->where('is_delete', '=', 0);

        // Filterable fields from post table
        if (!empty($request->id)) {
            $query->where('id', $request->id);
        }

        if (!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if (!empty($request->location)) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if (!empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if (!empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $data['getRecord'] = $query->orderBy('id', 'desc')->get();
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

        $post->status = $request->status ?? 0;
        $post->user_id = Auth::id();

        if (!empty($request->file('image_name'))) {
            $file = $request->file('image_name');
            $ext = $file->getClientOriginalExtension();
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->storeAs('public/media', $filename);
            $post->image_name = 'storage/media/' . $filename;

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
        $post = PostModel::getSingle($id);
        $post->description = trim($request->description);
        $post->location = trim($request->location);
        $post->status = $request->status ?? 0;

        $post->hide_like_view = $request->has('hide_like_view') ? 1 : 0;
        $post->allow_commenting = $request->has('allow_commenting') ? 1 : 0;
        $post->lost = $request->has('lost') ? 1 : 0;
        $post->found = $request->has('found') ? 1 : 0;

        if (!empty($request->file('image_name'))) {
            $file = $request->file('image_name');
            $ext = $file->getClientOriginalExtension();
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->storeAs('public/media', $filename);
            $post->image_name = 'storage/media/' . $filename;

            $post->image_name = trim($filename);
        }

        $post->save();

        return redirect('admin/post/list')->with('success', "Post Successfully Updated");
    }

    public function delete($id)
    {
        $post = PostModel::getSingle($id);
        $post->is_delete = 1;
        $post->save();

        return redirect()->back()->with('success', "Post Successfully Deleted");
    }
}
