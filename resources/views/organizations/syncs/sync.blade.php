<p>
    <b>Typ: Helfersynchronisierung</b><br/>
    {{ count($sync->data['personList']) }} Personen synchronisiert
</p>

<table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Funktionen</th>
        <th>Alarmgruppen</th>
        <th>aPager Adresse</th>
    </tr>
    @foreach($sync->data['personList'] as $user)
        <tr>
            <td>{{ $user['firstName'] }} {{ $user['lastName'] }}</td>
            <td>{{ implode(', ',$user['osFunctions']) }}</td>
            <td>{{ implode(', ',$user['alarmGroups']) }}</td>
            <td>
                {{ $user['aPagerPro'] }}
            </td>
        </tr>
    @endforeach
</table>
