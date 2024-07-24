<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebPageResource\Pages;
use App\Filament\Resources\WebPageResource\RelationManagers;
use App\Models\WebPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebPageResource extends Resource
{
    protected static ?string $model = WebPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->disabled(),
                Forms\Components\Select::make('website_id')->relationship('website.project', 'name')->disabled(),
                Forms\Components\TextInput::make('pageUrl')->required()->disabled(),
                Forms\Components\Textarea::make('description')->required()->disabled(),
                Forms\Components\Textarea::make('html')->required()->disabled()->rows(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('website.project.name')->sortable()->searchable(isIndividual: true),
//                Tables\Columns\TextColumn::make('website.baseDomain')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('pageUrl')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('description')->searchable(isIndividual: true),
//                Tables\Columns\TextColumn::make('html'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListWebPages::route('/'),
            'create' => Pages\CreateWebPage::route('/create'),
            'edit' => Pages\EditWebPage::route('/{record}/edit'),
        ];
    }
}
