<?php

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Jobs\BookMail;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Lang;
use App\Repositories\BookRepository;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['Super-Admin', 'admin', 'guest'])) {
            $book = $this->bookRepository->paginate(3);
            return response()->json($book);
        } else {
            return response()->json(Lang::get('auth.HTTP_FORBIDDEN'));
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
            $imageName = $this->bookRepository->image($request->image);
            $data['image'] = $imageName;
            $book = $this->bookRepository->create($data);
            return response()->json($book);
        } else {
            return response()->json(['error' => Lang::get('auth.HTTP_FORBIDDEN'),'status' => Response::HTTP_FORBIDDEN]);
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
        $book = $this->bookRepository->find($id);

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
 
            $data = $request->validated();
            $imageName = $this->bookRepository->image($request->image);
            $data['image'] = $imageName;
            $book = $this->bookRepository->update($data, $id);
            return response()->json($book);
        } else {
            return response()->json(['error' => Lang::get('auth.HTTP_FORBIDDEN'), 'status' => Response::HTTP_FORBIDDEN]);
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
            $this->bookRepository->delete($id);
            return response()->json(Lang::get('messages.delete', ['model' => 'Category']), Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'auth.HTTP_FORBIDDEN', 'status' => Response::HTTP_FORBIDDEN]);
        }
    }
}
