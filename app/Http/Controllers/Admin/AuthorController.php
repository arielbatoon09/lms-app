<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Admin\Author;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    // Get Author Data
    public function index()
    {
        try{
            return Inertia::render('Admin/Book-authors', [
                'authors' => Author::all()->map(function ($author) {
                    return [
                        'id' => $author->id,
                        'author_name' => $author->author_name,
                        'created_at' => \Carbon\Carbon::parse($author->created_at)->format('d/m/Y - H:i:s'),
                        'updated_at' => \Carbon\Carbon::parse($author->updated_at)->format('d/m/Y - H:i:s'),
                    ];
                })
            ]);

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
    // Add Author Item
    public function store(Request $request)
    {
        try{
            Validator::make($request->all(), [
                'author_name' => ['required'],
            ])->validate();
    
            Author::create($request->all());
    
            return redirect()->back()
                ->with('message', 'Created author successfully.');
        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
    // Update Author Item
    public function update(Request $request, $id)
    {
        try{
            Validator::make($request->all(), [
                'author_name' => ['required'],
            ])->validate();
            
            $authors = Author::find($id);

            if ($authors) {
                $authors->update($request->all());

                return redirect()->back()
                    ->with('message', 'Updated author successfully.');
            }

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
    // Delete Author Item
    public function destroy($id)
    {
        try {
            $author = Author::findOrFail($id);
        
            // Update the author_id of all books with this category to 0
            $author->books()->update(['author_id' => 0]);
        
            $author->delete();
        
            return redirect()->back()
                ->with('message', 'Author deleted successfully.');
        } catch (\Exception $error) {
            return $error->getMessage();
        }

    }            
}
