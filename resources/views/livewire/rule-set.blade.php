<div class="card" >
    <div class="card-header">
        Name: <input class="form-control-sm" type="text" wire:model="name">



        FE2 Feld
        <select wire:model="destination_field_id" class="form-control-sm">
            @foreach(\App\Models\DestinationField::all() as $field)
                <option value="{{ $field->id }}">{{ $field->name }}</option>
            @endforeach
        </select> <br/>
        Aktion
        <select wire:model="type" class="form-control-sm">
            <option value="set">setzen</option>
            <option value="replace">ersetzen</option>
            <option value="add">hinzufügen</option>
            <option value="remove">entfernen</option>
            <option value="abort">Nicht syncronisieren</option>
        </select>
        Wert: <select wire:model.live="set_value_type" class="form-control-sm">
            <option value="text">Freitext</option>
            <option value="field">Hiorg-Server Feld</option>
            <option value="qualification:name">Qualifikation: Name</option>
            <option value="qualification:name_short">Qualifikation: Abkürzung</option>
        </select>
        @if($set_value_type == 'field')
            <select wire:model="set_value" class="form-control-sm">
                @foreach(\App\Models\SourceField::all() as $field)
                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                @endforeach
            </select>
        @else
            <input class="form-control-sm" wire:model="set_value">
        @endif
        <div class="float-end">
            <span wire:sortable.handle>move</span>
            <button wire:click="addRule({{$ruleSet->id}})">Regel hinzufügen</button>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                Operator <select wire:model="operation" class="form-control-sm">
                    <option value="and">UND</option>
                    <option value="or">ODER</option>
                </select>
            </li>


            @foreach($ruleSet->rules as $rule)
                <livewire:rule :$rule :key="'rule-'.$rule->id"></livewire:rule>
            @endforeach
        </ul>
    </div>
</div>
