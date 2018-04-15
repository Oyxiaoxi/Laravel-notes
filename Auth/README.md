### 注册与登录

> Laravel 自带了用户认证功能，我们将利用此功能来快速构建我们的用户中心。

```bash 
php artisan make:auth
```

#### 路由绑定
```php
Route::get('/', 'PagesController@root')->name('root');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
```

#### 顶部导航
```php
@guest
    <li><a href="{{ route('login') }}">登录</a></li>
    <li><a href="{{ route('register') }}">注册</a></li>
@else
    .
    .
    .
@endguest
```
> 如果是未登录用户的话，就显示注册和登录按钮，如果是已登录用户的话，即显示用户菜单。

#### 数据迁移
```bash
php artisan migrate
```

> 会生成三张表

| 	users   |   |  |  |  |  |  | 
|:----:|:----:|:----:|:----:|:----:|:----:|:----:|
| 	id   | name  | email | password | remember_token | created_at | updated_at | 

| password_resets |   |   | 
|:----:|:----:|:----:|
| email | token | created_at  | 

| migrations |   |   | 
|:----:|:----:|:----:|
| id | migration | batch | 

#### 手动认证

```php 
use Illuminate\Support\Facades\Auth; # 头部引用 Laravel 5.6
use Illuminate\Http\RedirectResponse; 

public function login(Request $request){
    if ($request -> isMethod('post')) {
        $data = $request -> input();
        // attempt 接收 $data[] 值 ，手动认证
        if (Auth::attempt(['email'=>$data['email'], 'password'=>$data['password'], 'admin'=>'1'])) {
            echo "Success"; die;
        } else {
            echo "Falied"; die;
        }
    }
    return view('模板');
}

```

> users 表 password 之后增加 admin 字段，默认值为 1