@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-dark">
                <div class="card-header bg-yellow">
                    Wyniki
                </div>
                <div class="card-body">
                    @foreach($races as $race)
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h3>{{ $race->name }}</h3>
                            <div>
                                <a href="{{ url('rank', $race->id) }}" class="btn btn-success">Klasyfikacja Roczna</a>
                                <a href="{{ url('team_rank', $race->id) }}" class="btn btn-info">Klasyfikacja Teamów</a>
                            </div>
                        </div>
                        <div>
                            @foreach($race->rounds as $round)
                                @if($round->startList)
                                    <p class="h5 py-1"><a href="{{ url('runda', $round->id) }}">{{ $round->name }} @if($round->sub_name) - {{ $round->sub_name }}@endif</a></p>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                    @endforeach
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3>Rajdowy Puchar Śląska 2018</h3>
                        <div>
                            <a href="{{ url('rank2018') }}" class="btn btn-success">Klasyfikacja Roczna</a>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R1">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 1">
                            <button type="submit" class="btn btn-link">Runda 1</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R2">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 2">
                            <button type="submit" class="btn btn-link">Runda 2</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R3">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 3">
                            <button type="submit" class="btn btn-link">Runda 3</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R4">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 4">
                            <button type="submit" class="btn btn-link">Runda 4</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R5">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 5">
                            <button type="submit" class="btn btn-link">Runda 5</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps18/R6">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2018 - Runda 6">
                            <button type="submit" class="btn btn-link">Runda 6</button>
                        </form>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3>Rajdowy Puchar Śląska 2017</h3>
                        <div>
                            <a href="{{ url('rank2017') }}" class="btn btn-success">Klasyfikacja Roczna</a>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r1/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 1">
                            <button type="submit" class="btn btn-link">Runda 1</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r2/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 2">
                            <button type="submit" class="btn btn-link">Runda 2</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r3/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 3">
                            <button type="submit" class="btn btn-link">Runda 3</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r4/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 4">
                            <button type="submit" class="btn btn-link">Runda 4</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r5/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 5">
                            <button type="submit" class="btn btn-link">Runda 5</button>
                        </form>
                        <form action="{{ route('rank_frame') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="wyniki.stcs.pl/rps17/r6/">
                            <input type="hidden" name="name" value="Rajdowy Puchar Śląska 2017 - Runda 6">
                            <button type="submit" class="btn btn-link">Runda 6</button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3>Rajdowy Puchar Śląska 2016</h3>
                        <div>
                            <a href="{{ url('rank2016') }}" class="btn btn-success">Klasyfikacja Roczna</a>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection