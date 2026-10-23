@php($branches = \App\Models\Branch::where('is_active', true)->orderBy('sort_order')->get())
@if ($branches->isNotEmpty())
    <div {{ $attributes }}>
        <span class="eyebrow">Our branches</span>
        <h2 class="h-section">Visit a branch near you</h2>
        <div class="mt-8 grid gap-5 sm:grid-cols-2">
            @foreach ($branches as $b)
                <div class="card flex flex-col p-6">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-red-50 text-red-600"><x-ico name="pin" class="h-6 w-6" /></span>
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold">{{ $b->name }}</h3>
                            <p class="mt-1 text-left text-sm leading-relaxed text-muted">{{ $b->address }}</p>
                        </div>
                    </div>
                    @if ($b->phone_1 || $b->phone_2)
                        <div class="mt-4 flex items-start gap-4">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-emerald-50 text-emerald-600"><x-ico name="phone" class="h-6 w-6" /></span>
                            <ul class="space-y-1 text-sm leading-relaxed text-muted">
                                @foreach (array_filter([$b->phone_1, $b->phone_2]) as $phone)
                                    <li class="text-left"><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="hover:text-brand-700">{{ $phone }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($b->map_url)
                        <a href="{{ $b->map_url }}" target="_blank" rel="noopener" class="btn btn-ghost mt-6 self-start">Get directions <x-ico name="arrow" class="h-4 w-4" /></a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif