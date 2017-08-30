<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class SearchController extends Controller
{

    /**
     * Поиск по таблице products .
     *
     * @param  Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        // Определим сообщение, которое будет отображаться, если ничего не найдено
        // или поисковая строка пуста
        $error = ['error' => 'No results found, please try with different keywords.'];

        // Удостоверимся, что поисковая строка есть
        if($request->has('b')) {

            // Используем синтаксис Laravel Scout для поиска по таблице products.
            $books = Book::search($request->get('b'))->get();
            foreach($books as $i => $book)
            {
                $books[$i] = [
                    'Author' =>  ($book->author)->person ?? '',
                    'Book'  => $book,
                    'Genre' => ($book->genres)->data ?? '',
                ];
            }
            // Если есть результат есть, вернем его, если нет  - вернем сообщение об ошибке.
            return $books->count() ? $books : $error;

        }

        // Вернем сообщение об ошибке, если нет поискового запроса
        return $error;
    }
    public function deleteBook(Book $book,Request $request){
        $error = ['error' => 'Confirmation is incorrect'];
        // Удостоверимся, что подтверждающая строка есть
        if($request->has('c')) {
            $bookAuthor = $book->author;
            $bookGenre = $book->genres;
            if ($request->get('c') === $book->name) {
                if($bookAuthor)
                    $bookAuthor->delete();
                if($bookGenre)
                    $bookGenre->delete();
                $book->delete();
            }
            return ['successfull' => 'Book was deleted'];
        }
        return $error;
    }
    public function publishBook(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:books',
            'abs'  => 'required|unique:books|digits:9|min:9|max:9',

        ]);
        if($validator->fails())
            return ['error' => $validator->errors()->all()];

        $book = (Book::create(['name' => $request->get('name'),'abs' => $request->get('abs')]))->id;

        $author = (Author::where('name',$request->get('author'))->first())->id;
        $author = Book\BookAuthor::create(['book_id'=>$book,'author_id' => $author]);
        foreach($request->get('genres') as $item)
            Book\BookGenre::create(['book_id' => $book,'genre_id' => (Genre::where('name',$item)->first())->id]);

        return ['success' => 'Book created successfull'];

    }
    public function getAuthors(Request $request){
        return Author::all();
    }
    public function getGenres(Request $request){
        return Genre::all();
    }
    public function createAuthor(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:authors',
            'surname' => 'required',
        ]);
        if($validator->fails())
            return ['error' => $validator->errors()->all()];

        Author::create($request->all());

        return ['success' => 'Author created!','author' => $request->get('name')];
    }
    public function createGenre(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:genres|min:4',
        ]);
        if($validator->fails())
            return ['error' => $validator->errors()->all()];

        Genre::create($request->all());

        return ['success' => 'Genre created!','genre' => $request->get('name')];
    }
}
