<?php

namespace App\Models;

use App\Notifications\SendVerifyWithQueueNotification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, HasName, MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, HasRoles, InteractsWithMedia, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'status',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'trial_ends_at',
    ];

    protected $appends = ['avatar', 'full_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const ADMIN_EMAIL = 'fordiquez@store.com';

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new SendVerifyWithQueueNotification);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function socials(): HasMany
    {
        return $this->hasMany(UserSocial::class);
    }

    public function orderRecipients(): HasMany
    {
        return $this->hasMany(OrderRecipient::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function goods(): HasMany
    {
        return $this->hasMany(Good::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === $this::ADMIN_EMAIL && $this->hasVerifiedEmail();
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn ($value) => "$this->first_name $this->last_name");
    }

    public function getFilamentName(): string
    {
        return "$this->first_name $this->last_name";
    }

    public function avatar(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getFirstMediaUrl('avatars'));
    }

    public function addAvatarMedia(string $url, string $collectionName = 'avatars', string $diskName = 'public'): void
    {
        try {
            $this->clearMediaCollection($collectionName)
                ->addMediaFromUrl($url)
                ->sanitizingFileName(fn ($fileName) => strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName)))
                ->toMediaCollection($collectionName, $diskName);
        } catch (FileDoesNotExist|FileIsTooBig $exception) {
            Log::error($exception->getMessage());
        }
    }
}
