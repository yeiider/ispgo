@extends('layouts.email')

@section('title', 'Bienvenido a Wish')

@section('styles')

@endsection

@section('content')
    <h2>Hola {{ $customer->full_name }},</h2>
    <p>¡Listo! Empieza a comprar con millones de usuarios que están aprovechando del 50-80% menos de lo que pagarías
        en tu centro comercial por artículos de moda.</p>
    <p>¡Compra con confianza con nuestra garantía de satisfacción total!</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/shop') }}" class="button">Ir de compras</a>
    </div>
    <div style="text-align: center;">
        <img src="{{ asset('img/logo.png') }}" alt="Shopping Cart with Hearts" style="max-width: 100px;">
    </div>
@endsection
