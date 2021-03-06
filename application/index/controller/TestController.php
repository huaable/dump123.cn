<?php

namespace app\index\controller;

use PFinal\Wechat\Service\MenuService;
use PFinal\Wechat\Service\OAuthService;
use PFinal\Wechat\Kernel;
use think\Request;

//测试控制器
class TestController extends CommonController
{

    public function Index()
    {

        //        abort('404','not found');
        //        Log::info('xxx');
        return $this->fetch();
    }


    //测试公众号使用的
    public function wx(Request $request)
    {
        //配置项
        $config = config('wechat');
        //初始化
        Kernel::init($config);

        //处理微信服务器的请求
        $response = Kernel::handle();

        return $response;
    }

    private function wxInit()
    {
        //配置项
        //        $config = array(
        //            'appId' => 'wx37258c7e73adebb5',
        //            'appSecret' => '201cdd6bcb72ac67e009f4673b8c3b18',
        //            'token' => 'huaable',
        //            'encodingAesKey' => 'n8iqub0ScwWpmYP1ZNQThxOhEL9KwXGMkrB5X7AL1B0',
        //            //更多详细配置请参考 demo/config-local.example
        //        );

        //配置项
        $config = config('wechat');

        //初始化
        Kernel::init($config);
    }

    public function WxMenu()
    {


        $this->wxInit();

        //        $menus = [
        //            [
        //                "type" => "click",
        //                "name" => "今日歌曲",
        //                "key" => "V1001_TODAY_MUSIC"
        //            ],
        //            [
        //                "name" => "菜单",
        //                "sub_button" => [
        //                    [
        //                        "type" => "view",
        //                        "name" => "官网",
        //                        "url" => "http://dump123.cn/"
        //                    ],
        //                    [
        //                        "type" => "click",
        //                        "name" => "赞一下我们",
        //                        "key" => "V1001_GOOD"
        //                    ],
        //                ],
        //            ],
        //        ];


        $res = MenuService::create([
            [
                "type" => "view",
                "name" => "官网",
                "url" => "http://dump123.cn/"
            ],
            [
                "type" => "click",
                "name" => "赞一下我们",
                "key" => "V1001_GOOD"
            ],
        ]);
        dump($res);
    }


    public function wxPage()
    {
        $this->wxInit();
        $openid = OAuthService::getOpenid();
        echo $openid;
    }

    public function qy()
    {
        require_once EXTEND_PATH . 'weixin_qy/callback/WXBizMsgCrypt.php';

        // 假设企业号在公众平台上设置的参数如下
        $encodingAesKey = config('qyweixin.encodingAesKey');
        $token = config('qyweixin.token');
        $corpId = config('qyweixin.corp_id');

        /*
        ------------使用示例一：验证回调URL---------------
        *企业开启回调模式时，企业号会向验证url发送一个get请求
        假设点击验证时，企业收到类似请求：
        * GET /cgi-bin/wxpush?msg_signature=5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3&timestamp=1409659589&nonce=263014780&echostr=P9nAzCzyDtyTWESHep1vC5X9xho%2FqYX3Zpb4yKa9SKld1DsH3Iyt3tP3zNdtp%2B4RPcs8TgAE7OaBO%2BFZXvnaqQ%3D%3D
        * HTTP/1.1 Host: qy.weixin.qq.com

        接收到该请求时，企业应
        1.解析出Get请求的参数，包括消息体签名(msg_signature)，时间戳(timestamp)，随机数字串(nonce)以及公众平台推送过来的随机加密字符串(echostr),
        这一步注意作URL解码。
        2.验证消息体签名的正确性
        3. 解密出echostr原文，将原文当作Get请求的response，返回给公众平台
        第2，3步可以用公众平台提供的库函数VerifyURL来实现。

        */

        // $sVerifyMsgSig = HttpUtils.ParseUrl("msg_signature");
        $sVerifyMsgSig = \request()->get('msg_signature');
        // $sVerifyTimeStamp = HttpUtils.ParseUrl("timestamp");
        $sVerifyTimeStamp = \request()->get('timestamp');
        // $sVerifyNonce = HttpUtils.ParseUrl("nonce");
        $sVerifyNonce = \request()->get('nonce');
        // $sVerifyEchoStr = HttpUtils.ParseUrl("echostr");
        $sVerifyEchoStr = \request()->get('echostr');

        // 需要返回的明文
        $sEchoStr = "";

        $wxcpt = new \WXBizMsgCrypt($token, $encodingAesKey, $corpId);
        $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
        if ($errCode == 0) {
            return ($sEchoStr);
            //
            // 验证URL成功，将sEchoStr返回
            // HttpUtils.SetResponce($sEchoStr);
        } else {
            print("ERR: " . $errCode . "\n\n");
        }

    }


    public function company()
    {

        //        $WXAPI = new \WXAPI();
        //        // 获取 js 签名
        //        $jssdk = $WXAPI->jssdk_sign();
        //        //        dump($jssdk);
        //        $signPackage = [
        //            'appId' => $jssdk['app_id'],
        //            'timestamp' => $jssdk['timestamp'],
        //            'nonceStr' => $jssdk['noncestr'],
        //            'signature' => $jssdk['sign'],
        //        ];
        //        echo $url = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        //        $this->assign('signPackage', $signPackage);


                include_once(EXTEND_PATH . 'weworkapi/api/src/CorpAPI.class.php');
                include_once(EXTEND_PATH . 'weworkapi/api/src/ServiceCorpAPI.class.php');
                include_once(EXTEND_PATH . 'weworkapi/api/src/ServiceProviderAPI.class.php');
                //
//                $api = new \corpApi(config('qywx.corp_id'), config('qywx.secret'));
//        $ticket = $api->JsApiTicketGet();
//        dump($ticket);
//        dump($_GET);
        die;

        //        $api->
                $this->assign('signPackage', [
                    'appId' => config('qywx.corp_id'),
                    'timestamp' => time(),
                    'nonceStr' => '111',
                    'signature' => '',
                ]);
                return $this->fetch();
//
//        require_once(EXTEND_PATH . 'weixin/lib/WXAPI.php');
//
//
//
//        $WXAPI = new \WXAPI(config('qywx.corp_id'), config('qywx.secret'));
//
//        // 获取 js 签名
//        $signPackage = $WXAPI->jssdk_sign();
//
//
//        $this->assign('signPackage', $signPackage);
//        return $this->fetch();

    }

    public function companyApi()
    {

        require_once(EXTEND_PATH . 'weixin/lib/WXAPI.php');

        $WXAPI = new \WXAPI(config('qywx.corp_id'), config('qywx.secret'));

        // 获取 js 签名
        $signPackage = $WXAPI->jssdk_sign();

        $this->assign('signPackage', $signPackage);
        return $this->fetch('company');
    }

}
