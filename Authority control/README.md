### 权限控制

#### 存在隐患
    1. 未登录用户可以访问 edit 和 update 动作，如果你退出登录，以游客身份访问 http://larabbs.test/users/1/edit ：
    2. 登录用户可以更新其它用户的个人信息，如果我们再次注册一个用户：
    3. 登录状态的 2 号用户 Aufree 居然可以访问 1 号用户 Summer 的个人编辑页面，甚至是修改内容。

#### 限制游客访问
> Laravel 中间件 (Middleware) 提供了一种非常棒的过滤机制来过滤进入应用的 HTTP 请求，例如，当使用 Auth 中间件来验证用户的身份时，如果用户未通过身份验证，则 Auth 中间件会把用户重定向到登录页面。如果用户通过了身份验证，则 Auth 中间件会通过此请求并接着往下执行。Laravel 框架默认内置了一些中间件，例如身份验证、CSRF 保护等。所有的中间件文件都被放在项目的 app/Http/Middleware 文件夹中。

#### 修改控制器
```php
# app/Http/Controllers/UsersController.php
public function __construct()
{
    $this->middleware('auth', ['except' => ['show']]);
}
```

>  Laravel 提供的 Auth 中间件在过滤指定动作时，如该用户未通过身份验证（未登录用户），将会被重定向到登录页

#### 用户只能编辑自己的资料
```bash
php artisan make:policy UserPolicy
```

> 所有生成的授权策略文件都会被放置在 app/Policies 文件夹下。

```php
# app/Policies/UserPolicy.php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}
```

> update 方法接收两个参数，第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的用户实例。当两个 id 相同时，则代表两个用户是相同用户，用户通过授权，可以接着进行下一个操作。如果 id 不相同的话，将抛出 403 异常信息来拒绝访问。

    1. 并不需要检查 $currentUser 是不是 NULL。未登录用户，框架会自动为其 所有权限 返回 false；
    2. 调用时，默认情况下，不需要 传递当前登录用户至该方法内，因为框架会自动加载当前登录用户

#### AuthServiceProvider 类中对授权策略进行注册
```php
# app/Providers/AuthServiceProvider.php
protected $policies = [
    'App\Model' => 'App\Policies\ModelPolicy',
    \App\Models\User::class  => \App\Policies\UserPolicy::class,
];
```

#### 修改控制器 edit 和 update
```php
public function edit(User $user)
{
    $this->authorize('update', $user);
    return view('users.edit', compact('user'));
}

public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
{
    $this->authorize('update', $user);
    $data = $request->all();

    if ($request->avatar) {
        $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
        if ($result) {
            $data['avatar'] = $result['path'];
        }
    }

    $user->update($data);
    return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
}
```