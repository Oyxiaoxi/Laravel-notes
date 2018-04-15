### 上传头像

#### 视图编辑
```php
@if($user->avatar)
    <br>
    <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="200" />
@endif
```

> 在 Laravel 中, 可直接通过 请求对象（Request） 来获取用户上传的文件
```php
// 第一种方法
$file = $request->file('avatar');

// 第二种方法，可读性更高
$file = $request->avatar;
```

#### 修改控制器
```php
# app/Http/Controllers/UsersController.php
public function update(UserRequest $request, User $user)
{
    dd($request->avatar);

    $user->update($request->all());
    return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
}
```

#### 视图修改
```php
# resources/views/users/edit.blade.php
# enctype="multipart/form-data"
<form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
```

> 注: 必须在视图文件 form 中填加 enctype="multipart/form-data" ,不然 dd($request->avatar); 打印为空。

#### 保存上图的图片
```php
# app/Handlers/ImageUploadHandler.php
namespace App\Handlers;

class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($file, $folder, $file_prefix)
    {
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID 
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }
}
```

+ app/Handlers 文件夹来存放本项目的工具类 , utility class 是指一些跟业务逻辑相关性不强的类，Handlers 意为 处理器 ，ImageUploadHandler 意为图片上传处理器

#### 控制器修改
```php
# app/Http/Controllers/UsersController.php
public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
{
    $data = $request->all();

    if ($request->avatar) {
        $result = $uploader->save($request->avatar, 'avatars', $user->id);
        if ($result) {
            $data['avatar'] = $result['path'];
        }
    }

    $user->update($data);
    return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
}
```

    1. use App\Handlers\ImageUploadHandler; 因为使用了命名空间，所以需要在顶部加载
    2. $data = $request->all(); 赋值 $data 变量，以便对更新数据的操作；
    3. 注意 if ($result) 的判断是因为 ImageUploadHandler 对文件后缀名做了限定，不允许的情况下将返回 false

```php
if ($request->avatar) {
    $result = $uploader->save($request->avatar, 'avatars', $user->id);
    if ($result) {
        $data['avatar'] = $result['path'];
    }
}
```
    4. $user->update($data); 这一步才是执行更新。

#### 头像显示
```php
# resources/views/users/show.blade.php
<div align="center">
    <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="300px" height="300px">
</div>
```

#### 图片验证
```php
# app/Http/Requests/UserRequest.php
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
            'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200',
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' =>'头像必须是 jpeg, bmp, png, gif 格式的图片',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杆和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
```
    1. rules() 方法中新增了图片比例验证规则 dimensions ，仅允许上传宽和高都大于 200px 的图片；
    2. messages() 方法中新增了头像出错时的提示信息。

#### 图片压缩
```bash
# 安装 ntervention/image
composer require intervention/image
# 配置信息
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5
```

#### 开始压缩或裁剪
```php
# app/Handlers/ImageUploadHandler.php
namespace App\Handlers;

use Image;

class ImageUploadHandler
{
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
```

#### 修改控制器
> 注：ImageUploadHandler 文件中的代码讲解请参考代码注释，此次新增 reduceSize() 方法，以及此方法的调用。

```php
# UsersController
# save() 方法中，新增了 $max_width 参数，用来指定最大图片宽度，修改 UsersController 的 update() 方法中的调用，修改为：
$result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
```