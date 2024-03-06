<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $guarded = ['id'];
    public function ruleSets()
    {
        return $this->hasMany(RuleSet::class);
    }
}
