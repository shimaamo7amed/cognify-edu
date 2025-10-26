<?php

namespace App\Http\Controllers\E_Commerce;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getAllCategory(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        $categories = Category::all()->map(function ($category) use ($locale) {
            return [
                'id'   => $category->id,
                'name' => $category->name[$locale] ?? $category->name['en'],
            ];
        });
        if (!$categories) {
            return apiResponse([], __('message.No categories found'), 404);
        }

        return apiResponse($categories, __('message.Categories retrieved successfully'), 200);
    }
}