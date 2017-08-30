<?php

use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Book\BookAuthor::class, 50)->create();
        factory(App\Models\Book\BookGenre::class, 50)->create();
    }
}
