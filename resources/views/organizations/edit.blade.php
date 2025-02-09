@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>
    <p> Einstellungen bearbeiten:
</p>


<form class="form" method="POST">
     {{ csrf_field() }}






<x-input id="fe2_link" value="{{old('fe2_link', $org->fe2_link)}}" name="fe2_link">FE2 Link</x-input>
<x-input id="fe2_sync_token" value="{{old('fe2_sync_token', $org->fe2_sync_token)}}" name="fe2_sync_token">FE2 Sync Token</x-input>
<x-input id="fe2_user" value="{{old('fe2_user', $org->fe2_user)}}" name="fe2_user">FE2 Benutzer der Organisation</x-input>
<x-input id="fe2_pass" value="{{old('fe2_pass', $org->fe2_pass)}}" name="fe2_pass">Passwort</x-input>
<button type="submit">Speichern</button>
</form>

    <h3>Hiorg Admin User</h3>
    <p>
        @if($org->hiorg_user)
            Name: {{ $org->hiorg_user }}
        @else
            Admin noch nicht gesetzt
        @endif
        <a href="{{route('settings.setAdmin')}}" class="btn btn-primary">Neuen Administrator festlegen</a>
    </p>
    <h3>Manueller Sync</h3>
    <a href="{{ route('settings.sync') }}" class="btn btn-danger">Manuellen Sync durchführen</a>

@endsection
