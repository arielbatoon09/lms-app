<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::all();
        return Inertia::render('admin/book-categories', ['data' => $data]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'category_name' => ['required'],
            'is_active' => ['required'],
        ])->validate();

        Category::create($request->all());

        return redirect()->back() 
            ->with('message', 'Added new category successfully.');
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'category_name' => ['required'],
            'is_active' => ['required'],
        ])->validate();

        if ($request->has('id')) {
            Category::find($request->input('id'))->update($request->all());
            return redirect()->back()
                ->with('message', 'Updated category successfully.');
        }
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Category::find($request->input('id'))->delete();
            return redirect()->back()
                ->with('message', 'Deleted category successfully.');
        }
    }
}
