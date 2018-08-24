@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="news-container">
        <h3>News</h3>
        <div class="row justify-content-start">
            @foreach($news as $post)
                <div class="col-sm-3">
                    <div class="news"> 
                        @if($post->file_id)
                            <img src="{{ url('public/post', $post->file->path) }}" class="img-fluid">
                        @endif
                        <div class="news-body">
                            <p>{{ str_limit(strip_tags($post->text), 100) }}</p>

                            <a href="{{ url('aktualnosc', $post->id) }}">więcej</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="partners-container">
        <h3>Partnerzy</h3>
        <div class="owl-carousel owl-theme mt-3">
            @foreach($partners as $partner)
                <div class="partner">
                    @if($partner->url)
                        <a href="{{ $partner->url }}" target="_blank" rel="nofollow"><img src="{{ url('public/partner', $partner->file->path) }}"></a>
                    @else
                        <img src="{{ url('public/partner', $partner->file->path) }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@if($promoted)
    @include('modals.randomPartner');
@endif
@endsection