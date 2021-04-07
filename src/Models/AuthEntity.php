<?php

declare(strict_types=1);

namespace Exhum4n\Components\Models;

use Carbon\Carbon;
use Exhum4n\Users\Models\Credentials;
use Exhum4n\Users\Models\Status;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;
use Illuminate\Notifications\Notifiable as NotifiableTrait;

/**
 * Class AuthEntity
 *
 * @property int id
 * @property int status_id
 * @property bool is_verified
 * @property string email
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Status status
 * @property Credentials credentials
 */
class AuthEntity extends AbstractModel implements Authenticatable, Authorizable, CanResetPassword
{
    use NotifiableTrait;
    use AuthorizableTrait;
    use AuthenticatableTrait;
    use CanResetPasswordTrait;

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return HasOne
     */
    public function credentials(): HasOne
    {
        return $this->hasOne(Credentials::class);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
