@extends('layouts.email')

@section('title', 'Bienvenido a Wish')

@section('styles')
    {!! $styles !!}

@endsection

@section('content')
    @if($img_header)
        <div class="hero" style="background-image: url({{$img_header}})"></div>
    @endif
    {!! $content !!}
@endsection
