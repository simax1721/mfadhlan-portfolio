<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('period')
                    ->required()
                    ->placeholder('Apr 2026 - Present'),
                Forms\Components\TagsInput::make('tech_stack')
                    ->placeholder('Add technology and press Enter'),
                Forms\Components\Tabs::make('translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English bullets')
                            ->schema([
                                Forms\Components\Repeater::make('bullets_en')
                                    ->simple(
                                        Forms\Components\Textarea::make('bullet')
                                            ->required()
                                            ->rows(2),
                                    )
                                    ->addActionLabel('Add bullet point')
                                    ->reorderable(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Indonesian bullets')
                            ->schema([
                                Forms\Components\Repeater::make('bullets_id')
                                    ->simple(
                                        Forms\Components\Textarea::make('bullet')
                                            ->required()
                                            ->rows(2),
                                    )
                                    ->addActionLabel('Add bullet point')
                                    ->reorderable(),
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
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('company')->searchable(),
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
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
