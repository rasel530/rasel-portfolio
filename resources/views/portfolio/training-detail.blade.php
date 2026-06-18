@extends('layout')

@section('title', $training->title . ' · Training & Certifications')

@section('content')

<section class="training-detail">
    <div class="container">

        <a href="{{ route('home') }}#training" class="training-detail__back">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Training
        </a>

        <div class="training-detail__hero">
            @if(!empty($training->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($training->image))
            <img src="{{ asset('storage/' . $training->image) }}" alt="{{ $training->title }}" class="training-detail__hero-img">
            @endif

            <div style="position:relative;z-index:1;">
                @if($training->yearRange())
                <div style="font-size:13px;color:var(--accent-light);font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
                    {{ $training->yearRange() }}
                </div>
                @endif
                <h1 style="font-family:'Poppins',sans-serif;font-size:36px;font-weight:800;margin:0 0 8px;line-height:1.2;">{{ $training->title }}</h1>
                @if(!empty($training->organization))
                <div style="font-size:18px;color:var(--text-light);font-weight:500;">{{ $training->organization }}</div>
                @endif

                <div class="training-detail__meta">
                    @if(!empty($training->duration))
                    <div class="training-detail__meta-item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $training->duration }}
                    </div>
                    @endif
                    @if(!empty($training->certificate_url))
                    <a href="{{ $training->certificate_url }}" target="_blank" rel="noopener" class="training-detail__meta-item" style="color:var(--accent-light);text-decoration:none;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        View Certificate
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="training-detail__content">
            @if(!empty($training->long_description))
                {!! nl2br(e($training->long_description)) !!}
            @elseif(!empty($training->description))
                <p>{{ $training->description }}</p>
            @else
                <p>No additional details available.</p>
            @endif
        </div>

        @if($related->isNotEmpty())
        <div style="margin-top:48px;">
            <h2 style="font-family:'Poppins',sans-serif;font-size:24px;font-weight:700;margin-bottom:24px;">Related Training</h2>
            <div class="training-grid">
                @foreach($related as $item)
                <article class="training-card">
                    @if(!empty($item->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                    <a href="{{ $item->publicUrl() }}" class="training-card__img">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}">
                    </a>
                    @endif
                    <div class="training-card__body">
                        @if($item->yearRange())
                        <span class="training-card__date">{{ $item->yearRange() }}</span>
                        @endif
                        <h3 class="training-card__title">
                            <a href="{{ $item->publicUrl() }}">{{ $item->title }}</a>
                        </h3>
                        @if(!empty($item->organization))
                        <span class="training-card__org">{{ $item->organization }}</span>
                        @endif
                        @if(!empty($item->description))
                        <p class="training-card__desc">{{ $item->description }}</p>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
