<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteResource\Pages;
use App\Filament\Resources\WebsiteResource\RelationManagers;
use App\Models\Website;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteResource extends Resource
{
    protected static ?string $model = Website::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->disabled(),
                Forms\Components\Select::make('project_id')->relationship('project', 'name')->disabled(),
                Forms\Components\TextInput::make('baseDomain')->required()->disabled(),
                Forms\Components\Textarea::make('description')->required()->disabled(),
                Forms\Components\Textarea::make('keywords')->required()->disabled(),
                Forms\Components\Textarea::make('robots')->required()->disabled(),
                Forms\Components\Textarea::make('canonical')->required()->disabled(),
                Forms\Components\Textarea::make('general')->required()->disabled(),
                Forms\Components\Checkbox::make('googleTag')->required()->disabled(),
                Forms\Components\Checkbox::make('facebookPixel')->required()->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('project.name')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('baseDomain')->searchable(isIndividual: true)->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable(isIndividual: true)->toggleable(),
                Tables\Columns\TextColumn::make('keywords')->toggleable(),
                Tables\Columns\TextColumn::make('robots')->toggleable(),
                Tables\Columns\TextColumn::make('canonical')->toggleable(),
                Tables\Columns\TextColumn::make('general')->searchable(isIndividual: true)->toggleable(),
                Tables\Columns\IconColumn::make('googleTag')->boolean()->toggleable(),
                Tables\Columns\IconColumn::make('facebookPixel')->boolean()->toggleable(),
//                Tables\Columns\TextColumn::make('siteLinks'),
//                Tables\Columns\TextColumn::make('siteMap'),
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
            'index' => Pages\ListWebsites::route('/'),
            'create' => Pages\CreateWebsite::route('/create'),
            'edit' => Pages\EditWebsite::route('/{record}/edit'),
        ];
    }
}
