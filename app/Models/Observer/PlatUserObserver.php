<?php
namespace App\Models\Observer;

use App\Models\PlatUser;
use App\User;

class PlatUserObserver
{
    /**
     * 监听用户创建事件
     *
     * @param \App\Models\PlatUser $user
     */
    public function created(PlatUser $user)
    {
        $user->code = generateCode($user->password);
        $user->key = generateKey($user->code);
        $user->save();
    }
}

