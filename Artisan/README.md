### Artisan
Artisan 是 Laravel 自带的命令行接口，它为我们在开发过程中提供了很多有用的命令。想要查看所有可用的 Artisan 命令，可使用 list 命令：
```bash 
php artisan list # laravel 项目目录下
php artisan help migrate # 使用帮助
php artisan tinker # 扩展调试    
```

### 自定义生成命令
```bash
# app/Console/Commands ，会被 composer 预先加载
php artisan make:command SendEmails
```

### Artisan list
```bash 

app:name # 设置应用命令空间

auth:clear-resets # 刷新过期密码且重设令牌

cache:clear # 刷新应用缓存
cache:forget # 从一个项目中移除缓存
cache:table # 创建一个缓存数据库表
config:cache # 预加载缓存文件
config:clear # 删除配置缓存文件

db:seed # 发送数据库的详细记录

event:generate # 在记录上生成错过的事件和基础程序

key:generate # 设置Key，一般是在 .env 文件中

make:auth # 默认基本登陆，注册功能
make:channel # 创建一个新的类
make:command # 自定义命令
make:controller # 创建一个控制器
make:event # 创建事件
make:exception # 创建异常类
make:factory # 创建模型工厂
make:job # 创建队列
make:listener # 监听生产环境
make:mail # 创建邮件类
make:middleware # 创建中间件
make:migration # 创建表
make:model # 创建模型
make:notification # 创建站内信
make:policy
make:provider # 创建服务类
make:request # 创建一个新的表单请求类
make:resource
make:rule # 创建新的验证规则
make:seeder
make:test

migrate:fresh # 删除所有表并重新运行所有迁移
migrate:install # 创建迁移存储库
migrate:refresh # 重置并重新运行所有迁移
migrate:reset # 回滚所有数据库迁移
migrate:rollback # 回滚上次数据库迁移
migrate:status # 显示每个迁移的状态

notifications:table # 为通知表创建迁移

package:discover # 重建缓存的软件包清单

queue:failed # 列出所有失败的队列作业
queue:failed-table # 为失败的队列作业数据库表创建迁移
queue:flush # 刷新所有失败的队列作业
queue:forget # 删除失败的队列作业
queue:listen # 监听失败的队列作业
queue:restart # 在当前作业之后重新启动队列工作守护进程
queue:retry # 重试失败的队列作业
queue:table # 为队列作业数据库表创建迁移
queue:work # 开始在队列上处理作为守护进程的作业

route:cache # 创建一个路线缓存文件，以加快路线注册
route:clear # 移除路由缓存文件
route:list # 列出所有注册的路线

schedule:run # 运行自定义命令

session:table # 为会话数据库表创建迁移

storage:link # 创建一个从“public / storage”到“storage / app / public”的符号链接

vendor:publish 

view:cache # 编译所有应用程序的刀片模板
```


