<?php

namespace App\Livewire;

use App\Models\SourceField;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\On;

class Rule extends Component
{

    public \App\Models\Rule $rule;

    #[Validate('required|exists:source_fields,id')]
    public $source_field_id;

    public $compare_class;

    public $not;
    public $compare_value;

    public $source_field_extra_name;

    public $needsSourceFieldExtraName = false;
    public function updated($property)
    {
        // $property: The name of the current property that was updated

        if ($property === 'source_field_id') {
            $field = SourceField::find($this->source_field_id);
            $this->needsSourceFieldExtraName = $field->needs_extra_value;
        }
    }
    public function mount(\App\Models\Rule $rule)
    {
        $this->rule = $rule;
        $this->fill($rule);

        if($rule->sourceField?->needs_extra_value) {
            $this->needsSourceFieldExtraName = $rule->sourceField?->needs_extra_value;
        }
    }

    public function remove()
    {
        $this->rule->delete();
    }

    #[On('rules-save')]
    public function save()
    {
        $this->validate();
        $this->rule->fill($this->except(['rule', 'needsSourceFieldExtraName']))->save();
    }
    public function render()
    {
        return view('livewire.rule');
    }
}
