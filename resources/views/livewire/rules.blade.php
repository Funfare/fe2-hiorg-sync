<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link @if($tab == null) active @endif" aria-current="page" href="{{route('rules.show')}}">Unkategorisiert</a>
        </li>
        @foreach($org->tabs as $item)
            <li class="nav-item">
                <a class="nav-link @if($tab?->id == $item->id) active @endif" aria-current="page" href="{{route('rules.show', $item)}}">{{ $item->name }}</a>
            </li>
        @endforeach
    </ul>

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
