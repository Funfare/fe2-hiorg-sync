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
<x-input id="fe2_provisioning_user" value="{{old('fe2_provisioning_user', $org->fe2_provisioning_user)}}" name="fe2_provisioning_user">ID Provisionierung Helfer</x-input>
<x-input id="fe2_provisioning_leader" value="{{old('fe2_provisioning_leader', $org->fe2_provisioning_leader)}}" name="fe2_provisioning_leader">ID Provisionierung FÜhrung</x-input>
<button type="submit">Speichern</button>
</form>

@endsection
