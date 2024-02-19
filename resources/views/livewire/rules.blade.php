<div>
    <div class="card">
        <div class="card-header">
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
        </div>
        <div class="card-body">
            <div wire:sortable="updateRuleSetOrder">
                @foreach($org->ruleSets as $ruleSet)
                    <div wire:key="ruleset-sort-{{$ruleSet->id}}" wire:sortable.item="ruleset-sort-{{$ruleSet->id}}">
                        <livewire:rule-set :$ruleSet :key="'ruleset-'.$ruleSet->id"></livewire:rule-set>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" wire:click="addRuleSet">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="20" height="20" viewBox="0 0 28 28" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Neue Regel
            </button>
            <button class="btn btn-outline-primary" wire:click="addSpacer">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-plus" width="20" height="20" viewBox="0 0 28 28" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 10h-14" /><path d="M5 6h14" /><path d="M14 14h-9" /><path d="M5 18h6" /><path d="M18 15v6" /><path d="M15 18h6" /></svg>
                Trennlinie hinzufügen
            </button>
            <button class="btn btn-success float-end" wire:click="save">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 28 28" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                Speichern
            </button>

        </div>
    </div>
</div>
