<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-auto pt-3">
                <span wire:sortable.handle>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-move" width="24"
                         height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                                              fill="none"/><path d="M18 9l3 3l-3 3"/><path
                            d="M15 12h6"/><path d="M6 9l-3 3l3 3"/><path d="M3 12h6"/><path d="M9 18l3 3l3 -3"/><path
                            d="M12 15v6"/><path d="M15 6l-3 -3l-3 3"/><path d="M12 3v6"/></svg>
                </span>
            </div>
            <div class="col-auto p-0">
                <div class="form-floating">
                    <input class="form-control" wire:model="name">
                    <label for="floatingInput">Regelname</label>
                </div>
            </div>

            <div class="col-auto">
                <div class="form-floating">
                    <select wire:model="destination_field_id" class="form-select">
                        <option value="">Bitte Wählen</option>
                        @foreach(\App\Models\DestinationField::all() as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                    <label class="form-label">FE2 Feld</label>
                </div>
            </div>

            <div class="col-auto p-0">
                <div class="form-floating">
                    <select wire:model="type" class="form-select">
                        <option value="">Bitte Wählen</option>
                        <option value="set">setzen</option>
                        <option value="replace">ersetzen</option>
                        <option value="add">hinzufügen</option>
                        <option value="remove">entfernen</option>
                        <option value="abort">Kein Sync</option>
                    </select>
                    <label class="form-label">Aktion</label>
                </div>
            </div>

            <div class="col-auto">
                <div class="form-floating">
                    <select wire:model.live="set_value_type" class="form-select">
                        <option value="">Bitte Wählen</option>
                        <option value="text">Freitext</option>
                        <option value="field">HiOrg Feld</option>
                        <option value="qualification:name">Qualifikation: Name</option>
                        <option value="qualification:name_short">Qualifikation: Abkürzung</option>
                        <option value="phone:formatted">Telefonnummer formatiert</option>
                    </select>
                    <label class="form-label">Wert</label>
                </div>
            </div>

            <div class="col-auto p-0">
                <div class="form-floating">
                    @if($set_value_type == 'field' || $set_value_type == 'phone:formatted')

                        <select wire:model.live="set_value" class="form-select">
                            <option value="">Bitte Wählen</option>
                            @foreach(\App\Models\SourceField::when(
    $set_value_type == 'phone:formatted', fn($q) => $q->whereIn('key', ['attributes.telpriv', 'attributes.teldienst', 'attributes.handy']))
    ->orderBy('name')->get() as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                            @endforeach
                        </select>
                        <label class="form-label">Bitte wählen</label>
                    @elseif($set_value_type != '')
                        <input class="form-control" wire:model="set_value">
                        <label for="floatingInput">Wert</label>
                    @endif
                </div>
            </div>
            @if($set_value_type == 'field' && $needsSourceFieldExtraName)
                <div class="col-auto p-0 ps-2">
                    <div class="form-floating">
                        <input class="form-control" wire:model="source_field_extra_name">
                        <label class="form-label">Feld/Listenname</label>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($ruleSet->rules->isNotEmpty())
        <div class="card-body">
            <div class="row">
                <div class="col-auto pt-3">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-braces" width="24"
                             height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                                                  fill="none"/><path
                                d="M7 4a2 2 0 0 0 -2 2v3a2 3 0 0 1 -2 3a2 3 0 0 1 2 3v3a2 2 0 0 0 2 2"/><path
                                d="M17 4a2 2 0 0 1 2 2v3a2 3 0 0 0 2 3a2 3 0 0 0 -2 3v3a2 2 0 0 1 -2 2"/></svg>
                    </span>
                </div>
                <div class="col-auto ps-0">
                    <div class="form-floating">
                        <select wire:model="operation" class="form-select">
                            <option value="and">UND</option>
                            <option value="or">ODER</option>
                        </select>
                        <label class="form-label">Operator</label>
                    </div>
                </div>
                @foreach($ruleSet->rules as $rule)
                    <livewire:rule :$rule :key="'rule-'.$rule->id"></livewire:rule>
                @endforeach
            </div>
        </div>
    @endif
    <div class="card-footer">
        <div class="float-end">
            <button class="btn btn-outline-secondary" wire:click="addRule({{$ruleSet->id}})">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-code-plus" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 12h6"/>
                    <path d="M12 9v6"/>
                    <path d="M6 19a2 2 0 0 1 -2 -2v-4l-1 -1l1 -1v-4a2 2 0 0 1 2 -2"/>
                    <path d="M18 19a2 2 0 0 0 2 -2v-4l1 -1l-1 -1v-4a2 2 0 0 0 -2 -2"/>
                </svg>
                Bedingung hinzufügen
            </button>
            <button class="btn btn-outline-danger float-end ms-1" wire:click="$parent.removeRuleSet({{$ruleSet->id}})">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 7l16 0"/>
                    <path d="M10 11l0 6"/>
                    <path d="M14 11l0 6"/>
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                </svg>
                Regel löschen
            </button>
        </div>
    </div>
</div>
