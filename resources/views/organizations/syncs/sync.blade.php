<p>
    <b>Typ: Helfersynchronisierung</b><br/>
    {{ count($sync->data['personList']) }} Personen synchronisiert
</p>

<table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>OS Funktionen</th>
        <th>OS Gruppen</th>
        <th>Alarmgruppen</th>
        <th>aPager Adresse</th>
    </tr>
    @foreach($sync->data['personList'] as $user)
        <tr>
            <td>{{ $user['firstName'] }} {{ $user['lastName'] }}</td>
            <td>@if(is_array($user['osFunctions']))
                    {{ implode(', ',$user['osFunctions']) }}
                @else
                    {{ $user['osFunctions'] }}
                @endif
            </td>
            <td>@if(is_array($user['osGroups']))
                    {{ implode(', ',$user['osGroups']) }}
                @else
                    {{ $user['osGroups'] }}
                @endif
            </td>
            <td>
                @if(is_array($user['alarmGroups']))
                    {{ implode(', ',$user['alarmGroups']) }}
                @else
                    {{ $user['alarmGroups'] }}
                @endif
            </td>
            <td>
                {{ $user['aPagerPro'] }}
            </td>
        </tr>
    @endforeach
</table>
