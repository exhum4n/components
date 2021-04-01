<?php

declare(strict_types=1);

namespace Exhum4n\Components\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;
use Illuminate\Notifications\Notifiable as NotifiableTrait;

class AuthEntity extends AbstractModel implements Authenticatable, Authorizable, CanResetPassword
{
    use NotifiableTrait;
    use AuthorizableTrait;
    use AuthenticatableTrait;
    use CanResetPasswordTrait;
}
