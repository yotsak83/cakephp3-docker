# CakePHP3 with docker
## Overview
Build this repo's docker environment on local to try the [official tutorial](https://book.cakephp.org/3.0/en/tutorials-and-examples.html).

* Nginx 1.17.3
* php 7.3
* CakePHP 3.8.*


## How to build
* Build docker containers and start a CakePHP project

```bash
$ cd /YOUR/PROJECT
$ git clone https://github.com/yotasasaki/cakephp3-docker.git .
$ cd docker/
$ docker-compose up -d
$ docker ps --format="{{.Names}}"
cake-nginx
cake-mysql
cake-phpfpm
$ docker exec -it cake-phpfpm bash
// in phpfpm container
/var/www/html # rm cms/empty && composer self-update && composer create-project --prefer-dist cakephp/app:^3.8 cms
... composer install ...
/var/www/html # exit
```

* Follow [this doument](https://book.cakephp.org/3.0/en/tutorials-and-examples/cms/database.html#database-configuration),  replace the values below. 

```php
// config/app.php
    'Datasources' => [
        'default' => [
            // ... more configurations
            'host' => 'cake-mysql',
            'username' => 'cakephp',
            'password' => 'password',
            'database' => 'cake_cms',
```

* Access http://0.0.0.0:8080

## Step execution by Xdebug using VSCode
* Add extention "PHP Debug"
* Select "Run > Add configuration..." to open launch.json
* Add block below
```
{
    "name": "Listen for XDebug on CakePHP3",
    "type": "php",
    "request": "launch",
    "port": 9013,
    "pathMappings": {
        "/var/www/html/cms": "${workspaceRoot}/cms",
        "/var/www/html/cms/webroot": "${workspaceRoot}/cms/webroot",
        "/var/www/html/cms/bin/cake": "${workspaceRoot}/cms/bin/cake"
    },
    "xdebugSettings": {
        "max_children": 128,
        "max_data": 1024,
        "max_depth": 10
    },
    "log": true
},
```