<?php

namespace App\Models\Book;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    protected $fillable = ['book_id','genre_id'];
    public function data(){
        return $this->hasOne(Genre::class,'id','genre_id');
    }
}
