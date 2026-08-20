<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationEntryResource\Pages;
use App\Models\OrganizationEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationEntryResource extends Resource
{
    protected static ?string $model = OrganizationEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Organization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('role')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('organization')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->required()
                    ->placeholder('2023'),
                Forms\Components\Tabs::make('translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->schema([
                                Forms\Components\Textarea::make('description_en')
                                    ->label('Description')
                                    ->rows(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Indonesian')
                            ->schema([
                                Forms\Components\Textarea::make('description_id')
                                    ->label('Description')
                                    ->rows(2),
                            ]),
                    ]),
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
                Tables\Columns\TextColumn::make('role')->searchable(),
                Tables\Columns\TextColumn::make('organization')->searchable(),
                Tables\Columns\TextColumn::make('year'),
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
            'index' => Pages\ListOrganizationEntries::route('/'),
            'create' => Pages\CreateOrganizationEntry::route('/create'),
            'edit' => Pages\EditOrganizationEntry::route('/{record}/edit'),
        ];
    }
}
