<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BookRenting extends Model
{
    use HasFactory;

    protected $table = 'book_renting';

    protected $fillable = [
        'user_id',
        'book_id',
        'return_date',
    ];

    public function books()
    {
        return $this->belongsTo(Book::class,'book_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
