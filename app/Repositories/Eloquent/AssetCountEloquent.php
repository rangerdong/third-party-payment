<?php
namespace App\Repositories\Eloquent;

use App\Lib\BCMathLib;
use App\Models\AssetCount;
use App\Repositories\Contracts\AssetCountRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class AssetCountEloquent extends BaseRepository implements AssetCountRepository
{

    protected $bcLib;

    public function __construct(\Illuminate\Container\Container $app, BCMathLib $BCMathLib)
    {
        parent::__construct($app);
        $this->bcLib = $BCMathLib;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return AssetCount::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     *
     *
     * @param int    $uid
     * @param string $method add添加资金 sub减少资金 rechargeFrozen 充值冻结  withdrawFrozen 提现冻结 rechargeThaw解冻充值资金 withdraw 提现成功
     * @param float  $amt
     *
     * @return bool
     * @throws \Exception {$method} not found
     */
    public function operateAsset($uid, $method, $amt)
    {
        $asset = $this->model->byUserId($uid)->first();
        if ( ! $asset) {
            $asset = $this->model->create([
                'uid' => $uid,
                'available' => 0,
                'recharge_frozen' => 0,
                'settle_frozen' => 0,
                'other_frozen' => 0
            ]);
        }
        if (method_exists(self::class, $method.'Asset')) {
            $func_name = $method.'Asset';
            if ($this->$func_name($asset, $amt)) { //访问操作
                return true;
            } else {
                return false;
            }
        } else {
            throw new \Exception('OperateAsset\'s method - ['.$method.']is not exists '); //若无操作方法，则抛出异常
        }
    }


    protected function addAsset($asset, $amt)
    {
        $asset->available = bcadd($asset->available, $amt, 6);
        if ($asset->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function subAsset($asset, $amt)
    {
        $asset->available = bcsub($asset->available, $amt, 6);
        if ($this->bcLib->isInvalidAmt($asset->available) && $asset->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function rechargeFrozenAsset($asset, $amt)
    {
        $asset->recharge_frozen = bcadd($asset->recharge_frozen, $amt, 6);
        if ($asset->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function rechargeThawAsset($asset, $amt)
    {
        $asset->recharge_frozen = bcsub($asset->recharge_frozen, $amt, 6);
        $asset->available = bcadd($asset->available, $amt, 6);
        if ($this->bcLib->isInvalidAmt($asset->recharge_frozen) && $asset->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function withdrawFrozenAsset($asset, $amt)
    {
        $asset->withdraw_frozen = bcadd($asset->withdraw_frozen, $amt, 6);
        $asset->available = bcsub($asset->available, $amt, 6);
        if ($this->bcLib->isInvalidAmt($asset->available) && $asset->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function withdrawAsset($asset, $amt)
    {
        $asset->withdraw_frozen = bcsub($asset->withdraw_frozen, $amt, 6);
        if ($this->bcLib->isInvalidAmt($asset->withdraw_frozen) && $asset->save()) {
            return true;
        } else {
            return false;
        }
    }


}
