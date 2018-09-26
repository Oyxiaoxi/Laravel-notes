### 任务调度列表

```php
# 1. 加载Console内核
\app()->make(Illuminate\Contracts\Console\Kernel::class);

# 2.  获取计划任务列表
$scheduleList = app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events();
```