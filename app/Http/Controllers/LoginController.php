<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use App\Helpers\Jwt;
class LoginController extends Controller
{
    //
    public function captcha()
    {
        $phrase = new PhraseBuilder;
        // 设置验证码位数
        $code = $phrase->build(4);
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);
        // 设置背景颜色25,25,112
        $builder->setBackgroundColor(255, 255, 255);
        // 设置倾斜角度
        $builder->setMaxAngle(25);
        // 设置验证码后面最大行数
        $builder->setMaxBehindLines(10);
        // 设置验证码前面最大行数
        $builder->setMaxFrontLines(10);
        // 设置验证码颜色
        $builder->setTextColor(230, 81, 175);
        // 可以设置图片宽高及字体
        $builder->build($width = 165, $height = 45, $font = null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();
        // 缓存验证码时的Uuid
        $key = Uuid::uuid1()->toString();
        // 把内容存入 cache，5分钟后过期
        Cache::put($key,$phrase,Carbon::now()->addMinutes(10));
        // 组装接口数据
        $data = [
            'key' => $key,
            'captcha' => $builder->inline(),
        ];
        $message = array(
            "msg" => '操作成功',
            "issucceed" => true,
            "count" => $data
        );
        return $message;
    }

    public function login()
    {
        // 参数
        $param = request() -> all();
        // 用户名
        $username = trim($param['username']);       //trim 移除字符串的首尾空格
        // 密码
        $password = trim($param['password']);
        // 验证规则
        $rules = [
            'username' => 'required | min: 2 | max: 24',
            'password' => 'required | min: 6 | max: 20',
            'captcha' => 'required',
            'key' => 'required',
        ];
        // 规则描述
        $messages = [
            'required' => ':attribute为必填项',
            'min' => ':attribute长度不符合要求',
            'captcha.required' => '验证码不能为空',
        ];
        // Validator自定义验证的类
        // 验证make参数（验证数据，验证规则，错误信息）
        $validator = Validator::make($param, $rules, $messages, [
            'username' => '用户名称',
            'password' => '登陆密码'
        ]);
        if($validator -> fails()) {
            $errors = $validator->errors() -> getMessages();
            foreach($errors as $k => $v) {
                return (message($v[0], false));
            }
        }
        // 验证码效验
        $codekey = trim($param['key']);
        $captcha = Cache::get($codekey);    //取出缓存的验证码
        // dump(strtolower($captcha));
        // dump(strtolower($param['captcha']));
        if (strtolower($captcha) != strtolower($param['captcha'])) {    //将字符串转化为小写
            return message("请输入正确的验证码", false);
        }
        // 用户名效验
        $user = User::where('UserName', $username) -> first();
        if(!$user) {
            return message('您的用户名不存在', false);
        }
        // 密码效验
        if(!Hash::check($password, $user->PassWord)) {
            return message('您的登录密码不正确', false);
        }
        // 使用状态校验
        if ($user['status'] != 1) {
            return message("您的帐号已被禁用", false);
        }
        // JWT生成token
        $jwt = new Jwt();
        $token = $jwt->getToken($user->Id);
        // // 结果返回
        $result = [
            'access_token' => $token,
        ];
        return message('登录成功', true, $result);
    }
}
