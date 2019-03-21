##set thinkphp pathinfo for nginx
1. 添加/etc/nginx/sites-available/default中
```
	location /atsTp5/ {
		index index.php;
		if (!-e $request_filename){
			rewrite ^/atsTp5/(.*)$ /atsTp5/index.php?s=/$1 last;
			break;
		}
	}
```

2:修改index.php到根目录
// 应用目录
define('APP_PATH', __DIR__.'/application/');
// 加载框架引导文件
require './thinkphp/start.php';

---
##runTime & log 的权限设置

 1://        return $this->fetch('common/footer');
 2: //        return $this->fetch();

---
## save mode mysql
  SET SQL_SAFE_UPDATES=0;

---
##  设置www-data用户为root权限，
##### 1: 虽然在nginx里面设置了启动为root，但是php-fpm还是为www-data用户组，
   具体在/etc/php/5.6/fpm/php-fpm.conf里面www.conf的配置设置。比较麻烦
##### 2：修改www-data 权限   
    1) chmod 757 sudoers
    2)  vi /etc/sudoers
    3)  www-data ALL=NOPASSWD: ALL
    4) chmod 0440 /etc/sudoers

## memo
1. DB:query () 返回的是二维数组。不包括模型数据。
2. 对外接口可以继承控制器，直接使用Request对象。内部使用，可以不继承。

## ftp 设定
1. 需要对runtime中ftp文件夹加777权限
  - 添加ftp_mail.txt
  - 添加unix_time.txt
  
## workman

1. 考虑到一个进程下加入比较定时器，而这个任务比较繁重。会出现阻塞的情况，，所以就新建另外一个线程。
2. 根据workman手册，可以建立另一个workman实例。

## thinkphp 
1. 获取二级配置文件内容为： 'cookie.expire'

## postfix

1. 搭建postfix。本人也是稀里糊涂地搭建了smtp服务器。。踩了坑有点多
2. 在命令打入echo， mail验证的时候需要不能随便乱输入文字，否则qq邮箱会收不到。
3. 用php写代码的时候，需要将mail_from 这个加入qq邮箱的域名白名单，否则接收不到邮件。

## TODO list
1. 在saveFtp中ftp_mail.txt