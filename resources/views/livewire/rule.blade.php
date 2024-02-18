<li class="list-group-item">
    <div>
        Datenfeld:
        <select wire:model.live="source_field_id" class="form-control-sm">
            @foreach(\App\Models\SourceField::all() as $field)
                <option value="{{ $field->id }}">{{ $field->name }}</option>
            @endforeach
        </select>
        <div class="form-check form-check-inline">
            <input class="form-check-input" wire:model="not" type="checkbox" value="1" id="notCheckbox{{$rule->id}}">
            <label class="form-check-label" for="notCheckbox{{$rule->id}}">
                Nicht
            </label>
        </div>
        <select wire:model="compare_class" class="form-control-sm">
            <option value="IsEqual">=</option>
            <option value="InArray">Enthält in Liste</option>
            <option value="InString">Enthält in Text</option>
            <option value="IsEmpty">Ist leer</option>
            <option value="IsOlder">Älter als</option>
            <option value="ValueListInArray">Enthält eines der Werte</option>
        </select>
        <input placeholder="wert" wire:model="compare_value" class="form-control-sm">
        @if($needsSourceFieldExtraName)
            Feld/Listenname: <input wire:model="source_field_extra_name" class="form-control-sm">
        @endif
        <div class="float-end">
            <button wire:click="$parent.removeRule({{$rule->id}})">❌</button>
        </div>
    </div>
</li>
