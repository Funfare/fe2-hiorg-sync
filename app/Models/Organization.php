<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string|null $fe2_link
 * @property string|null $fe2_user
 * @property string|null $fe2_pass
 * @property string|null $fe2_sync_token
 * @property string|null $fe2_provisioning_user
 * @property string|null $fe2_provisioning_leader
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2Link($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2Pass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2ProvisioningLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2ProvisioningUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2SyncToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereFe2User($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Organization extends Model
{
    use HasFactory;

    protected $casts = [
        'hiorg_token' => 'json'
    ];
    protected $guarded = [];

    public function syncs()
    {
        return $this->hasMany(Sync::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ruleSets()
    {
        return $this->hasMany(RuleSet::class);
    }

    public function rules()
    {
        return $this->hasManyThrough(Rule::class, RuleSet::class);
    }

    public function tabs()
    {
        return $this->hasMany(Tab::class);
    }
}
