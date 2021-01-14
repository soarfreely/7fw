<?php


namespace App\Http\Controllers\Mini\Wechat;


use App\Http\Controllers\Mini\MiniController;

class AuthWechatController extends MiniController
{
    CONST code2Session = 'https://api.weixin.qq.com';
    CONST path = '/sns/jscode2session';

    protected $miniAppConfig = [];

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth_mini', ['except' => ['login']]);
    }

    /**
     * 登录获取用户授权信息
     *
     * 注意，没有一劳永逸的授权，如果和项目需求不符，则需要自行开发定义
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/5/7 4:27 PM
     */
    public function login(Request $request)
    {
        try {
            $postData = $request->only(['code']);
            if (empty($postData['code'])) {
                throw new \Exception('参数错误,缺少code', '-1');
            }
            $params = array_merge($this->miniAppConfig, [
                'js_code' => $postData['code'],
            ]);
            $data = (new HttpRequest())->setConfig(['base_uri' => self::code2Session])->get(self::path."?".http_build_query($params));
            $data = json_decode($data, true);
            if (!isset($data['openid']) || !isset($data['session_key'])) {
                throw new \Exception($data['errmsg'], $data['errcode']);
            }

            $record = Visitor::where('openId', $data['openid'])->get()->toArray();
            $returnData['is_auth'] = empty($record) ? 0 : 1;
            $returnData['token'] = jwtToken($data);

            /**
             * TODO 此处修改建议
             * 1.上面查询到用户是否授权，如果没有授权，则给前端返回自定义标识，未授权，如isAuth = false,已授权的话，返回用户信息即Token信息
             * 2.后端保存session_key和openId对应关系到redis，设置过期时间
             * 3.前端进行授权操作，拿到encryptedData'和'iv'，到下面userInfo接口
             * 4.userInfo接口从redis拿到session_key 进行解密用户收据，返回token
             */
            return $this->setHeaders(['Authorization' => 'Bearer '.$returnData['token']])->success($returnData);
        } catch (\Exception $e) {
            return $this->failed($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 获取用户信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/5/7 4:40 PM
     */
    public function userInfo(Request $request)
    {
        $params = $request->only(['encryptedData', 'iv', 'openid']);
        $sessionKey = Redis::get($params['openid']);
        if (is_null($sessionKey)) {
            throw new \Exception("登录已过期(session_key)");
        }
        $pc = new WXBizDataCrypt($this->miniAppConfig['appid'], $sessionKey);
        $errCode = $pc->decryptData($params['encryptedData'], $params['iv'], $userInfo);
        if ($errCode != 0) {
            throw new \Exception("授权失败", $errCode);
        }
        $userInfo = json_decode($userInfo, true);
        // 解密出授权信息，根据数据库数据进行查询，返回详细用户信息，关键信息放入token 返回给前端
        $returnData = [];

        return $this->setHeaders(['Authorization' => 'Bearer '.$returnData['token']])->success($returnData);
    }

}
