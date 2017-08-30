<?php

namespace App\Models;

use App\Models\Book\BookAuthor;
use App\Models\Book\BookGenre;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use Searchable;
    protected $fillable = ['name','abs'];
    public function author(){
        return $this->hasOne(BookAuthor::class);
    }
    public function genres(){
        return $this->hasOne(BookGenre::class);
    }
}
