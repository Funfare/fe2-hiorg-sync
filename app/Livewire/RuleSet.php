<?php

namespace App\Livewire;

use App\Compares\IsEqual;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\On;

class RuleSet extends Component
{
    public \App\Models\RuleSet $ruleSet;

    #[Validate('required')]
    public $name;
    #[Validate('required|in:add,replace,set,abort,remove')]
    public $type;
    #[Validate('required|in:and,or')]
    public $operation;
    #[Validate('required|integer')]
    public $order;

    public $set_value;

    //#[Validate('required|exists:destination_fields,id')]
    public $destination_field_id;
    public $set_value_type;

    public function addRule()
    {
        $this->ruleSet->rules()->create([
            'name' => 'Neue Regel',
            'compare_class' => 'IsEqual',
        ]);
    }

    public function removeRule(\App\Models\Rule $rule)
    {
        $this->ruleSet->rules->where('id', $rule->id)->first()->delete();
        $this->ruleSet->load('rules');
    }

    public function mount(\App\Models\RuleSet $ruleSet)
    {
        $this->ruleSet = $ruleSet;
        $this->fill($ruleSet);
    }

    #[On('rules-save')]
    public function save()
    {
        $this->validate();
        $this->ruleSet->fill($this->except('ruleSet'))->save();
    }

    public function render()
    {
        if($this->ruleSet->type == 'spacer') {
            return view('components.rule-spacer');
        }
        return view('livewire.rule-set');
    }


}
