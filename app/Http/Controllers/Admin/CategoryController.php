<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Get Category Data
    public function index()
    {
        try{
            return Inertia::render('Admin/Book-categories', [
                'categories' => Category::all()->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'category_name' => $category->category_name,
                        'created_at' => \Carbon\Carbon::parse($category->created_at)->format('d/m/Y - H:i:s'),
                        'updated_at' => \Carbon\Carbon::parse($category->updated_at)->format('d/m/Y - H:i:s'),
                    ];
                })
            ]);
        }catch(\Exception $error){
            return $error->getMessage();
        }
    }

    // Add Category Item
    public function store(Request $request)
    {
        try{
            Validator::make($request->all(), [
                'category_name' => ['required'],
            ])->validate();
    
            Category::create($request->all());
    
            return redirect()->back()
                ->with('message', 'Created category successfully.');
        }catch(\Exception $error){
            return $error->getMessage();
        }
    }

    // Update Category Item
    public function update(Request $request, $id)
    {
        try{
            Validator::make($request->all(), [
                'category_name' => ['required'],
            ])->validate();
            
            $categories = Category::find($id);

            if ($categories) {
                $categories->update($request->all());

                return redirect()->back()
                    ->with('message', 'Updated category successfully.');
            }

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }

    // Delete Category Item
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
        
            // Update the category_id of all books with this category to 0
            $category->books()->update(['category_id' => 0]);
        
            $category->delete();
        
            return redirect()->back()
                ->with('message', 'Category deleted successfully.');
        } catch (\Exception $error) {
            return $error->getMessage();
        }

    }
}
