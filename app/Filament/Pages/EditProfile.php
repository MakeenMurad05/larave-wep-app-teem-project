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
                    Grid::make([
                        'default' => 1,
                        'lg' => 3, // تقسيم المساحة: عمود للصورة وعمودين للحقول
                    ])
                    ->schema([
                        
                        // حقل الصورة - مرتبط بعمود 'photo' في الموديل
                        FileUpload::make('photo')
                            ->label('Profile Photo')
                            ->avatar() // يجعلها دائرية
                            ->imageEditor()
                            ->directory('profiles') // اسم المجلد في الـ Storage
                            ->columnSpan(1),

                        // شبكة الحقول النصية - تأخذ باقي المساحة
                        Grid::make(2)
                            ->columnSpan(2)
                            ->schema([
                                
                                TextInput::make('first_name')
                                    ->label('First name')
                                    ->required(),

                                TextInput::make('last_name')
                                    ->label('Last name')
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('Phone')
                                    ->tel()
                                    ->prefixIcon('heroicon-m-phone')
                                    ->required(),

                                DatePicker::make('birth_date')
                                    ->label('Birth date')
                                    ->native(false) // ليظهر بشكل مودرن
                                    ->displayFormat('m/d/Y')
                                    ->required(),

                                Textarea::make('bio')
                                    ->label('Bio')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ]),
                ])
                ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        $photoPath = is_array($data['photo']) ? array_values($data['photo'])[0] : $data['photo'];

        
       $profile = Profile::updateOrCreate(
        ['users_id' => $user->id], // شرط البحث (ابحث عن هذا اليوزر)
        [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'],
            'birth_date' => $data['birth_date'],
            'bio'        => $data['bio'],
            'photo'      => $photoPath, // هنا نضع الصورة صراحةً
        ]
    );

        Notification::make()
            ->success()
            ->title('Profile saved successfully')
            ->send();
    }
}