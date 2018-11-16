<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/15
 * Time: 18:18
 */

namespace app\api\validate;


use app\common\lib\exception\ParamError;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance()->param();
        $result = $this->batch()->check($request);

        if (!$result) {
            throw new ParamError([
                'errMessage' => $this->error
            ]);
        } else {
            return $result;
        }
    }
}