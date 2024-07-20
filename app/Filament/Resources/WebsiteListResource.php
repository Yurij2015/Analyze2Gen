<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteListResource\Pages;
use App\Filament\Resources\WebsiteListResource\RelationManagers;
use App\Models\WebsiteList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteListResource extends Resource
{
    protected static ?string $model = WebsiteList::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('description'),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Forms\Components\Textarea::make('websites')
                    ->rows(5)
                    ->afterStateHydrated(function ($state, $set) {
                        if (is_array($state)) {
                            $set('websites', implode("\n", $state));
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        return array_filter(explode("\n", trim($state)));
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('project.name'),
                Tables\Columns\TextColumn::make('websites')
                    ->listWithLineBreaks()
//                    ->bulleted()
                    ->limitList(10)
                    ->expandableLimitedList()
                    ->color('primary')
                    ->icon('heroicon-o-globe-alt')
                    ->iconColor('info'),
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
            'index' => Pages\ListWebsiteLists::route('/'),
            'create' => Pages\CreateWebsiteList::route('/create'),
            'edit' => Pages\EditWebsiteList::route('/{record}/edit'),
        ];
    }
}
