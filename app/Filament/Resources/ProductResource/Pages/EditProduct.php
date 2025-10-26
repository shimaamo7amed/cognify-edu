<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeUpdate(array $data): array
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
