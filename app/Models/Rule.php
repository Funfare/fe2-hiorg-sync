<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'not' => 'boolean'
    ];

    public function sourceField()
    {
        return $this->belongsTo(SourceField::class);
    }

    public function ruleSet()
    {
        return $this->belongsTo(RuleSet::class);
    }
}
