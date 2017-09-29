<?php
namespace App\Models\Observer;

use App\Models\AssetCount;
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
        //创建资金记录
        AssetCount::create([
            'uid' => $user->id
        ]);
        $user->code = generateCode($user->password);
        $user->key = generateKey($user->code);
        $user->save();
    }
}

