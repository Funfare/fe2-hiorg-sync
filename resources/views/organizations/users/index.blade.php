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

            <tr>
                <td><a href="{{ route('impersonate.user', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Admin' : 'Benutzer' }}</td>
                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $user->last_action_at?->format('d.m.Y H:i:s') }}</td>
            </tr>
            @endforeach
    </table>
    {{$users->links()}}

@endsection
