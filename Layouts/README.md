### 基础布局

#### 文件存放位置
+ resources/views/layouts 
+ app.blade.php -- 主要布局文件，项目的所有页面都将继承于此页面；
+ _header.blade.php -- 布局的头部区域文件，负责顶部导航栏区块；
+ _footer.blade.php -- 布局的尾部区域文件，负责底部导航区块；

#### 自定义的辅助方法
```php
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
```

#### 首页展示 

+ 创建控制器
```bash
php artisan make:controller PagesController # 生成的文件会在 app/Http/ 目录下
```

#### 绑定路由
```php
Route::get('/', 'PagesController@root')->name('root');
```

#### Laravel-Mix 
```bash
npm install # 安装项目依赖
npm run watch-poll # 修改文件自动生成，监听
```

> 注：~bootstrap-sass 来在 node_modules 目录下