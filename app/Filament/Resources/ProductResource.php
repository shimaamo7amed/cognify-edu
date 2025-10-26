<?php

namespace App\Filament\Resources;

use App\Models\Tag;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Cognify Store';
    protected static ?string $navigationIcon = 'heroicon-s-cube';
    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        // dd(Tag::all());

        return $form->schema([
            Tabs::make('Product Form')
                ->tabs([
                    Tabs\Tab::make(__('General Information'))->schema([
                        Section::make(__('Basic Info'))->schema([
                            Select::make('category_id')
                            ->label(__('Category'))
                            ->options(Category::all()->pluck('name.' . app()->getLocale(), 'id'))
                            ->required(),
                            TextInput::make('oldPrice')
                                ->label(__('Current Price'))
                                ->numeric()
                                ->required()
                                ->reactive(),

                            TextInput::make('discountPercentage')
                                ->label(__('Discount'))
                                ->numeric()
                                ->suffix('%')
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                    $oldPrice = $get('oldPrice');
                                    if ($oldPrice && $state !== null) {
                                        $discounted = $oldPrice - ($oldPrice * ($state / 100));
                                        $set('price', round($discounted, 2));
                                        $set('sale', $state > 0);
                                    } elseif ($oldPrice) {
                                        $set('price', $oldPrice);
                                        $set('sale', false);
                                    }
                                }),
                            TextInput::make('price')
                                ->label(__('Price After Discount'))
                                ->numeric()
                                ->disabled()
                                ->dehydrated(false)
                                ->afterStateHydrated(function (callable $set, callable $get) {
                                    $price = $get('price');
                                    $oldPrice = $get('oldPrice');
                                    $discount = $get('discountPercentage');

                                    if (!$discount && $oldPrice && !$price) {
                                        $set('price', $oldPrice);
                                    }
                            }),
                            TextInput::make('quantity')
                                ->label(__('Quantity'))
                                ->numeric()
                                ->nullable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $set('inStock', $state >= 1);
                                }),

                            TextInput::make('ageRange')
                                ->label(__('Age Range'))
                                ->required()
                                ->nullable(),

                            Toggle::make('inStock')
                                ->label(__('In Stock')),

                            Toggle::make('sale')
                                ->label(__('On Sale'))
                                ->disabled()
                                ->dehydrated(fn () => true),
                        ])->columns(2),
                    ]),

                    Tabs\Tab::make(__('Images'))->schema([
                        Section::make(__('Main Image'))->schema([
                            FileUpload::make('main_image')
                                ->label(__('Main Image'))
                                ->directory('products')
                                ->multiple()
                                ->maxFiles(4)
                                ->imageEditor()
                                ->imageEditorMode(2)
                                ->downloadable()
                                ->required(),
                        ]),
                    ]),

                    Tab::make(__('Tags'))
                        ->schema([
                            Section::make(__('Product Tags'))->schema([
                                Select::make('tag_id')
                                    ->label(__('Tags'))
                                    ->options(
                                        Tag::all()
                                            ->mapWithKeys(fn ($tag) => [$tag->id => $tag->name[app()->getLocale()] ?? ''])
                                    )
                                    ->searchable()
                                    ->required(),
                            ]),
                    ]),
                    Tabs\Tab::make(__('Details'))->schema([
                        Section::make(__('English Info'))->schema([
                            TextInput::make('name.en')->label(__('Name (EN)'))->required(),
                            Textarea::make('shortDes.en')->label(__('Short Description (EN)'))->nullable(),
                            Textarea::make('longDes.en')->label(__('Long Description (EN)'))->required(),
                        ]),
                        Section::make(__('Arabic Info'))->schema([
                            TextInput::make('name.ar')->label(__('Name (AR)'))->required(),
                            Textarea::make('shortDes.ar')->label(__('Short Description (AR)'))->nullable(),
                            Textarea::make('longDes.ar')->label(__('Long Description (AR)'))->required(),
                        ]),
                    ]),
            ])
            ->columnSpanFull(),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('name.en')->label('Name'),
                Tables\Columns\TextColumn::make('price')->money('EGP'),
                Tables\Columns\IconColumn::make('inStock')->boolean(),
                Tables\Columns\IconColumn::make('sale')->boolean(),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\TextColumn::make('tags.name')->label('Tags')
                            ])
            ->filters([

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
