<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use App\Enums\ProductType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Products')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->live(onBlur: true)
                                    ->autofocus()
                                    ->afterStateUpdated(function (string $state, Set $set) {
                                        // Automatically generate a slug when the name is updated.
                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated(),

                                // not needed because filament will automatically add relationship owner id
                                // Forms\Components\Hidden::make('brand_id')
                                //     ->default($this->getOwnerRecord()->id),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full'),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU (Stock Keeping Unit)')
                                    ->unique(ignoreRecord: true)
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->default(1)
                                    ->required(),

                                Forms\Components\Select::make('type')
                                    ->options(ProductType::options())
                                    ->default(ProductType::Deliverable),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Additional Information')
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visibility')
                                    ->helperText('Whether or not the product is visible to customers.')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Whether or not the product is featured on the homepage.'),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->helperText('The date the product will be available for purchase.')
                                    ->default(now()),

                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->preload()
                                    ->multiple()
                                    ->required(),

                                Forms\Components\FileUpload::make('image_url')
                                    ->label(false)
                                    ->image()
                                    ->imageCropAspectRatio(null)
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable()
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('type')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
