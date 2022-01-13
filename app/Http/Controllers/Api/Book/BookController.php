<?php

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\User;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['Super-Admin', 'admin', 'guest'])) {
            $book = Book::get();
            return response()->json($book);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        if (auth()->user()->hasRole(['Super-Admin', 'admin'])) {
            $data = $request->validated();
            $imageName = time() . '.' . $request->image->extension();
            // dd($imageName);
            $request->image->move('avatars', $imageName);
            $data['image'] = $imageName;
            $book = Book::create($data);

            return response()->json($book);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {
        if (auth()->user()->hasRole('Super-Admin')) {
            $book = Book::find($id);
            $data = $request->validated();

            $imageName = time() . '.' . $request->image->extension();
            // dd($imageName);
            $request->image->move('avatars', $imageName);
            $data['image'] = $imageName;
            $book->update($data);

            return response()->json($book);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (auth()->user()->hasRole(['Super-Admin', 'admin'])) {
            $book = Book::find($id);
            $book->delete();
            return response()->json(['message' => 'delete success']);
        } else {
            return response()->json(['error' => 'you not permission']);
        }
    }
}
