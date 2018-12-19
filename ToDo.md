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
##runTime 的权限设置

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

