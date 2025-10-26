<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Events;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EventsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EventsResource\RelationManagers;

class EventsResource extends Resource
{
    protected static ?string $model = Events::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    public static function getModelLabel(): string
    {
        return __('filament/Events/Events.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Events/Events.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title.en')
                ->label(__('filament/Events/Events.title_en'))
                ->required(),
                TextInput::make('title.ar')
                ->label(__('filament/Events/Events.title_ar'))
                ->required(),
                TextInput::make('description.en')
                ->label(__('filament/Events/Events.description_en'))
                ->required(),
                TextInput::make('description.ar')
                ->label(__('filament/Events/Events.description_ar'))
                ->required(),
                TimePicker::make('startDate')
                ->label(__('filament/Events/Events.startDate'))
                ->required(),
                TimePicker::make('endDate')
                ->label(__('filament/Events/Events.endDate'))
                ->required(),
                TextInput::make('location.en')
                ->label(__('filament/Events/Events.location_en'))
                ->required(),
                TextInput::make('location.ar')
                ->label(__('filament/Events/Events.location_ar'))
                ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title.en')
                ->label(__('filament/Events/Events.title_en')),
                TextColumn::make('description.ar')
                ->label(__('filament/Events/Events.description_en')),
                TextColumn::make('startDate')
                ->label(__('filament/Events/Events.startDate')),
                TextColumn::make('endDate')
                ->label(__('filament/Events/Events.endDate')),
                TextColumn::make('location.en')
                ->label(__('filament/Events/Events.location_en')),

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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvents::route('/create'),
            'view' => Pages\ViewEvents::route('/{record}'),
            'edit' => Pages\EditEvents::route('/{record}/edit'),
        ];
    }
}