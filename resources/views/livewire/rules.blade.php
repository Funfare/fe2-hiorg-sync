<div>

    <button class="btn btn-primary" wire:click="addRuleSet">Neue Regel</button>
    <button class="btn btn-primary" wire:click="addSpacer">Trennlinie hinzufügen</button>
    <div class="float-end">
        <button wire:click="save">Speichern</button>
    </div>
    <div wire:sortable="updateRuleSetOrder">
        @foreach($org->ruleSets as $ruleSet)
            <div wire:key="ruleset-sort-{{$ruleSet->id}}" wire:sortable.item="ruleset-sort-{{$ruleSet->id}}">
                <livewire:rule-set :$ruleSet :key="'ruleset-'.$ruleSet->id"></livewire:rule-set>
            </div>
        @endforeach
    </div>
</div>
