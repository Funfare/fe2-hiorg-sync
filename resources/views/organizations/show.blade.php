@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>

    <p>
    Hallo {{ Auth::user()->name }},<br />
        der nächste Abgleich der Daten zwischen Hiorg-Server und aPager ist am
        @if(today() < \Carbon\Carbon::parse('2024-02-01'))
            01.02.2024 um 19:00 Uhr.
        @else
        {{ today()->addDay()->format('d.m.Y') }} um 01:00 Uhr.
            @endif
         <br /> <br />
        <a href="{{ route('me') }}">Hier kannst du sehen, welche Daten von dir synchronisiert werden</a><br /><br />

    </p>
    @if($valid)
        <h4>Provisionierung</h4>
        <p>
        Hier kannst du eine neue Provisionierung (Aktivierungsmail) anfordern, wenn du die aPager App neu installiert oder ein neues Handy hast. <br />
        <a class="btn btn-primary" href="{{ route('me.prov') }}">Um eine neue Provisionierung (Aktivierungsmail) zu erhalten, klicke bitte hier</a>
        </p>
        <h4>Probealarm</h4>
        <p>
        Hier kannst du einen Probealarm für dein Handy auslösen, um die Funktion des aPagers zu testen. <br />
        <a class="btn btn-danger pr-2" href="{{ route('me.alarm', 'alarm') }}">Lauter Alarm</a>
        <a class="btn btn-warning" href="{{ route('me.alarm', 'info') }}">Info Alarm</a> <br />
        </p>
    @else
        <div class="alert alert-danger" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                 aria-label="Warning:">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            Es erfolgt derzeit keine Synchronisierung zum aPager und keine Alarmierung!
        </div>
    @endif
    @can('admin')
    <h3>Letzte Synchronisationen</h3>
    <table class="table table-striped">
        <tr>
        <th>Datum</th>
        <th>Typ</th>
        <th>Anzahl</th>
            <th></th>
        </tr>
        @forelse($syncs as $sync)
            <tr>
                <td>
                    {{ $sync->created_at->format('d.m.Y H:i') }}
                </td>
                <td>
                    {{ $sync->type == 'sync' ? 'Personal synchronisiert' : 'Provisionierung aktualisiert' }}
                </td>
                <td>
                    @if($sync->type == 'sync')
                        {{ count($sync->data['personList']) }} Personen synchronisiert
                    @elseif($sync->type == 'prov')
                        {{ count($sync->data) }} Provisionierungen aktualisiert
                    @elseif($sync->type == 'alarm')
                        {{ count($sync->data) }} Alarm ausgelöst
                    @endif
                </td>
                <td>
                    <a href="{{ route('sync', $sync) }}">Details</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Noch keine Synchronisation durchgeführt</td>
            </tr>
        @endforelse

    </table>
    {{ $syncs->links() }}
    @endcan
@endsection
