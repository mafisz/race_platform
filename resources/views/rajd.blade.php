@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-dark">
                <div class="card-header bg-yellow d-flex justify-content-between flex-wrap">
                    <p class="col-12 col-md-6 m-0">
                        {{ $round->race->name}} : {{ $round->name }} {{ $round->sub_name}}
                    </p>
                    <div class="col-12 col-md-6 text-md-right m-0">
                        <label class="m-0 mr-2">Data rajdu:</label>{{ $round->date->format('Y-m-d H:i') }}<br>
                        <label class="m-0 mr-2">Start zapisów:</label>{{ $round->sign_date->format('Y-m-d H:i') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="col-12 col-md-4">
                            @if($round->poster_id)
                                @if (in_array(pathinfo($round->poster->path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <a href="{{ url('public/posters', $round->poster->path) }}" data-lightbox="poster" data-title="{{ $round->sub_name}}" class="mb-2 d-block">
                                        <img src="{{ url('public/posters', $round->poster->path) }}" class="img-fluid">
                                    </a>
                                @else
                                    <div class="text-center mb-2">
                                        <a href="{{ url('public/posters', $round->poster->path) }}" class="btn btn-sm btn-danger" target="_blank">Zobacz plakat</a>
                                    </div>
                                @endif
                            @endif

                            @if($round->map_id)
                                @if (in_array(pathinfo($round->map->path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <a href="{{ url('public/maps', $round->map->path) }}" data-lightbox="map" data-title="{{ $round->sub_name}}" class="mb-2 d-block">
                                        <img src="{{ url('public/maps', $round->map->path) }}" class="img-fluid">
                                    </a>
                                @else
                                    <div class="text-center mb-2">
                                        <a href="{{ url('public/maps', $round->map->path) }}" class="btn btn-sm btn-info" target="_blank">Zobacz mapę</a>
                                    </div>
                                @endif
                            @endif

                            <div class="text-center">
                                @if($round->file_id)
                                    <a href="{{ url('public/terms', $round->file->path) }}" class="btn btn-sm btn-secondary" target="_blank">Regulamin</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="float-right mb-2"> 
                                @if($round->startList)
                                    <a href="{{ url('runda', $round->id) }}" class="btn btn-sm btn-outline-success">Zobacz wyniki</a>
                                @endif

                                @if($round->form->visible)
                                    <a href="{{ url('sign-list', $round->id) }}" class="btn btn-sm btn-outline-primary">Zobacz listę zgłoszeń</a>
                                @endif

                                @if($round->form->active)
                                    <a href="{{ url('dashboard') }}" class="btn btn-sm btn-primary">Zapisz się</a>
                                @endif
                            </div>
                            @if($round->desc)
                                <h5>Opis rundy:</h5>
                                {!! $round->desc !!}
                            @endif

                            @if($round->serwis)
                                <h5 class="mt-3">Serwis i biuro rajdu:</h5>
                                {!! $round->serwis !!}
                            @endif

                            @if($round->length || $round->special_length || $round->driveway_length)
                                <h5 class="mt-3">Szczegóły:</h5>
                                <div class="d-flex flex-wrap justify-content-between text-center pt-2"> 
                                    @if($round->length)
                                        <div class="col-12 col-md-4">
                                            <h6>Długość rajdu</h6>
                                            <h5>{{ $round->length }}</h5>
                                        </div>
                                    @endif
                                    @if($round->special_length)
                                        <div class="col-12 col-md-4">
                                            <h6>Całkowita długość odcinków specjalnych</h6>
                                            <h5>{{ $round->special_length }}</h5>
                                        </div>
                                    @endif
                                    @if($round->driveway_length)
                                        <div class="col-12 col-md-4">
                                            <h6>Długość dojazdówek</h6>
                                            <h5>{{ $round->driveway_length }}</h5>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($round->price)
                                <h5>Wpisowe: {{ $round->price }}</h5>
                            @endif
                            @if($round->advance)
                                <h5>Zaliczka: {{ $round->advance }}</h5>
                            @endif

                            @if($round->sections->count())
                                <h5 class="my-3">Odcinki specjalne:</h5>
                                <div class="sections lista"> 
                                    @foreach($round->sections as $section)
                                        <div class="row d-flex flex-wrap align-items-center p-2">
                                            <h6 class="m-0 col-3">{{ $section->name }}</h6>
                                            @if($section->length)
                                                <h6 class="m-0 col-6">Długość odcinka: {{ $section->length }}</h6>
                                            @endif
                                            @if($section->map_id)
                                                @if (in_array(pathinfo($section->map->path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                    <a href="{{ url('public/maps', $section->map->path) }}" data-lightbox="section" data-title="{{ $section->name }}" class="d-block col-3">
                                                        Zobacz mapę
                                                    </a>
                                                @else
                                                    <a href="{{ url('public/maps', $section->map->path) }}" class="d-block col-3" target="_blank">
                                                        Zobacz mapę
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="col-sm-6 mx-auto text-center mt-4"> 
                                @if($round->form->active)
                                    <a href="{{ url('dashboard') }}" class="btn btn-block btn-sm btn-primary">Zapisz się</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection