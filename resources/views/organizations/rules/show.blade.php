@extends('layouts.master')

@section('content')
    <div>
        <a href="{{ route('rules.preview') }}" class="btn btn-outline-primary">Vorschau</a>
    </div>
    <livewire:rules :$org :$tab></livewire:rules>

@endsection
