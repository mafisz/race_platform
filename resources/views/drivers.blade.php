@extends('layouts.app')

@section('content')
<div class="drivers-container">
    <h3>Kierowcy</h3>
    <div class="container">
        <div class="row justify-content-center">
            @foreach($users as $user)
                @if($user->driver)
                    <div class="col-md-4 col-lg-4 col-xl-3">
                        <div class="driver">
                            <a href="{{ url('kierowca', $user->id) }}">
                            @if($user->driver->file_id)
                                <img src="{{ url('/public/driver', $user->driver->file->path) }}" class="img-fluid">
                            @else
                                <img src="{{ url('/images/driver.png') }}" class="img-fluid">
                            @endif
                            <h6 class="my-3">
                                    @if($user->driver->show_name){{ $user->driver->name }}@endif
                                    @if($user->driver->show_lastname){{ $user->driver->lastname }}@endif
                                    @if(!$user->driver->show_lastname && !$user->driver->show_name) Anonim @endif
                            </h6>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection