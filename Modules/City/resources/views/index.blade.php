@extends('city::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('city.name') !!}</p>
@endsection
