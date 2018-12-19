@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-dark">
                <div class="card-header bg-yellow">
                    Polityka prywatności
                </div>
                <div class="card-body">
                    @if($policy)
                        {!! $policy->value !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection