<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Models\Rule;
use App\Models\RuleSet;
use App\Models\Tab;
use Illuminate\Http\Request;
use Livewire\Component;

class Rules extends Component
{
    public Organization $org;


    public ?Tab $tab = null;

    public function mount(Organization $org, $tab)
    {
        $this->org = $org;
        $this->tab = $tab;
        $this->hydrate();

    }
    public function addRuleSet()
    {
        $this->org->ruleSets()->create([
            'name' => 'Neue Regel',
            'type' => 'set',
            'order' => $this->org->ruleSets()->max('order') + 1,
            'tab_id' => $this->tab?->id,
        ]);
        $this->hydrate();
    }

    public function hydrate() {
        $this->org->load([
            'ruleSets' => fn($q) => $q->orderBy('order')->where('tab_id', $this->tab?->id),
            'ruleSets.rules'
        ]);
    }

    public function addSpacer()
    {
        $this->org->ruleSets()->create([
            'name' => 'Spacer',
            'type' => 'spacer',
            'order' => $this->org->ruleSets()->max('order') + 1,
            'tab_id' => $this->tab?->id,
        ]);
        $this->hydrate();

    }

    public function removeRuleSet(RuleSet $ruleSet)
    {
        $this->org->ruleSets->where('id', $ruleSet->id)->first()->delete();
        $this->hydrate();

    }

    public function save() {
        $this->dispatch('rules-save');
        $this->hydrate();

    }

    public function render()
    {
        return view('livewire.rules');
    }
    public function updateRuleSetOrder($order)
    {
        foreach($order as $item) {
            $id = str_replace('ruleset-sort-', '', $item['value']);
            $this->org->ruleSets()->where('id', $id)->update(['order' => $item['order']]);
        }
        $this->hydrate();
    }
}
