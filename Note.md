##set thinkphp pathinfo for nginx
1:
	location /atsTp5/ {
		index index.php;
		if (!-e $request_filename){
			rewrite ^/atsTp5/(.*)$ /atsTp5/index.php?s=/$1 last;
			break;
		}
	}

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

## TODO list
0-1. Baseline Image 需要添加到JumpStart中
0-2. Execute Job 需要1,2,5,6
0-3. 添加tool时需要增加对192.168.40.XXX check
1. 在steps中存储test result path 
2. 添加查询task功能
3. 修改tp5的README.md为本项目介绍。
4. 把重复的js一块内容删除
5. 添加releasenote功能。
6. 修改public/img的名字
7. 修改chart的名字和title, 添加提示之类的
8. 添加fakeLoader.js, 用于提交或者修改steps