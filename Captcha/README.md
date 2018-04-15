### 注册图片验证

> 注册功能存在一个问题，因我们表单未添加任何防护，恶意用户可以轻易使用机器人自动化注册新用户。机器人自由注册，对我们站点稳定性来讲是巨大的威胁，恶意用户可以很轻易的通过机器人程序在短时间内，注册大量用户，甚至于填满我们的数据库。

#### 安装扩展包
```bash
composer require "mews/captcha:~2.0" # 第三方扩展包  
# config/captcha.php
php artisan vendor:publish --provider='Mews\Captcha\CaptchaServiceProvider'
```

```php
return [

    'characters' => '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ',

    'default'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
    ],

    'flat'   => [
        'length'    => 6,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 90,
        'lines'     => 6,
        'bgImage'   => false,
        'bgColor'   => '#ecf2f4',
        'fontColors'=> ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast'  => -5,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 2,
        'invert'    => true,
        'contrast'  => -5,
    ]

];
```

+ 配置选项
> characters 选项是用来显示给用户的所有字符串，default, flat, mini, inverse 分别是定义的四种验证码类型，你可以在此修改对应选项自定义验证码的长度、背景颜色、文字颜色等属性

#### 1.前端展示
> make:auth 命令生成了 resources/views/auth 下的四个文件

视图名称 | 说明 
----|------|
register.blade.php | 注册页面视图  |
login.blade.php | 登录页面视图  |
passwords/email.blade.php | 提交邮箱发送邮件的视图  |
passwords/reset.blade.php | 重置密码的页面视图  |

```php
# 图片验证码
@if ($errors->has('captcha'))
    <span class="help-block">
        <strong>{{ $errors->first('captcha') }}</strong>
    </span>
@endif
```

> captcha_src() 方法是 [mews/captcha](https://github.com/mewebstudio/captcha) 提供的辅助方法，用于生成验证码图片链接；

#### 2.后端验证
```php
# 验证逻辑
# app/Http/Controllers/Auth/RegisterController.php
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'captcha' => 'required|captcha',
    ], [
        'captcha.required' => '验证码不能为空',
        'captcha.captcha' => '请输入正确的验证码',
    ]);
}
```

> 'captcha' => 'required|captcha',
> 表达式里的第二个 captcha 是 mews/captcha 自定义的表单验证规则。扩展包非常巧妙地利用了 Laravel 表单验证器提供的 自定义表单验证规则 功能。