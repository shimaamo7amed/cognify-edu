<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceItemResource\Pages;
use App\Filament\Resources\ServiceItemResource\RelationManagers;

class ServiceItemResource extends Resource
{
    protected static ?string $model = ServiceItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    public static function getNavigationGroup(): ?string
    {
        return __('filament/Services/Services.group');
    }
    public static function getModelLabel(): string
    {
        return __('filament/Services/ServicesItem.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Services/ServicesItem.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title.en')
                ->label(__('filament/Services/ServicesItem.title_en'))
                ->required(),
                TextInput::make('title.ar')
                ->label(__('filament/Services/ServicesItem.title_ar'))
                ->required(),
                TextInput::make('description.en')
                ->label(__('filament/Services/ServicesItem.description_en')),
                TextInput::make('description.ar')
                ->label(__('filament/Services/ServicesItem.description_ar')),
                Select::make('service_id')
                ->label(__('filament/Services/ServicesItem.service'))
                ->required()
                ->relationship('service', 'title')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->title['en'] ?? ''),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('service.title.en')
                ->label(__('filament/Services/ServicesItem.service')),
                TextColumn::make('title.en')
                ->label(__('filament/Services/ServicesItem.title_en'))
                ->searchable(),
                // TextColumn::make('description.en')
                // ->label(__('filament/Services/ServicesItem.description_en')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceItems::route('/'),
            'create' => Pages\CreateServiceItem::route('/create'),
            'view' => Pages\ViewServiceItem::route('/{record}'),
            'edit' => Pages\EditServiceItem::route('/{record}/edit'),
        ];
    }
}