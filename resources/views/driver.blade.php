@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-dark">
                <div class="card-header bg-yellow">
                    <a href="{{ route('kierowcy') }}" class="text-white">Kierowcy</a> :
                    @if($user->profile->show_name){{ $user->profile->name }}@endif
                    @if($user->profile->show_lastname){{ $user->profile->lastname }}@endif
                    @if(!$user->profile->show_lastname && !$user->profile->show_name) Anonim @endif
                    
                    @auth
                        @if(auth()->user()->team_admin() && !$user->team())
                            <button class="btn btn-sm btn-info float-right" data-toggle="modal" data-target="#sendRequest">Zaproś do swojego Teamu</button>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                        <div class="col-sm-12">
                            <div class="row shadow p-3 bg-white">
                                <div class="col-md-3">
                                    @if($user->profile->file_id)
                                        <img src="{{ url('/public/driver', $user->profile->file->path) }}" class="img-fluid">
                                    @else
                                        <img src="{{ url('/images/driver.png') }}" class="img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-uppercase">
                                        @if($user->profile->show_name){{ $user->profile->name }}@endif
                                        @if($user->profile->show_lastname){{ $user->profile->lastname }}@endif
                                        @if(!$user->profile->show_lastname && !$user->profile->show_name) Anonim @endif
                                    </h3>
                                    <p>@if($user->profile->show_email){{ $user->email }}@endif</p>
                                    @if($user->team())
                                        <p><strong>Team: <a href="{{ route('team', $user->team()->id) }}">{{ $user->team()->title }}</a></strong></p>
                                    @endif
                                    <strong>O mnie:</strong>
                                    {!! $user->profile->desc !!}
                                </div>
                                <div class="col-md-3">
                                    @if($user->laurel_place(1)->count() || $user->laurel_place(2)->count() || $user->laurel_place(3)->count())
                                        <h6 class="text-center">Laury</h6>
                                    @endif
                                    @if($user->laurel_place(1)->count())
                                        <div class="mb-1">
                                            <p class="m-0 laurel gold">{{ $user->laurel_place(1)->count() }}</p>
                                            @php
                                                $klasa = '';
                                                $show = true;
                                            @endphp
                                            @foreach($user->laurel_place(1)->get() as $laurel)
                                                @php
                                                    if($laurel->klasa != $klasa){
                                                        $klasa = $laurel->klasa;
                                                        $show = true;
                                                    }
                                                    else
                                                        $show = false;
                                                @endphp
                                                @if($show)
                                                    <p class="m-0"></p>
                                                    <strong class="inline-block">{{ $klasa }}</strong>
                                                @endif
                                                    <span>{{ $laurel->year }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($user->laurel_place(2)->count())
                                        <div class="mb-1">
                                            <p class="m-0 laurel silver">{{ $user->laurel_place(2)->count() }}</p>
                                            @php
                                                $klasa = '';
                                                $show = true;
                                            @endphp
                                            @foreach($user->laurel_place(2)->get() as $laurel)
                                                @php
                                                    if($laurel->klasa != $klasa){
                                                        $klasa = $laurel->klasa;
                                                        $show = true;
                                                    }
                                                    else
                                                        $show = false;
                                                @endphp
                                                @if($show)
                                                    <p class="m-0"></p>
                                                    <strong class="inline-block">{{ $klasa }}</strong>
                                                @endif
                                                    <span>{{ $laurel->year }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($user->laurel_place(3)->count())
                                        <div class="mb-1">
                                            <p class="m-0 laurel brown">{{ $user->laurel_place(3)->count() }}</p>
                                            @php
                                                $klasa = '';
                                                $show = true;
                                            @endphp
                                            @foreach($user->laurel_place(3)->get() as $laurel)
                                                @php
                                                    if($laurel->klasa != $klasa){
                                                        $klasa = $laurel->klasa;
                                                        $show = true;
                                                    }
                                                    else
                                                        $show = false;
                                                @endphp
                                                @if($show)
                                                    <p class="m-0"></p>
                                                    <strong class="inline-block">{{ $klasa }}</strong>
                                                @endif
                                                    <span>{{ $laurel->year }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-lg-6 mt-4">
                                    <div class="card border-0 shadow">
                                        <div class="card-header bg-yellow">
                                            Piloci
                                        </div>
                                        <div class="card-body pilots">
                                            @if($user->pilots->count())
                                                @foreach($user->pilots as $pilot)
                                                    <div class="d-flex justify-content-start align-items-center flex-wrap py-2">
                                                        <div class="col-md-5">
                                                            @if($pilot->file_id)
                                                                <img src="{{ url('/public/pilot', $pilot->file->path) }}" class="img-fluid">
                                                            @else
                                                                <img src="{{ url('/images/driver.png') }}" class="img-fluid">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-7">
                                                            @if($pilot->show_name){{ $pilot->name }}@endif
                                                            @if($pilot->show_lastname){{ $pilot->lastname }}@endif
                                                            @if(!$pilot->show_lastname && !$pilot->show_name) Anonim @endif
                                                            <p>@if($pilot->show_email){{ $pilot->email }}@endif</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-lg-12 mt-4">
                                    <div class="card border-0 shadow">
                                        <div class="card-header bg-yellow">
                                            Samochody
                                        </div>
                                        <div class="card-body d-flex flex-wrap align-items-start">
                                            @if($user->cars->count())
                                                @foreach($user->cars as $car)
                                                    <div class="col-6 d-flex justify-content-start align-items-center flex-wrap py-2">
                                                        <div class="col-md-5">
                                                            @if($car->file_id)
                                                                <img src="{{ url('/public/car/thumb/', $car->file->path) }}" class="img-fluid">
                                                            @else
                                                                <img src="{{ url('/images/car.png') }}" class="img-fluid">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-7">
                                                            <h5>
                                                                {{ $car->marka }} {{ $car->model }}
                                                                <br><small>{{ $car->ccm }}ccm</small>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow mt-4">
                                <div class="card-header bg-yellow">
                                    Rajdy
                                </div>
                                <div class="card-body lista p-0">
                                    @if($user->races->count())
                                        @foreach($user->races as $race)
                                        <div class="row d-flex align-items-center justify-content-between flex-wrap m-0 py-3">
                                            <h6 class="col-md-4 m-0">
                                                {{ $race->startList->round->name }} @if($race->startList->round->sub_name) - {{ $race->startList->round->sub_name }}@endif<br>
                                                <small>{{ $race->startList->round->race->name }}</small></h5>
                                            </h6>
                                            <h6 class="col-md-3 m-0">
                                                {{ $race->sign->name }} {{ $race->sign->lastname }}<br>
                                                @if($race->sign->pilot)
                                                    <small><strong>Pilot:</strong>
                                                    @if($race->sign->pilot->profile->show_name && $race->sign->pilot->profile->show_lastname)
                                                        <a href="{{ route('pilot', [$race->sign->pilot->id, str_slug($race->sign->pilot->profile->name.'-'.$race->sign->pilot->profile->lastname)]) }}">
                                                    @elseif($race->sign->pilot->profile->show_lastname)
                                                        <a href="{{ route('pilot', [$race->sign->pilot->id, $race->sign->pilot->profile->lastname]) }}">
                                                    @else
                                                        <a href="{{ route('pilot', $race->sign->pilot->id) }}">
                                                    @endif
                                                        {{ $race->sign->pilot_name }} {{ $race->sign->pilot_lastname }}
                                                    </a></small>
                                                @else
                                                    <small><strong>Pilot:</strong> {{ $race->sign->pilot_name }} {{ $race->sign->pilot_lastname }}</small>
                                                @endif
                                            </h6>
                                            <h6 class="col-md-3 m-0">
                                                {{ $race->sign->marka }} {{ $race->sign->model }}<br>
                                                <small>{{ $race->sign->ccm }}ccm</small>
                                            </h6>
                                            <h6 class="col-md-2 m-0">
                                                Miejsce: {{ $race->rank() }}
                                            </h6>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
    @if(auth()->user()->team_admin() && !$user->team())
        @include('modals.sendRequest')
    @endif
@endauth
@endsection