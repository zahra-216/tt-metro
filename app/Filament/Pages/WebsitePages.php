<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\PageTexts;
use BackedEnum;
use Filament\Actions\Action;
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
 * Rename website pages: menu name, big heading, the line under it and the Google description.
 *
 * @property-read Schema $form
 */
class WebsitePages extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Pages';

    protected static ?string $title = 'Pages';

    protected static ?string $slug = 'pages';

    protected static ?int $navigationSort = -11;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function getSubheading(): ?string
    {
        return 'Press a page to open it. The boxes show the words that are on the website now: change them and press "Save changes" at the bottom. To get the original words back, empty the box and save. Page addresses never change, so old links keep working.';
    }

    public function mount(): void
    {
        $state = [];
        foreach (array_keys(PageTexts::all()) as $page) {
            foreach (PageTexts::fields($page) as $field) {
                $state[PageTexts::key($page, $field)] = PageTexts::get($page, $field); // the words on the website now
            }
        }
        $this->form->fill($state);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $sections = [];

        foreach (PageTexts::all() as $page => $info) {
            $boxes = [];

            foreach (PageTexts::fields($page) as $field) {
                $standard = PageTexts::standard($page, $field);
                $box = in_array($field, ['subtitle', 'description'], true)
                    ? Textarea::make(PageTexts::key($page, $field))->rows($field === 'description' ? 3 : 2)->autosize()->columnSpanFull()
                    : TextInput::make(PageTexts::key($page, $field));

                $boxes[] = $box
                    ->label(match (true) {
                        $field === 'menu' && $page === 'apply' => 'Button name (top menu and bottom links)',
                        $field === 'menu' && $page === 'privacy' => 'Link name (bottom of every page)',
                        $field === 'menu' => 'Menu name',
                        $field === 'heading' => 'Big heading on the page',
                        $field === 'subtitle' => 'Line under the heading',
                        default => 'Description for Google',
                    })
                    ->placeholder($standard)
                    ->maxLength(PageTexts::maxLength($page, $field))
                    ->helperText(trim(match ($field) {
                        'menu' => 'Keep it short (up to '.PageTexts::maxLength($page, $field).' letters). Long names make the menu text smaller, or fold the menu into a button, so it still fits.',
                        'description' => 'One or two sentences shown under the page name in Google results.',
                        default => '',
                    }.(PageTexts::followsProgrammes($page, $field) ? ' This one updates by itself when your programmes change, as long as you do not edit it.' : '')) ?: null);
            }

            $sections[] = Section::make($info['label'].' page')
                ->description($page === 'home' ? 'The banner words, welcome text and Google description of the home page are in Site settings.' : null)
                ->headerActions([
                    Action::make('view_'.$page)->label('View page')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')->url(route($info['route']), shouldOpenInNewTab: true),
                ])
                ->collapsible()
                ->collapsed()
                ->columns(['default' => 1, 'lg' => 2])
                ->schema($boxes);
        }

        return $schema->components($sections);
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
        $state = $this->form->getState();

        foreach (array_keys(PageTexts::all()) as $page) {
            foreach (PageTexts::fields($page) as $field) {
                $key = PageTexts::key($page, $field);
                // the website uses the short hyphen everywhere, also in text pasted from Word
                $value = trim(str_replace(["\u{2014}", "\u{2013}"], '-', (string) ($state[$key] ?? '')));
                // unchanged original words are not stored, so the page keeps following the standard wording
                Setting::put($key, $value === PageTexts::standard($page, $field) ? '' : $value);
            }
        }

        $this->mount(); // show the tidied words, and the original ones in emptied boxes

        Notification::make()->title('Saved - the website is updated')->success()->send();
    }
}
