<p>
    <b>Typ: Alarm</b><br/>
    {{ count($sync->data) }} Alarm ausgelöst
</p>

<table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Grund</th>
        <th>Alarmtyp</th>
    </tr>
    @foreach($sync->data as $user)
        <tr>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['reason'] }}</td>
            <td>
                @if($user['alarm'] === 'info')
                    Info-Alarm
                @else
                    Lauter Alarm
                @endif
            </td>
        </tr>
    @endforeach
</table>
