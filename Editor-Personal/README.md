### 编辑个人资料

#### 新增字段

> 由于进行的是字段添加操作,需要遵照如 add_column_to_table 这样的命名规范，并在生成迁移文件的命令中设置 --table 选项，用于指定对应的数据库表。

```bash
php artisan make:migration add_avatar_and_introduction_to_users_table --table=users
```

2018_04_15_160954_add_avatar_and_introduction_to_users_table.php

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvatarAndIntroductionToUsersTable extends Migration
{
    /**
     * 执行迁移
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->string('introduction')->nullable();
        });
    }

    /**
     * 回滚迁移
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('introduction');
        });
    }
}
```

+ 将字段加入到用户表

```bash
php artisan migrate
```

#### 表单请求 UserRequest
> 表单请求验证（FormRequest） 是 Laravel 框架提供的用户表单数据验证方案，此方案相比手工调用 validator 来说，能处理更为复杂的验证逻辑，更加适用于大型程序。

```bash
php artisan make:request UserRequest
```

> 注： 需要在相应的控制器里面增加  use App\Http\Requests\UserRequest;

#### 生成的文件目录
```bash
app/Http/Requests/UserRequest.php
```

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
        ];
    }
}
```

#### 字段解释
字段|作用
----|----|
name.required|验证的字段必须存在于输入数据中，而不是空。
name.between|验证的字段的大小必须在给定的 min 和 max 之间。
name.regex|验证的字段必须与给定的正则表达式匹配。
name.unique|验证的字段在给定的数据库表中必须是唯一的。
email.required|验证的字段在给定的数据库表中必须是唯一的。
email.email|验证的字段必须符合 e-mail 地址格式。
introduction.max|验证中的字段必须小于或等于 value。

#### 公共错误提示
```php
# resources/views/common/error.blade.php

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <h4>有错误发生：</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

+ 视图引用
```php
@include('common.error')
```

#### 多语言翻译
```bash
# 安装 laravel-lang
composer require "overtrue/laravel-lang:~3.0"
```

#### 提示信息
```php
# 修改 UserRequest，新增方法 messages()
# app/Http/Requests/UserRequest.php
public function messages()
{
    return [
        'name.unique' => '用户名已被占用，请重新填写',
        'name.regex' => '用户名只支持英文、数字、横杆和下划线。',
        'name.between' => '用户名必须介于 3 - 25 个字符之间。',
        'name.required' => '用户名不能为空。',
    ];
}
```
> messages() 方法是 表单请求验证（FormRequest）一个很方便的功能，允许自定义具体的消息提醒内容，键值的命名规范 —— 字段名 + 规则名称，对应的是消息提醒的内容。


#### 视图消息提醒
```php
# resources/views/layouts/_message.blade.php
@if (Session::has('message'))
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('message') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('success') }}
    </div>
@endif

@if (Session::has('danger'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('danger') }}
    </div>
@endif
```

+ 视图引用
```php
@include('layouts._message')
```

#### 模型字段添加
```php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
```


#### 中文显示友好时间戳
```php
# app/Providers/AppServiceProvider.php
public function boot()
{
    //

    \Carbon\Carbon::setLocale('zh');
}
```