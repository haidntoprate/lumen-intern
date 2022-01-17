<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;

use App\Repositories\BookRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class BookRepositoryEloquent extends BaseRepository implements BookRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Book::class;
    }
    
    public function boot()
    {

    }

}
