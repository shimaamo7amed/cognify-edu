<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->calculateDiscountedPrice($data);
    }

    protected function calculateDiscountedPrice(array $data): array
    {
        $old = $data['oldPrice'] ?? 0;
        $discount = $data['discountPercentage'] ?? 0;

        if ($old && $discount) {
            $discount = min($discount, 100);
            $discounted = $old - ($old * ($discount / 100));
            $data['price'] = round($discounted, 2);
            $data['sale'] = $discount > 0;
        } else {
            $data['price'] = $old;
            $data['sale'] = false;
        }

        return $data;
    }

}
