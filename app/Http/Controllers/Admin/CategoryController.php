<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Admin\Category;
// use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Book-categories', [
            'categories' => Category::all()->map(function($category){
                return [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'is_active' => $category->is_active,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                ];
            })
        ]);
    }
}
