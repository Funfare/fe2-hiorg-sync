<tr>
    <td><a href="{{ route('impersonate.user', $user) }}">{{ $user->name }}</a></td>
    <td>{{ $user->email }}</td>
    <td><select wire:model.live="admin" class="form-control">
            <option value="0">Benutzer</option>
            <option value="1">Admin</option>
        </select></td>
    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
    <td>{{ $user->last_action_at?->format('d.m.Y H:i:s') }}</td>
</tr>
