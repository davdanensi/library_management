<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'book_name',
        'author',
        'cover_image',
    ];

    public function books()
    {
        return $this->hasMany(BookRenting::class,'id','book_id');
    }
}
