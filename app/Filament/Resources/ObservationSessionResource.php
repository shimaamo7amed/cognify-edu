<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ObservationSession;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ObservationSessionResource\Pages;
use App\Filament\Resources\ObservationSessionResource\RelationManagers;

class ObservationSessionResource extends Resource
{
    protected static ?string $model = ObservationSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Observation Session';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name.en')
                    ->label('Session Name (English)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.ar')
                    ->label('Session Name (Arabic)')
                    ->required()
                    ->maxLength(255),
                Textarea::make('desc.en')
                    ->label('Description(English)')
                    ->rows(4),
                Textarea::make('desc.ar')
                    ->label('Description(Arabic)')
                    ->rows(4),
                TextInput::make('service_fee')
                    ->label('Price (EPG)')
                    ->numeric()
                    ->required(),
                TextInput::make('service_tax')
                        ->label('Fees (EPG)')
                        ->numeric()
                        ->required(),
                TextInput::make('duration')
                    ->label('Session Duration (minutes)')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('min')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en'),
                TextColumn::make('service_fee')->label('Price'),
                TextColumn::make('service_tax')->label('Fees'),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListObservationSessions::route('/'),
            'create' => Pages\CreateObservationSession::route('/create'),
            'view' => Pages\ViewObservationSession::route('/{record}'),
            'edit' => Pages\EditObservationSession::route('/{record}/edit'),
        ];
    }
}
