<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleSet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function destinationField()
    {
        return $this->belongsTo(DestinationField::class);
    }
}
