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
|$table->bigInteger('votes');|等同于数据库中的BIGINT类型|
|$table->binary('data');|等同于数据库中的BLOB类型|
|$table->boolean('confirmed');|等同于数据库中的BOOLEAN类型|
|$table->char('name', 4);|等同于数据库中的CHAR类型|
|$table->date('created_at');|等同于数据库中的DATE类型|
|$table->dateTime('created_at');|等同于数据库中的DATETIME类型|
|$table->decimal('amount', 5, 2);|等同于数据库中的DECIMAL类型，带一个精度和范围|
|$table->double('column', 15, 8);|等同于数据库中的DOUBLE类型，带精度, 总共15位数字，小数点后8位.|
|$table->enum('choices', ['foo', 'bar']);|等同于数据库中的 ENUM类型|
|$table->float('amount');|等同于数据库中的 FLOAT 类型|
|$table->increments('id');|数据库主键自增ID|
|$table->integer('votes');|等同于数据库中的 INTEGER 类型|
|$table->json('options');|等同于数据库中的 JSON 类型|
|$table->jsonb('options');|等同于数据库中的 JSONB 类型|
|$table->longText('description');|等同于数据库中的 LONGTEXT 类型|
|$table->mediumInteger('numbers');|等同于数据库中的 MEDIUMINT类型|
|$table->mediumText('description');|等同于数据库中的 MEDIUMTEXT类型|
|$table->morphs('taggable');|添加一个 INTEGER类型的 taggable_id 列和一个 STRING类型的 taggable_type列|
|$table->nullableTimestamps();|和 timestamps()一样但允许 NULL值.|
|$table->rememberToken();|添加一个 remember_token 列： VARCHAR(100) NULL.|
|$table->smallInteger('votes');|等同于数据库中的 SMALLINT 类型|
|$table->softDeletes();|新增一个 deleted_at 列 用于软删除.|
|$table->string('email');|等同于数据库中的 VARCHAR 列.|
|$table->string('name', 100);|等同于数据库中的 VARCHAR，带一个长度|
|$table->text('description');|等同于数据库中的 TEXT 类型|
|$table->time('sunrise');|等同于数据库中的 TIME类型|
|$table->tinyInteger('numbers');|等同于数据库中的 TINYINT 类型|
|$table->timestamp('added_on');|等同于数据库中的 TIMESTAMP 类型|
|$table->timestamps();|添加 created_at 和 updated_at列.|
|$table->uuid('id');|等同于数据库的UUID|

#### 修改列
```php
# 新生成migration里up方法的Create就会变成table
Schema::table('users', function ($table) {

});

# 将name列的尺寸从 25 增加到 50：
$table->string('name', 50)->change();

# 还可以修改该列允许 NULL 值：
$table->string('name', 50)->nullable()->change();

# 重命名列
$table->renameColumn('from', 'to'); # 暂不支持 enum类型的列的重命名。

# 删除列
$table->dropColumn('votes');

# 删除多个列：
$table->dropColumn(['votes', 'avatar', 'location']);
```