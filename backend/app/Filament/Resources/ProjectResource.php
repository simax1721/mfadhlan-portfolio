<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('projects')
                    ->imageEditor(),
                Forms\Components\TagsInput::make('tech_stack')
                    ->placeholder('Add technology and press Enter'),
                Forms\Components\Tabs::make('translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->schema([
                                Forms\Components\TextInput::make('subtitle_en')
                                    ->label('Subtitle')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description_en')
                                    ->label('Description')
                                    ->rows(3),
                                Forms\Components\Repeater::make('bullets_en')
                                    ->label('Bullets')
                                    ->simple(
                                        Forms\Components\TextInput::make('bullet')->required(),
                                    )
                                    ->addActionLabel('Add bullet point')
                                    ->reorderable(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Indonesian')
                            ->schema([
                                Forms\Components\TextInput::make('subtitle_id')
                                    ->label('Subtitle')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description_id')
                                    ->label('Description')
                                    ->rows(3),
                                Forms\Components\Repeater::make('bullets_id')
                                    ->label('Bullets')
                                    ->simple(
                                        Forms\Components\TextInput::make('bullet')->required(),
                                    )
                                    ->addActionLabel('Add bullet point')
                                    ->reorderable(),
                            ]),
                    ]),
                Forms\Components\TextInput::make('demo_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('github_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Toggle::make('featured'),
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
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('subtitle_en')->label('Subtitle'),
                Tables\Columns\IconColumn::make('featured')->boolean(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
