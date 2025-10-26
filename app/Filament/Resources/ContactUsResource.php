<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ContactUs;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactUsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactUsResource\RelationManagers;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/Forms/Partners.group');
    }
    public static function getModelLabel(): string
    {
        return __('filament/Forms/ContactUS.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Forms/ContactUS.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label(__('filament/Forms/ContactUS.fullName')),
                TextInput::make('email')
                ->label(__('filament/Forms/ContactUS.email')),
                TextInput::make('phoneNumber')
                ->label(__('filament/Forms/ContactUS.phone')),
                Textarea::make('message')
                ->label(__('filament/Forms/ContactUS.message')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('filament/Forms/ContactUS.fullName')),
                TextColumn::make('email')
                ->label(__('filament/Forms/ContactUS.email')),
                TextColumn::make('phoneNumber')
                ->label(__('filament/Forms/ContactUS.phone')),
                TextColumn::make('message')
                ->label(__('filament/Forms/ContactUS.message')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListContactUs::route('/'),
            'view' => Pages\ViewContactUs::route('/{record}'),
        ];
    }
}