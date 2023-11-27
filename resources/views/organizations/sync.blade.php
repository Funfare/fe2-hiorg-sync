@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>
    <h3>Details Synchronisation</h3>
    <p>
        Uhrzeit: {{$sync->created_at->format('d.m.Y H:i:s')}}
    </p>
    <a class="btn btn-secondary py-1" href="{{ route('home') }}">Zurück</a>
    @include('organizations.syncs.'.$sync->type)

@endsection
