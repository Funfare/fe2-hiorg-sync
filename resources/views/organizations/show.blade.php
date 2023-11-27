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
                <td colspan="4">Noch keine Synchronisatino durchgeführt</td>
            </tr>
        @endforelse
    </table>
    @endif
@endsection
