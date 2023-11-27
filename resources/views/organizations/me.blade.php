@extends('layouts.master')

@section('content')
    <h2>{{ Auth::user()->organization->name }}</h2>

    <a href="{{ route('home') }}" class="btn btn-secondary pb-2">Zurück</a>
    @if(!$valid)
        <div class="alert alert-danger" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                 aria-label="Warning:">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            Es erfolgt keine Synchronisierung zum aPager und keine Alarmierung!
        </div>
        <b>Bitte überprüfe deine Zugehörgkeit zur Gruppe "Handyalarmierung" und dein Geburtsdatum</b>
    @else
<p>
    Diese Daten werden automatisch übertragen. <br />
    Fehler können über <a href="https://www.4juh.de/workspaces/sanitaetsbereitschaft-wuerzburg/apps/form/zugaenge-edv-programme" target="_blank">4JUH Zugänge IT/Programme</a> gemeldet werden.
</p>
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
    @endif
    <p class="pt-3">Diese Daten werden für eine Stunde zwischengespeichert, eine Änderung im Hiorg-Server ist erst mit Verzögerung
        sichtbar</p>
@endsection
