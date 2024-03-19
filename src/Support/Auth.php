<?php

declare(strict_types=1);

namespace BasementChat\Basement\Support;

use App\Models\ChatUser;
use Illuminate\Support\Facades\Auth as BaseAuth;

class Auth
{
    public static function check()
    {
        return BaseAuth::guard('customer')->check() || BaseAuth::guard('freelancer')->check();
    }

    public static function user()
    {
        $modelType = '';
        $modelId = null;

        if (BaseAuth::guard('customer')->check()) {
            $modelType = 'customer';
            $modelId = BaseAuth::guard('customer')->user()->id;
        }

        if (BaseAuth::guard('freelancer')->check()) {
            $modelType = 'freelancer';
            $modelId = BaseAuth::guard('freelancer')->user()->id;
        }

        return ChatUser::query()
            ->where('type', $modelType)->where('user_id', $modelId)
            ->first();
    }

    public static function id()
    {
        return self::user()->id;
    }

    public static function type(): ?string
    {
        if (BaseAuth::guard('customer')->check()) {
            return 'customer';
        }

        if (BaseAuth::guard('freelancer')->check()) {
            return 'freelancer';
        }

        return null;
    }
}
