<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Books;
use App\Models\Admin\Category;
use App\Models\Admin\Author;

class BooksController extends Controller
{
    // Retrieve Data from Books Table
    public function index()
    {
        try {
            return Inertia::render('Admin/Manage-books', [
                'books' => Books::with('category', 'author')->get()->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'book_name' => $book->book_name,
                        'description' => $book->description,
                        'category' => $book->category->category_name ?? 'No category available',
                        'category_id' => $book->category_id,
                        'author' => $book->author->author_name ?? 'No author available',
                        'author_id' => $book->author_id,
                        'book_fees' => $book->book_fees,
                        'quantity' => $book->quantity,
                        'is_active' => $book->is_active,
                        'book_img' => $book->book_img,
                    ];
                }),
                'categories' => Category::all()->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'category_name' => $category->category_name,
                        'is_active' => $category->is_active,
                    ];
                }),
                'authors' => Author::all()->map(function ($author) {
                    return [
                        'id' => $author->id,
                        'author_name' => $author->author_name,
                    ];
                })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
    // Add Book Data
    public function store(Request $request)
    {
        try {
            // Generate Random Integer
            $randomNumber = random_int(100000, 999999);

            Validator::make($request->all(), [
                'book_name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'author_id' => 'required',
                'book_fees' => 'required',
                'quantity' => 'required',
                'is_active' => 'required',
                'book_img' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
            ])->validate();

            // File Upload & Handling
            $destinationPath = 'uploads';
            $image = $randomNumber . '-' . $request->book_img->getClientOriginalName();
            $request->book_img->move(public_path($destinationPath), $image);

            $bookData = [
                'book_name' => $request->book_name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'author_id' => $request->author_id,
                'book_fees' => $request->book_fees,
                'quantity' => $request->quantity,
                'is_active' => $request->is_active,
                'book_img' => $image
            ];

            $book = Books::create($bookData);

            if ($book) {
                return redirect()->back()
                    ->with('message', 'Added book successfully.');
            } else {
                return redirect()->back()
                    ->with('message', 'Failed adding book.');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
    // Update Book Data
    public function update(Request $request, $id)
    {
        try {
            // Generate Random Integer
            $randomNumber = random_int(100000, 999999);

            Validator::make($request->all(), [
                'book_name' => ['required'],
                'description' => ['required'],
                'category_id' => ['required'],
                'author_id' => ['required'],
                'book_fees' => ['required'],
                'quantity' => ['required'],
                'is_active' => ['required'],
            ])->validate();


            // Find ID of a Book
            $books = Books::find($id);
            if ($books) {
                // If User updating without changing the Image
                if ($request->book_img) {
                    // File Upload & Handling
                    $destinationPath = 'uploads';
                    $image = $randomNumber . '-' . $request->book_img->getClientOriginalName();
                    $imageExtension = $request->book_img->getClientOriginalExtension();
                    $request->book_img->move(public_path($destinationPath), $image);
                    if ($imageExtension == 'jpg' || $imageExtension == 'png' || $imageExtension == 'jpeg') {
                        $books->book_name = $request->book_name;
                        $books->description = $request->description;
                        $books->category_id = $request->category_id;
                        $books->author_id = $request->author_id;
                        $books->book_fees = $request->book_fees;
                        $books->quantity = $request->quantity;
                        $books->is_active = $request->is_active;
                        $books->book_img = $image;
                        $books->update();

                        return redirect()->back()
                            ->with('message', 'Updated book successfully.');
                    } else {
                        return redirect()->back()
                            ->with('message', 'Failed updating book.');
                    }
                    // Else: user changed the image
                } else {
                    $books->book_name = $request->book_name;
                    $books->description = $request->description;
                    $books->category_id = $request->category_id;
                    $books->author_id = $request->author_id;
                    $books->book_fees = $request->book_fees;
                    $books->quantity = $request->quantity;
                    $books->is_active = $request->is_active;
                    $books->update();

                    return redirect()->back()
                        ->with('message', 'Updated book successfully.');
                }
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
    // Delete Book Data
    public function destroy($id)
    {
        $books = Books::find($id);

        if ($books) {
            $books->delete();
            return redirect()->back()
                ->with('message', 'Deleted book successfully.');
        } else {
            return redirect()->back()
                ->with('message', 'Book not found.');
        }
    }
}
