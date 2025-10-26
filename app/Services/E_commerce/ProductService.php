<?php

namespace App\Services\E_commerce;

use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Request;

class ProductService
{
    public function getAllProducts(Request $request, $limit = 3)
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
            AllowedFilter::callback('inStock', function ($query, $value) {
                if ($value == 'true' || $value == 1 || $value === true) {
                    $query->where('inStock', 1);
                } elseif ($value == 'false' || $value == 0 || $value === false) {
                    $query->where('inStock', 0);
                }
            }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%$value%"])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%$value%"]);
                    });
                }),
                AllowedFilter::callback('filter_by_category', function ($query, $value) {
                if (is_string($value)) {
                    $value = explode(',', $value);
                }
                $query->whereHas('category', function ($q) use ($value) {
                    $q->where(function ($subQuery) use ($value) {
                        foreach ($value as $val) {
                            $subQuery->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%$val%"])
                                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%$val%"]);
                        }
                    });
                });
            }),

            ])
            ->allowedSorts(['id', 'price'])
            ->with(['category', 'tags'])
            ->paginate($limit);
    }



    public function getProductBySlug($slug)
    {
        // dd($slug);

        $product = Product::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->first();
        // dd($product);
        if (!$product) {
            return null;
        }
        return $product;
    }


}
