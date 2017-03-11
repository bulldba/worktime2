#worktime2
一款轻量级研发项目管理工具，主要针对中小互联网敏捷开发团队，页面清爽简单，功能精简好用。

##使用框架 laravel 5.1
相关文档可以参考<br>
http://laravel-china.org/docs/5.1<br>
http://www.golaravel.com/laravel/docs/5.1/<br>

##基本配置
.env 文件可以修改数据库配置<br>
mysql 里面要先创建一个数据库，例如：CREATE DATABASE IF NOT EXISTS worktime DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;<br>
执行命令创建数据库表 php artisan migrate<br>
chmod 777 -R storage<br>
开发环境下，也可以使用php自带的webserver 命令：php artisan serve --port=8080<br>

##我使用的shell history
```Java
pwd
#/data/www

yum install git
svn co https://github.com/aoktian/worktime2.git
git clone https://github.com/aoktian/worktime2.git
ls
cd worktime2/
#mysql 创建数据库
#CREATE DATABASE IF NOT EXISTS worktime2 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

mv .env.example .env
vim .env
# 改数据库配置

php artisan key:generate
chmod 777 -R storage/
php artisan migrate

#改 nginx 配置
vim /usr/local/programs/nginx/conf/nginx.conf

root   /data/www/worktime2/public;
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

#重启nginx
/usr/local/programs/nginx/sbin/nginx -s reload

```

##联系我
email: aoktian@foxmail.com
