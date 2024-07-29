<?php

namespace App\Models;

use App\Enums\UserType;
use App\Events\UserCreatedEvent;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\LiveChat\Chat;
use App\Models\SeamlessTransaction;
use App\Models\ThreeD\Lotto;
use App\Models\TwoD\Lottery;
use App\Models\TwoD\LotteryTwoDigitPivot;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Wallet
{
    use HasApiTokens, HasFactory, HasWalletFloat, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'name',
        'email',
        'phone',
        'password',
        'profile',
        'register_type',
        'third_party_id',
        'token',
        'device_id',
        'fcm_token',
        'gem',
        'bonus',
        'limit',
        'limit3',
        'cor',
        'cor3',
        'zero',
        'remark',
        'chk',
        'language',
        'active',
        'kpay_no',
        'cbpay_no',
        'wavepay_no',
        'ayapay_no',
        'lottery_balance',
        'max_score',
        'agent_id',
        'slot_wallet',
        'lottery_wallet',
        'status',
        'type',
        'main_balance',
        'is_changed_password',
        'referral_code',
        'last_active_at',
        'note',
    ];

    protected $dispatchesEvents = [
        'created' => UserCreatedEvent::class,
    ];

    protected $dates = ['last_active_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => UserType::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('last_active_at', '>=', now()->subMinutes(5));
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function isAdmin()
    {
        return $this->roles()->where('id', 1);
    }

    public function getIsMasterAttribute()
    {
        return $this->roles()->where('id', 2)->exists();
    }

    public function getIsAgentAttribute()
    {
        return $this->roles()->where('id', 3)->exists();
    }

    public function getIsUserAttribute()
    {
        return $this->roles()->where('id', 4)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('title', $role);
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->pluck('title')->contains($permission);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Other users that this user (a master) has created (agents)
    public function createdAgents()
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    // The master that created this user (an agent)
    public function createdByMaster()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public static function adminUser()
    {
        return self::where('type', UserType::Admin)->first();
    }

    public function seamlessTransactions()
    {
        return $this->hasMany(SeamlessTransaction::class, 'user_id');
    }

    public function wagers()
    {
        return $this->hasMany(Wager::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function scopeRoleLimited($query)
    {
        if (! Auth::user()->hasRole('Admin')) {
            return $query->where('agent_id', Auth::id());
        }

        return $query;
    }

    public function lotteries()
    {
        return $this->hasMany(Lottery::class);
    }

    public function lotteryTwoDigitPivots()
    {
        return $this->hasMany(LotteryTwoDigitPivot::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    public function lottos()
    {
        return $this->hasMany(Lotto::class, 'user_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    public function adminChats()
    {
        return $this->hasMany(Chat::class, 'admin_id');
    }
}
