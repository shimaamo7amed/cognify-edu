<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailabilityResource\Pages;
use App\Models\Availability;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;

class AvailabilityResource extends Resource
{
    protected static ?string $model = Availability::class;
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Schedule Management';
    protected static ?int $navigationSort = 1;
    

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
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

                        Select::make('interval')
                            ->options([
                                15 => '15 Minutes',
                                30 => '30 Minutes',
                                60 => '60 Minutes',
                            ])
                            ->required()
                            ->default(30),

                        Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'booked' => 'Booked',
                                'blocked' => 'Blocked',
                            ])
                            ->required()
                            ->default('available'),

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

                        Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('interval')
                    ->formatStateUsing(fn ($state) => "$state Minutes")
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'booked' => 'warning',
                        'blocked' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('recurrence_type')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'booked' => 'Booked',
                        'blocked' => 'Blocked',
                    ]),
                Tables\Filters\SelectFilter::make('interval')
                    ->options([
                        15 => '15 Minutes',
                        30 => '30 Minutes',
                        60 => '60 Minutes',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAvailabilities::route('/'),
            'create' => Pages\CreateAvailability::route('/create'),
            'edit' => Pages\EditAvailability::route('/{record}/edit'),
        ];
    }
}