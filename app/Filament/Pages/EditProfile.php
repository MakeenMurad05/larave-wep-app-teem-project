<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Filament\Schemas\Schema;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    //protected static string $navigationIcon = 'heroicon-o-user';
    protected string $view = 'filament.pages.edit-profile';
    protected static bool $shouldRegisterNavigation = false; // Hide from sidebar
    protected static ?string $title = 'My Profile';

    public ?array $data = [];

    public function mount(): void
    {
        // specific to the logged-in user
        $user = Auth::user();
        
        // Get existing profile or create empty array
        $profile = $user->profile; 

        if ($profile) {
            $this->form->fill($profile->attributesToArray());
        } else {
            // Fill mostly empty, but you could pre-fill email if needed
            $this->form->fill([]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
             ->schema([
                Section::make('My Profile')
                    ->description('Update your personal details and photo.')
                    ->schema([
                        // Create a Grid with 3 columns total
                        // 1 column for Photo, 2 columns for Text
                        Grid::make(3)
                            ->schema([
                                
                                // --- LEFT COLUMN (1/3 width) ---
                                Group::make()
                                    ->columnSpan(1)
                                    ->schema([
                                        FileUpload::make('photo')
                                            ->label('Profile Photo')
                                            ->avatar() // Makes it circular
                                            ->imageEditor() // Allows cropping
                                            ->alignCenter() // Centers it in the column
                                            ->disk('public') // <--- Add this: Forces it to use the public disk
                                            ->directory('profile-photos') // Folder name inside storage/app/public/
                                            ->visibility('public') // <--- Add this: Ensures the file is viewable
                                            ->columnSpanFull(),
                                    ]),

                                // --- RIGHT COLUMN (2/3 width) ---
                                Group::make()
                                    ->columnSpan(2)
                                    ->schema([
                                        // Row 1: Names
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('first_name')
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                TextInput::make('last_name')
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),

                                        // Row 2: Contact Info
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('phone')
                                                    ->tel()
                                                    ->required()
                                                    ->prefixIcon('heroicon-m-phone'),
                                                
                                                DatePicker::make('birth_date')
                                                    ->required()
                                                    ->maxDate(now())
                                                    ->prefixIcon('heroicon-m-calendar'),
                                            ]),

                                        // Row 3: Bio
                                        Textarea::make('bio')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->placeholder('Tell us a little about yourself...'),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();


        
       $profile = Profile::updateOrCreate(
        ['users_id' => $user->id], // شرط البحث (ابحث عن هذا اليوزر)
        [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'],
            'birth_date' => $data['birth_date'],
            'bio'        => $data['bio'],
            'photo'      => $data['photo'], // هنا نضع الصورة صراحةً
        ]
    );

        Notification::make()
            ->success()
            ->title('Profile saved successfully')
            ->send();
    }
}