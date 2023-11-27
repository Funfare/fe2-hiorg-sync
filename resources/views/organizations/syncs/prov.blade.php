<p>
    <b>Typ: Provisionierung</b><br/>
    {{ count($sync->data) }} Personen provisioniert
</p>

<table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Grund</th>
        <th>zugewiesene Provisionierung</th>
    </tr>
    @foreach($sync->data as $user)
        <tr>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['reason'] }}</td>
            <td>
                @if($user['provision'] === $org->fe2_provisioning_user)
                    Helfer
                @elseif($user['provision'] === $org->fe2_provisioning_leader)
                    Führung
                @else
                    Unbekannt
                @endif
            </td>
        </tr>
    @endforeach
</table>
