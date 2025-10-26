<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Partner;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PartnerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PartnerResource\RelationManagers;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/Forms/Partners.group');
    }
    public static function getModelLabel(): string
    {
        return __('filament/Forms/Partners.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/Forms/Partners.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('organizationName')
                ->label(__('filament/Forms/Partners.organizationName')),
                TextInput::make('contactPersonName')
                ->label(__('filament/Forms/Partners.contactPersonName')),
                TextInput::make('email')
                ->label(__('filament/Forms/Partners.email')),
                TextInput::make('phoneNumber')
                ->label(__('filament/Forms/Partners.phoneNumber')),
                TextInput::make('location')
                ->label(__('filament/Forms/Partners.location')),
                Select::make('service_id')
                ->relationship('service','title.en')
                ->label(__('filament/Forms/Partners.service_id'))
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->title['en'] ?? ''),
                Select::make('service_items')
                    ->relationship('serviceItems', 'title')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->title['en'] ?? '')
                    ->label(__('filament/Forms/Partners.service_items')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organizationName')
                ->label(__('filament/Forms/Partners.organizationName')),
                TextColumn::make('contactPersonName')
                ->label(__('filament/Forms/Partners.contactPersonName')),
                TextColumn::make('email')
                ->label(__('filament/Forms/Partners.email')),
                TextColumn::make('phoneNumber')
                ->label(__('filament/Forms/Partners.phoneNumber')),
                TextColumn::make('location')
                ->label(__('filament/Forms/Partners.location')),
                TextColumn::make('is_approved')
                ->label(__('filament/Forms/Partners.is_approved')),
                TextColumn::make('service.title.en')
                ->label(__('filament/Forms/Partners.service_id')),
                TextColumn::make('serviceItems.title')
                ->label(__('filament/Forms/Partners.service_items'))
                ->listWithLineBreaks()
                    ->getStateUsing(function ($record) {
                        return $record->serviceItems->map(function ($item) {
                            return $item->title['en'] ?? '';
                        });
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('accept')
                ->label(__('filament/Forms/Partners.accept'))
                ->action(function (Partner $record) {
                $record->update(['is_approved' => 'accepted']);
                Mail::send('mails.partner_accepted', ['partner' => $record], function ($message) use ($record) {
                $message->to($record->email)
                ->subject('Partnership application accepted');
                });
                })
                ->requiresConfirmation()
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn (Partner $record) => $record->is_approved === 'pending'),
                Action::make('reject')
                ->label(__('filament/Forms/Partners.reject'))
                ->action(function (Partner $record) {
                $record->update(['is_approved' => 'rejected']);
                Mail::send('mails.partner_rejected', ['partner' => $record], function ($message) use ($record) {
                $message->to($record->email)
                ->subject('Partnership application rejected');
                });
                })
                ->requiresConfirmation()
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn (Partner $record) => $record->is_approved === 'pending'),
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
            'index' => Pages\ListPartners::route('/'),
            'view' => Pages\ViewPartner::route('/{record}'),
        ];
    }
}