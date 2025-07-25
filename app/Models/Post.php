<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    // protected $fillable = ['title', 'author_id', 'category_id', 'slug', 'body'];
    protected $guarded = ['id'];

    protected $with = ['author', 'category'];

    public function author(): BelongsTo // author() adalah relasinya
    {
        return $this->belongsTo(User::class,);
    }

    public function category(): BelongsTo // category() adalah relasinya
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        });
        $query->when($filters['category'] ?? false, function ($query, $category) {
            return $query->whereHas(
                'category',
                fn(Builder $query) =>
                $query->where('slug', $category)
            );
        });
        $query->when($filters['author'] ?? false, function ($query, $author) {
            return $query->whereHas(
                'author',
                fn(Builder $query) =>
                $query->where('username', $author)
            );
        });
    }
    // ini berfungsi ketika kita insert data dari tinker, namun untuk fillable ditulis data yang boleh diisi seperti contoh dibawah, jika tidak ditulis berarti tidak boleh diisi
    // protected $fillable = ['title', 'author', 'slug', 'body'];

    // ini berfungsi ketika kita insert data dari tinker, namun untuk guarded ditulis data yang tidak boleh diisi contohnya field id
    // protected $guarded = ['id'];

    // public static function find($slug)
    // {
    // tidak menggunakan arrow function
    // return Arr::first(static::all(), function ($post) use ($slug) {
    //     return $post['slug'] == $slug;
    // });

    // menggunakan arrow function
    //     return Arr::first(static::all(), fn($post) => $post['slug'] == $slug) ?? abort(404);
    // }
}
