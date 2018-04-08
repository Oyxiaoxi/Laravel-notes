### Getting Started

#### 服务器环境要求
+ PHP >= 7.1.3
+ PHP OpenSSL 扩展
+ PHP PDO 扩展
+ PHP Mbstring 扩展
+ PHP Tokenizer 扩展
+ PHP XML 扩展
+ PHP Ctype 扩展
+ PHP JSON 扩展

#### 安装 Laravel
```bash
brew install composer
```

#### 添加 composer
```bash
vim ~/.zshrc
# composer
export PATH="/Applications/MAMP/bin/php/php7.1.8/bin:$PATH:$HOME/.composer/vendor/bin/:$PATH"
```

#### Install Laravel 
```bash
composer global require "laravel/install"
```

#### new projects
```bash 
laravel new projects-name
```

#### server
```bash 
php artisan serve
```

#### 配置 Laravel

+ nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

+ 获取环境变量配置值
```php
'debug' => env('APP_DEBUG', false),
```

+ 判断当前应用环境
```php
$environment = App::environment();

if (App::environment('local')) {
    // The environment is local
}

if (App::environment('local', 'staging')) {
    // The environment is either local OR staging...
}
```

#### 缓存配置文件

+ 应用每次上线前需要执行
```bash
php artisan config:cache
```

#### 维护模式
```bash
php artisan down # 关闭会抛出 503 异常 
php artisan down --message="Upgrading Database" --retry=60 # 传递自定义消息
php artisan up # 开启
```
> 注：你可以通过定义自己的模板来定制默认的维护模式模板，自定义模板视图位于 resources/views/errors/503.blade.php。
