# Laravel Supervisor

## Installation
```
composer require simlux/laravel-supervisor:dev-master
```

## Supervisor

### Documentation
http://supervisord.org/configuration.html

### XML-RPC
Install PHP XML-RPC module: 
```
sudo apt-get install php7.2-xmlrpc
```

Add to */etc/supervisor/supervisord.conf*:
```
[inet_http_server]
port = 127.0.0.1:9001
username = ###
password = ###
```

### PHP libraries:
- https://github.com/ihor/SupervisorXMLRPC
- https://github.com/supervisorphp/supervisor