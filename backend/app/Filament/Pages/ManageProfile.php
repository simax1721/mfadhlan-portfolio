<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $title = 'Profile';

    protected static string $view = 'filament.pages.manage-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Profile::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Info')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('role')->required(),
                    ]),

                Forms\Components\Section::make('Tagline & Summary')
                    ->description('Shown on the site in whichever language the visitor picks.')
                    ->schema([
                        Forms\Components\Tabs::make('translations')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('tagline_en'),
                                        Forms\Components\Textarea::make('summary_en')
                                            ->required()
                                            ->rows(5),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Indonesian')
                                    ->schema([
                                        Forms\Components\TextInput::make('tagline_id'),
                                        Forms\Components\Textarea::make('summary_id')
                                            ->required()
                                            ->rows(5),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('location')
                            ->required(),
                        Forms\Components\TextInput::make('github_url')
                            ->url(),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url(),
                    ]),

                Forms\Components\Section::make('Media')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->directory('profile')
                            ->imageEditor(),
                        Forms\Components\FileUpload::make('cv_file')
                            ->label('CV (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('profile'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Profile::current()->update($this->form->getState());

        Notification::make()
            ->title('Profile updated')
            ->success()
            ->send();
    }
}
