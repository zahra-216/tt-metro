@if (setting('map_embed_url'))
    <div class="overflow-hidden rounded-3xl border border-line shadow-card">
        <iframe src="{{ setting('map_embed_url') }}" title="TT Metro Campus location map" class="h-[420px] w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
@endif