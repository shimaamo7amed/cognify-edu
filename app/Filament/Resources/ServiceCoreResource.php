<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceCore;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceCoreResource\Pages;
use App\Filament\Resources\ServiceCoreResource\RelationManagers;

class ServiceCoreResource extends Resource
{
    protected static ?string $model = ServiceCore::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function getModelLabel(): string
    {
        return __('filament/Services/ServiceCore.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Services/ServiceCore.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title.en')
                ->label(__('filament/Services/ServiceCore.title_en'))
                ->required(),
                TextInput::make('title.ar')
                ->label(__('filament/Services/ServiceCore.title_ar'))
                ->required(),
                Textarea::make('description.en')
                ->label(__('filament/Services/ServiceCore.description_en'))
                ->nullable(),
                Textarea::make('description.ar')
                ->label(__('filament/Services/ServiceCore.description_ar'))
                ->nullable(),
                FileUpload::make('image')
                ->label(__('filament/Services/ServiceCore.image'))
                ->disk('public')
                ->directory('CoursesImage')
                ->image(),
                Select::make('parent_id')
                ->label('Parent Service')
                ->options(function () {
                    return ServiceCore::all()->mapWithKeys(function ($service) {
                        return [$service->id => $service->title['en'] ?? ''];
                    });
                })
                ->searchable()
                ->preload()
                ->nullable(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title.en')
                ->label(__('filament/Services/ServiceCore.title_en'))
                ->searchable(),
                TextColumn::make('description.en')
                ->label(__('filament/Services/ServiceCore.description_en'))
                ->searchable(),
                ImageColumn::make('image')
                ->label(__('filament/Services/ServiceCore.image')),
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
            'index' => Pages\ListServiceCores::route('/'),
            'create' => Pages\CreateServiceCore::route('/create'),
            'view' => Pages\ViewServiceCore::route('/{record}'),
            'edit' => Pages\EditServiceCore::route('/{record}/edit'),
        ];
    }
}
