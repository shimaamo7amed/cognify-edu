<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SessionSlot;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SessionSlotResource\Pages;
use App\Filament\Resources\SessionSlotResource\RelationManagers;

class SessionSlotResource extends Resource
{
    protected static ?string $model = SessionSlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Observation Session';
    protected static ?string $modelLabel = " Availability Session Slot";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('observation_session_id')
                ->label('Related Session')
                ->relationship('session', 'name')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name['en'] ?? '')
                ->searchable()
                ->preload()
                ->required(),
                Card::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            DateTimePicker::make('start_time')
                                ->required()
                                ->label('Start Time')
                                ->withoutSeconds(),

                            DateTimePicker::make('end_time')
                                ->required()
                                ->label('End Time')
                                ->withoutSeconds()
                                ->after('start_time'),
                        ]),
                ]),
                Card::make()
                    ->schema([
                        Toggle::make('is_recurring')
                            ->label('Enable Recurring Schedule')
                            ->reactive(),

                        Select::make('recurrence_type')
                            ->options([
                                    'weekly' => 'Weekly',
                                    'bi-weekly' => 'Bi-weekly',
                                    'monthly' => 'Monthly',
                                ])
                                ->visible(fn ($get) => $get('is_recurring'))
                                ->required(fn ($get) => $get('is_recurring')),

                        Select::make('recurrence_days')
                            ->options([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ])
                        ->multiple()
                        ->visible(fn ($get) => $get('is_recurring'))
                        ->required(fn ($get) => $get('is_recurring'))
                        ->label('Recurring Days'),
                        DateTimePicker::make('recurrence_end_date')
                            ->visible(fn ($get) => $get('is_recurring'))
                            ->required(fn ($get) => $get('is_recurring'))
                            ->label('Recurrence End Date')
                            ->after('start_time'),
                        ])
                    ->columns(2),
                Select::make('interval')
                    ->options([
                        15 => '15 Minutes',
                        30 => '30 Minutes',
                        60 => '60 Minutes',
                ])
                ->required()
                ->default(30),
                Toggle::make('is_booked')
                    ->label('Booked')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('session.name')->label('Session Name')->getStateUsing(fn ($record) => $record->session->name['en'] ?? '')->sortable()->searchable(),
                TextColumn::make('start_time')->label('Start Time')->sortable()->searchable(),
                TextColumn::make('end_time')->label('End Time')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSessionSlots::route('/'),
            'create' => Pages\CreateSessionSlot::route('/create'),
            'view' => Pages\ViewSessionSlot::route('/{record}'),
            'edit' => Pages\EditSessionSlot::route('/{record}/edit'),
        ];
    }
}
