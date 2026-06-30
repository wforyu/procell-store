<div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4">
    @foreach($images as $img)
        <div class="rounded-lg overflow-hidden border border-gray-200">
            <a href="{{ asset('storage/' . $img->image) }}" target="_blank">
                <img src="{{ asset('storage/' . $img->image) }}" class="w-full h-40 object-cover hover:opacity-90 transition-opacity">
            </a>
        </div>
    @endforeach
</div>
