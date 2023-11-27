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
    <h4>Provisionierung (ab 02.02.2024)</h4>
    <p>
    Ab dem 02.02.2024 kannst du direkt hier eine neue Aktivierungsmail anfordern, solltest du ein neues Handy haben oder die Konfiguration erneut benötigen.
    {{--@env('local')
    <a href="{{ route('me.prov') }}">Um eine neue Provisionierung (Aktivierungsmail) zu erhalten, klicke bitte hier</a>
    @endenv--}}
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
