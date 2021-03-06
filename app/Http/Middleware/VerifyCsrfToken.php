<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'send_request/*',
        'undo_request/*',
        'follow/*',
        'unfollow/*',
        'accept_friend/*',
        'decline_friend/*',
        'users/follow/merchants',
        'user/follow/*',
        'users/social_notification/delete/*',
        'search/user/*',
        '/users/profile/edit/picture',
        '/post'
        
    ];
}
