<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceResource\RelationManagers;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament/Services/Services.group');
    }
    public static function getModelLabel(): string
    {
        return __('filament/Services/Services.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Services/Services.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title.en')
                ->label(__('filament/Services/Services.title_en'))
                ->required(),
                TextInput::make('title.ar')
                ->label(__('filament/Services/Services.title_ar'))
                ->required(),
                TextInput::make('description.en')
                ->label(__('filament/Services/Services.description_en')),
                TextInput::make('description.ar')
                ->label(__('filament/Services/Services.description_ar')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title.en')
                ->label(__('filament/Services/Services.title_en')),
            
                // TextColumn::make('description.en')
                // ->label(__('filament/Services/Services.description_en')),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
