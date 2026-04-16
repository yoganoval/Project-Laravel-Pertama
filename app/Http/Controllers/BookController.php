<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:view books')->only('index');
        $this->middleware('permission:create books')->only('create','store');
        $this->middleware('permission:edit books')->only('edit','update');
        $this->middleware('permission:delete books')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Books/Index', [
            'books' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        return Inertia::render('Books/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'nullable|integer',
            'isbn' => 'nullable',
            'stok' => 'required|integer'
        ]);

        Book::create($data);

        return redirect()->route('books.index');
    }

    public function edit(Book $book)
    {
        return Inertia::render('Books/Edit', [
            'book' => $book
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'nullable|integer',
            'isbn' => 'nullable',
            'stok' => 'required|integer'
        ]);

        $book->update($data);

        return redirect()->route('books.index');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back();
    }
}