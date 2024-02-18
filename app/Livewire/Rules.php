<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Models\Rule;
use App\Models\RuleSet;
use Livewire\Component;

class Rules extends Component
{
    public Organization $org;


    public function mount(Organization $org)
    {
        $this->org = $org;
        $this->org->load([
            'ruleSets' => fn($q) => $q->orderBy('order'),
            'ruleSets.rules'
        ]);
    }
    public function addRuleSet()
    {
        $this->org->ruleSets()->create([
            'name' => 'Neue Regel',
            'type' => 'set',
            'order' => 0,
        ]);
    }

    public function addSpacer()
    {
        $this->org->ruleSets()->create([
            'name' => 'Spacer',
            'type' => 'spacer',
            'order' => 0,
        ]);
    }

    public function removeRuleSet(RuleSet $ruleSet)
    {

    }

    public function save() {
        $this->dispatch('rules-save');
        $this->org->load([
            'ruleSets' => fn($q) => $q->orderBy('order'),
            'ruleSets.rules'
        ]);
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
        $this->org->load([
            'ruleSets' => fn($q) => $q->orderBy('order'),
            'ruleSets.rules'
        ]);
    }
}
