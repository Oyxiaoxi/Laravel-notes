### Migration

#### 创建Migration
```bash
# 创建表
php artisan make:migration create_users_table --create=users

# 修改表
php artisan make:migration add_votes_to_users_table --table=users
```
> 这样在/database/migrations里就多了一些文件

#### 创建列
```php
Schema::create('users', function ($table) {
    $table->increments('id');
    $table->string('name');
});
```

#### 对应的命令和相应数据库里的类型
|命令   | 描述 |
|:----:|:----:|
|$table->bigIncrements('id');|自增ID，类型为bigint|