@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">{{ $page->title }}</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
        <div class="prose prose-gray prose-headings:text-gray-900 prose-headings:font-bold prose-a:text-amber-600 prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl max-w-none">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
