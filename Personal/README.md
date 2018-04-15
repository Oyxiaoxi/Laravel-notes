### 个人页面

#### 绑定路由
```php
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

# 上面代码等同于
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
```

#### 生成的资源路由列表信息
HTTP 请求 | URI | 动作 | 作用
----|----|----|----|----|
GET|/users/{user}|UsersController@show|显示用户个人信息页面
GET|/users/{user}/edit|UsersController@edit|显示编辑个人资料页面
PATCH|/users/{user}|UsersController@update|处理 edit 页面提交的更改

#### 创建控制器
```bash 
php artisan make:controller UsersController
```

#### 增加 show 
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
```
    1. 第一个修改是引入了 App\Models\User 用户模型，因为将要在 show() 方法中使用到 User 模型，所以必须先引用。
    2. show() 方法的声明：
> Laravel 会自动解析定义在控制器方法（变量名匹配路由片段）中的 Eloquent 模型类型声明。由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。

#### 增加路由
```php
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
```