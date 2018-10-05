@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center overflow-auto">
        <div class="col-md-12">
            <div class="card border-dark fixed-xs-width">
                <div class="card-header bg-yellow text-center">
                    <h3>{{ $round->race->name }}</a> : {{ $round->name }} - Lista zgłoszeń</h3>
                </div>
                <div class="card-body">
                    @php
                        $numer = 0;
                    @endphp
                    @foreach($klasy as $klasa)
                        <h2 class="text-center mt-4 mb-3 text-uppercase">..:: {{ $klasa }} ::..</h2>
                        <div class="lista">
                            @foreach($class[$klasa] as $sign)
                                <div class="row justify-content-between align-items-center flex-wrap py-2">
                                    <h6 class="m-0 col-1">
                                        {{ ++$numer }}.
                                    </h6>
                                    <div class="col-1">
                                        @if($sign['sign']->user && $sign['sign']->user->profile->file_id)
                                            <img src="{{ url('public/driver/thumb/', $sign['sign']->user->profile->file->path) }}" class="img-fluid thumb">
                                        @else
                                            <img src="{{ url('images/driver.png') }}" class="img-fluid thumb">
                                        @endif
                                    </div>
                                    <h6 class="m-0 col-5 text-left">
                                        @if($sign['sign']->user)
                                            @if($sign['sign']->user->profile->show_name && $sign['sign']->user->profile->show_lastname)
                                                <a href="{{ route('kierowca', [$sign['sign']->user->id, str_slug($sign['sign']->user->profile->name.'-'.$sign['sign']->user->profile->lastname)]) }}">
                                            @elseif($sign['sign']->user->profile->show_lastname)
                                                <a href="{{ route('kierowca', [$sign['sign']->user->id, $sign['sign']->user->profile->lastname]) }}">
                                            @else
                                                <a href="{{ route('kierowca', $sign['sign']->user->id) }}">
                                            @endif
                                                {{ $sign['sign']->name }} {{ $sign['sign']->lastname }}
                                            </a>
                                        @else
                                            {{ $sign['sign']->name }} {{ $sign['sign']->lastname }}
                                        @endif
                                        <br>
                                        @if($sign['sign']->pilot)
                                            <small><strong>Pilot:</strong>
                                            @if($sign['sign']->pilot->profile->show_name && $sign['sign']->pilot->profile->show_lastname)
                                                <a href="{{ route('pilot', [$sign['sign']->pilot->id, str_slug($sign['sign']->pilot->profile->name.'-'.$sign['sign']->pilot->profile->lastname)]) }}">
                                            @elseif($sign['sign']->pilot->profile->show_lastname)
                                                <a href="{{ route('pilot', [$sign['sign']->pilot->id, $sign['sign']->pilot->profile->lastname]) }}">
                                            @else
                                                <a href="{{ route('pilot', $sign['sign']->pilot->id) }}">
                                            @endif
                                                {{ $sign['sign']->pilot_name }} {{ $sign['sign']->pilot_lastname }}
                                            </a></small>
                                        @else
                                            <small><strong>Pilot:</strong> {{ $sign['sign']->pilot_name }} {{ $sign['sign']->pilot_lastname }}</small>
                                        @endif
                                    </h6>
                                    <div class="col-2">
                                        @if($sign['sign']->car && $sign['sign']->car->file_id)
                                            <img src="{{ url('public/car/thumb/', $sign['sign']->car->file->path) }}" class="img-fluid thumb">
                                        @else
                                            <img src="{{ url('images/car.png') }}" class="img-fluid thumb">
                                        @endif
                                    </div>
                                    <h6 class="m-0 col-3">
                                        {{ $sign['sign']->marka }} {{ $sign['sign']->model }} - {{ $sign['sign']->ccm }}ccm<br>
                                        <small>{{ $sign['sign']->rok }}r. @if($sign['sign']->turbo) / <strong>Turbo</strong> @endif @if($sign['sign']->rwd) / <strong>RWD</strong> @endif</small>
                                    </h6>
                                </div>  
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection