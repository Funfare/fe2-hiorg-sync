<div class="col-auto row">
    <div class="col-auto">
        <div class="form-floating">
            <select wire:model.live="source_field_id" class="form-select">
                @foreach(\App\Models\SourceField::all() as $field)
                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                @endforeach
            </select>
            <label class="form-label">Datenfeld</label>
        </div>
    </div>

    <div class="col-auto pt-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" wire:model="not" type="checkbox" value="1" id="notCheckbox{{$rule->id}}">
            <label class="form-check-label" for="notCheckbox{{$rule->id}}">
                Nicht
            </label>
        </div>
    </div>

    <div class="col-auto">

        <div class="form-floating">
            <select wire:model="compare_class" class="form-select">
                <option value="IsEqual">=</option>
                <option value="Greater">&gt;</option>
                <option value="GreaterEquals">&gt;=</option>
                <option value="InArray">Enthält</option>
                <option value="IsEmpty">Ist leer</option>
                <option value="IsOlder">Älter als</option>
                <option value="ValueListInArray">Enthält eines der Werte</option>
                <option value="InFuture">In der Zukunft</option>
            </select>
            <label class="form-label">Vergleich</label>
        </div>
    </div>

    <div class="col-auto">
        <div class="form-floating">
            <input wire:model="compare_value" class="form-control">
            <label for="floatingInput">Wert</label>
        </div>
    </div>

    @if($needsSourceFieldExtraName)
    <div class="col-auto">
        <div class="form-floating">
            <input wire:model="source_field_extra_name" class="form-control">
            <label for="floatingInput">Feld/Listenname</label>
        </div>
    </div>
    @endif

    <div class="col pt-2">
        <button class="btn btn-outline-danger" wire:click="$parent.removeRule({{$rule->id}})">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            löschen
        </button>
    </div>

</div>

