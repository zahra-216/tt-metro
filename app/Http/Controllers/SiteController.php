<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Faculty;
use App\Models\GalleryAlbum;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Programme;
use App\Models\Stat;
use App\Models\StudentLifeItem;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public const LEVELS = ['Diploma', 'HND', 'Top-up', 'Degree', 'Postgraduate Diploma', 'Masters'];

    public function home()
    {
        $faculties = Faculty::where('is_active', true)->withCount(['programmes' => fn ($q) => $q->where('is_active', true)])->orderBy('sort_order')->get();

        return view('home', [
            'stats' => Stat::where('is_active', true)->orderBy('sort_order')->get(),
            'faculties' => $faculties,
            'popular' => Programme::with('faculty')->where('is_active', true)->orderByDesc('is_featured')->latest('wp_id')->take(6)->get(),
            'events' => Event::where('is_active', true)->orderByDesc('starts_on')->take(4)->get(),
            'board' => TeamMember::where('group', 'board')->where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'albums' => GalleryAlbum::where('is_active', true)->orderBy('sort_order')->take(4)->get(),
        ]);
    }

    public function about()
    {
        $groups = TeamMember::where('is_active', true)->orderBy('sort_order')->get()->groupBy('group');

        return view('about', [
            'groups' => $groups,
            'partners' => Partner::where('is_active', true)->orderBy('sort_order')->get(),
            'stats' => Stat::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function academics(Request $request)
    {
        $faculties = Faculty::where('is_active', true)->orderBy('sort_order')->get();

        $programmes = Programme::with('faculty')->where('is_active', true)
            ->when($request->filled('faculty'), fn ($q) => $q->whereHas('faculty', fn ($f) => $f->where('slug', $request->faculty)))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->level))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->q.'%'))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('academics', ['faculties' => $faculties, 'programmes' => $programmes, 'levels' => self::LEVELS]);
    }

    public function programme(Programme $programme)
    {
        abort_unless($programme->is_active, 404);

        return view('programme', [
            'programme' => $programme->load('faculty'),
            'related' => Programme::where('faculty_id', $programme->faculty_id)->where('id', '!=', $programme->id)->where('is_active', true)->inRandomOrder()->take(3)->get(),
        ]);
    }

    public function studentLife()
    {
        return view('student-life', [
            'items' => StudentLifeItem::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category'),
        ]);
    }

    public function gallery()
    {
        return view('gallery', ['albums' => GalleryAlbum::where('is_active', true)->withCount('items')->orderBy('sort_order')->get()]);
    }

    public function album(GalleryAlbum $album)
    {
        abort_unless($album->is_active, 404);

        return view('album', ['album' => $album->load('items')]);
    }

    public function events()
    {
        return view('events', [
            'events' => Event::where('is_active', true)->orderByDesc('starts_on')->paginate(9),
            'news' => Post::where('is_active', true)->orderByDesc('published_at')->take(3)->get(),
        ]);
    }

    public function event(Event $event)
    {
        abort_unless($event->is_active, 404);

        return view('event', ['event' => $event->load('images'), 'others' => Event::where('id', '!=', $event->id)->where('is_active', true)->orderByDesc('starts_on')->take(3)->get()]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_active, 404);

        return view('post', ['post' => $post]);
    }

    public function contact()
    {
        return view('contact');
    }

    public function apply(Request $request)
    {
        return view('apply', [
            'programmes' => Programme::where('is_active', true)->orderBy('title')->get(['id', 'title', 'level']),
            'selected' => $request->integer('programme'),
        ]);
    }
}