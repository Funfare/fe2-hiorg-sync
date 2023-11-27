@extends('layouts.master')

@section('content')
    <h2>{{ Auth::user()->organization->name }}</h2>

    @if(!$valid)
        <div class="alert alert-danger" role="alert">Es erfolgt keine Synchronisierung zum aPager! <br />
        Prüfe die Zugehörigkeit zur Gruppe "Handyalarmierung" und dein Geburtsdatum</div> <br /> <br />
        <b>Nach der Korrektur</b> sind folgende Daten für dich hinterlegt:
    @else
        Diese Daten werden übertragen:
    @endif
    <table class="table table-striped">
        <tr>
            <td>Name</td>
            <td> {{ $fe2['firstName'] }} {{ $fe2['lastName'] }}</td>
        </tr>
        <tr>
            <td>E-Mail</td>
            <td> {{ $fe2['email'] }}</td>
        </tr>
        <tr>
            <td>aPagerPro Adresse</td>
            <td> {{ $fe2['aPagerPro'] }}</td>
        </tr>
        <tr>
            <td>Telefonnummer</td>
            <td> {{ $fe2['mobil'] }}</td>
        </tr>
        <tr>
            <td>Bemerkung</td>
            <td> {{ $fe2['note'] }}</td>
        </tr>
        <tr>
            <td>Funktionen</td>
            <td> {{ implode(', ', $fe2['osFunctions']) }}</td>
        </tr>
        <tr>
            <td>Alarmgruppen</td>
            <td> {{ implode(', ', $fe2['alarmGroups']) }}</td>
        </tr>
    </table>
    <p>Diese Daten werden für eine Stunde zwischengespeichert, eine Änderung im Hiorg-Server ist erst mit Verzögerung hier ersichtlich</p>
@endsection
