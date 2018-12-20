@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('partials._tenant_sidebar')
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        @card
                        @slot('card_style', 'mb-3')
                        @slot('header', 'Todos los proveedores')
                        <h1>{{ $providers_count }}</h1>
                        @endcard
                    </div>
                    <div class="col-md-6">
                        @card
                        @slot('card_style', 'mb-3')
                        @slot('header', 'Todos los productos')
                        <h1>{{ $products_count }}</h1>
                        @endcard
                    </div>
                    <div class="col-md-6">
                        @card
                        @slot('card_style', 'mb-3')
                        @slot('header', 'Nuevas compras')
                        <h1>{{ $purchases_count }}</h1>
                        @endcard
                    </div>
                    <div class="col-md-6">
                        @card
                        @slot('header', 'Nuevas transacciónes')
                        <h1>{{ $transactions_count }}</h1>
                        @endcard
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection