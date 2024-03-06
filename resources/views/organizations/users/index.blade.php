@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>
    <h3>Vorhandene Benutzer</h3>


    <table class="table table-striped">
        <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Berechtigung</th>
            <th>Erster Login</th>
            <th>Letzter Aufruf</th>
        </tr>
        @foreach($users as $user)
            <livewire:edit-user :$user :key="$user->id"></livewire:edit-user>
        @endforeach
    </table>
    {{$users->links()}}

@endsection
