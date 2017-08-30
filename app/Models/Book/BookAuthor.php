<?php

namespace App\Models\Book;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
    protected $fillable = ['book_id','author_id'];
    public function person(){
        return $this->hasOne(Author::class,'id','author_id');
    }
}
