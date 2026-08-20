<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationEntryResource\Pages;
use App\Models\EducationEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EducationEntryResource extends Resource
{
    protected static ?string $model = EducationEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Education';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('degree')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('institution')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('period')
                    ->required()
                    ->placeholder('Oct 2020 - Feb 2025'),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                Tables\Columns\TextColumn::make('degree')->searchable(),
                Tables\Columns\TextColumn::make('institution')->searchable(),
                Tables\Columns\TextColumn::make('period'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEducationEntries::route('/'),
            'create' => Pages\CreateEducationEntry::route('/create'),
            'edit' => Pages\EditEducationEntry::route('/{record}/edit'),
        ];
    }
}
