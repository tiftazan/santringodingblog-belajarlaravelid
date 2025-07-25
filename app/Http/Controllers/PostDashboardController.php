<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->where('author_id', Auth::user()->id);

        if (request('keyword')) {
            $posts->where('title', 'like', '%' . request('keyword') . '%');
        }
        return view('dashboard.index', ['posts' => $posts->paginate(7)->withQueryString()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi inputan user, pesan errornya default
        // $request->validate([
        //     'title' => 'required|unique:posts|min:4|max:255',
        //     'category_id' => 'required',
        //     'body' => 'required'
        // ]);

        // validasi inputan user, pesan errornya bisa custom
        Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:4|max:255',
            'category_id' => 'required',
            'body' => 'required|min:20'
        ], [
            'title.required' => 'Field :attribute harus diisi!',
            'category_id.required' => 'Pilih salah satu :attribute',
            'body.required' => ':attribute gak boleh kosong!',
            'body.min' => ':attribute harus :min karakter atau lebih! '
        ], [
            'title' => 'judul',
            'category_id' => 'kategori',
            'body' => 'tulisan'

        ])->validate();
        Post::create([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        // ..redirect ke halaman dashboard ketika sukses tambah data
        return redirect('/dashboard')->with(['success' => 'Your post has been saved!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('dashboard.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //Validation
        $request->validate([
            'title' => 'required|min:4|max:255|unique:posts, title' . $post->id,
            'category_id' => 'required',
            'body' => 'required'
        ]);

        // Update post
        $post->update([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        // Redirect
        return redirect('/dashboard')->with(['success' => 'Your post has been updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/dashboard')->with(['success' => 'Your post has been removed!']);
    }
}
