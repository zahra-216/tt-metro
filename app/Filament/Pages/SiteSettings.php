<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'Site settings';

    protected static ?int $navigationSort = 99;

    /** @var array<string, mixed> */
    public ?array $data = [];

    private const KEYS = [
        'hero_title', 'hero_subtitle', 'hero_image', 'welcome_title', 'welcome_text',
        'admissions_badge', 'admissions_text', 'apply_email',
        'phone_1', 'phone_2', 'phone_landline', 'email_1', 'email_2', 'address', 'opening_hours', 'map_embed_url',
        'student_portal_url', 'staff_portal_url', 'verify_url',
        'facebook', 'instagram', 'youtube', 'linkedin', 'tiktok',
        'chairman_message', 'chairman_image', 'vision', 'mission', 'motto', 'what_we_do', 'program_options', 'registrations', 'satellite', 'values',
        'intro_council', 'intro_senate', 'intro_staff', 'intro_lecturers', 'intro_partners', 'intro_facilities', 'intro_scholarship',
    ];

    public function mount(): void
    {
        $this->form->fill(collect(self::KEYS)->mapWithKeys(fn ($k) => [$k => Setting::get($k)])->all());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Home page banner')->columns(2)->schema([
                TextInput::make('hero_title')->columnSpanFull(),
                Textarea::make('hero_subtitle')->rows(2)->columnSpanFull(),
                FileUpload::make('hero_image')->image()->directory('site')->columnSpanFull(),
                TextInput::make('welcome_title'),
                Textarea::make('welcome_text')->rows(5)->columnSpanFull(),
            ]),
            Section::make('Admissions bar & forms')->columns(2)->schema([
                TextInput::make('admissions_badge')->label('Top bar badge')->placeholder('ADMISSIONS 2025/2026'),
                TextInput::make('admissions_text')->label('Top bar message'),
                TextInput::make('apply_email')->label('Email that receives application & contact forms')->email()->columnSpanFull(),
            ]),
            Section::make('Contact details')->columns(2)->schema([
                TextInput::make('phone_1'),
                TextInput::make('phone_2'),
                TextInput::make('phone_landline'),
                TextInput::make('opening_hours'),
                TextInput::make('email_1')->email(),
                TextInput::make('email_2')->email(),
                Textarea::make('address')->rows(2)->columnSpanFull(),
                Textarea::make('map_embed_url')
                    ->label('Google Maps embed URL')
                    ->helperText('Google Maps → Share → Embed a map → copy the src="..." link only.')
                    ->rows(2)
                    ->columnSpanFull(),
            ]),
            Section::make('About page')->collapsed()->columns(2)->schema([
                FileUpload::make('chairman_image')->image()->directory('about'),
                RichEditor::make('chairman_message')->label('Message from the Founder Chairman')->columnSpanFull(),
                Textarea::make('vision')->rows(3),
                Textarea::make('mission')->rows(3),
                TextInput::make('motto')->columnSpanFull(),
                RichEditor::make('what_we_do')->label('What we do')->columnSpanFull(),
                RichEditor::make('program_options')->columnSpanFull(),
                RichEditor::make('registrations')->label('Registrations & partnerships')->columnSpanFull(),
                RichEditor::make('satellite')->label('Satellite centre opportunity')->columnSpanFull(),
                RichEditor::make('values')->columnSpanFull(),
            ]),
            Section::make('Introductions (people, partners, student life)')->collapsed()->columns(1)->schema([
                RichEditor::make('intro_council')->label('Governing Council'),
                RichEditor::make('intro_senate')->label('Academic Senate'),
                RichEditor::make('intro_staff')->label('Academic & Management Staff'),
                RichEditor::make('intro_lecturers')->label('Our Lecturers'),
                RichEditor::make('intro_partners')->label('Affiliations & Partners'),
                RichEditor::make('intro_facilities')->label('Facilities'),
                RichEditor::make('intro_scholarship')->label('Scholarships'),
            ]),
            Section::make('Portals & links')->columns(2)->schema([
                TextInput::make('student_portal_url')->url(),
                TextInput::make('staff_portal_url')->url(),
                TextInput::make('verify_url')->label('Certificate verification URL')->url(),
            ]),
            Section::make('Social media')->columns(2)->schema([
                TextInput::make('facebook')->url(),
                TextInput::make('instagram')->url(),
                TextInput::make('youtube')->url(),
                TextInput::make('linkedin')->url(),
                TextInput::make('tiktok')->url(),
            ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Save changes')->submit('save')->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::put($key, $value);
        }

        Notification::make()->title('Settings saved')->success()->send();
    }
}
