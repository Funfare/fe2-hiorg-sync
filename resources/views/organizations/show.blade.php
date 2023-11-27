@extends('layouts.master')

@section('content')
    <h2>{{ $org->name }} ({{$org->key}})</h2>

    <p>
    Hallo {{ Auth::user()->name }},<br />
        der nächste Abgleich der Daten zwischen Hiorg-Server und aPager ist am
        @if(today() < \Carbon\Carbon::parse('2024-02-01'))
            01.02.2024
        @else
        {{ today()->addDay()->format('d.m.Y') }}
            @endif
        um 01:00 Uhr. <br /> <br />
        <a href="{{ route('me') }}">Hier kannst du sehen, welche Daten von dir synchronisiert werden</a><br /><br />

    </p>
    <p>
    <h4>Provisionierung</h4>
    <a href="{{ route('me.prov') }}">Um eine neue Provisionierung (Aktivierungsmail) zu erhalten, klicke bitte hier</a>
    </p>
    @can('admin')
    <h3>Letzte Synchronisationen</h3>
    <table class="table table-striped">
        <tr>
        <th>Datum</th>
        <th>Typ</th>
        <th>Anzahl</th>
            <th></th>
        </tr>
        @forelse($org->syncs as $sync)
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
    @endcan
@endsection
