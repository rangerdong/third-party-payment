# third-party-payment
基于php开发的第三方支付系统
基于php7.1，laravel5.4，mysql5.7, linux centos6.9， naginx环境开发

# 程序安装

```$bash
$ git clone https://github.com/rangerdong/third-party-payment.git

$ cd third-party-payment

# 安装依赖包
$ composer install 

# 迁移数据库文件
$ php artisan migrate  

# 填充数据库 若出现找不到Class 运行 composer dump-autoload 命令再执行
$ php artisan db:seed

```
然后 游览器打开 http://yoursite/admin 

admin用户帐密: 

`admin` 

`admin`

# 相关内容

[`laravel-admin`](https://github.com/z-song/laravel-admin.git)  [查看文档](http://laravel-admin.org/docs/#/zh/)

  
  
 
