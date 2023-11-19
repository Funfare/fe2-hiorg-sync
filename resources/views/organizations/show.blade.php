@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>

    <p>
    Hallo {{ Auth::user()->name }},<br />
        der nächste Abgleich der Daten zwischen Hiorg-Server und Fe2 ist am {{ today()->addDay()->format('d.m.Y') }} um 01:00 Uhr. <br /> <br />
        Hier kannst du sehen, welche Daten von dir synchronisiert werden: Link zu bla <br /><br />


    </p>
    <p>
    <h4>Provisionierung</h4>
    Um eine neue Provisionierung (Aktivierungsmail) zu erhalten, klicke bitte hier
    </p>
    @if(Auth::user()->is_admin)
    <h3>Letzte Synchronisationen</h3>


    @endif
@endsection
