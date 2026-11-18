<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\Sections;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
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
 * One switch per website section, grouped by page. Switch off = the section disappears from the website;
 * nothing is deleted, switch on to bring it back.
 *
 * @property-read Schema $form
 */
class ShowHideSections extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Show / hide sections';

    protected static ?string $title = 'Show / hide sections';

    protected static ?int $navigationSort = -10;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function getSubheading(): ?string
    {
        return 'Switch a section off to hide it from the website, and on to show it again. Nothing is deleted: the text, pictures and lists stay saved. Press "Save changes" at the bottom.';
    }

    public function mount(): void
    {
        $state = [];
        foreach (Sections::defaults() as $key => $default) {
            $state[Sections::settingKey($key)] = Sections::on($key);
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

        foreach (Sections::all() as $title => $page) {
            $toggles = [];
            foreach ($page['items'] as $key => [$label, $hint]) {
                $toggles[] = Toggle::make(Sections::settingKey($key))
                    ->label($label)
                    ->helperText($hint)
                    ->onColor('success')
                    ->offColor('gray')
                    ->inline(false);
            }

            $sections[] = Section::make($title)
                ->description(match ($title) {
                    'Inside the Home page' => 'The top banner of the home page always shows. Change its words in Site settings.',
                    'Whole pages (hide a page completely)' => 'Switch a page off and visitors who open its link go to the home page. Home, Academics (programmes), Contact and Apply always stay on.',
                    default => null,
                })
                ->headerActions($page['route'] ? [
                    Action::make('view_'.$page['route'])->label('View page')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')->url(route($page['route']), shouldOpenInNewTab: true),
                ] : [])
                ->columns(['default' => 1, 'lg' => 2])
                ->schema($toggles);
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

        foreach (Sections::defaults() as $key => $default) {
            Setting::put(Sections::settingKey($key), ! empty($state[Sections::settingKey($key)]) ? '1' : '0');
        }

        Notification::make()->title('Saved - the website is updated')->success()->send();
    }
}
