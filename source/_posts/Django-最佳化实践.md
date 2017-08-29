---
title: Django 最佳化实践
date: 2017-08-26 19:04:50
tags: Python
---

![django-model][django-model]
<br>
**东西只有在用的时候学的最快**

说来惭愧，在学校除了 Python 也不怎么用其他语言，emmmmmmm，导致大多编程科目如 **C、C++、Java、PHP** 都是从入门到放弃的样子（苦笑
<!--more-->

## 数据库
MySQL、MongoDB、SQLite都试过，后来还是选了MySQL，数据量小的项目还是SQLite好用，还不用装额外的库
上一篇我们获取到了形如 **IP:PORT** 的代理，那么从最简单的开始：创建数据库并插入一条数据

    mysql -uroot -p
    
    create database django;
    
    grant all privileges on django.* to 'user'@'%' identified by 'password';
    
    create table django.httpProxy(proxyAddress varchar(21) primary key not null);
    
    use django;
    
    insert into httpProxy(proxyAddress) values ('1.1.1.1:8080');
  
![create][create]
<br>
    
## Django
参考 [virtualenv简明使用教程][virtualenv简明使用教程]，我们创建一个独立环境
### 安装
    pip install django pymysql
    
**项目 VS APP**
一个项目可包含多个 APP，一个 APP 也能被多个项目使用
APP 是指完成一些功能的 Web 应用，比如博客系统,公共记录的数据库或者是一个简单的投票系统。而项目则是一个网站的所有配置文件 + 多个 APP 的集合

### 创建项目
    django-admin startproject mysite

### 在项目内创建APP
    cd mysite
    django-admin startapp app

![tree][tree]
<br>
### 修改配置
#### Settings
修改 `mysite\settings.py`
![setting1][setting1]
<br>
![setting2][setting2]
<br>
#### Database
![setting_database][setting_database]
<br>
PS. 因为这里用的是 Python 3 版本的 pymysql，所以要将 django 默认调用 Python 2 版本的 MySQLdb 替改为 pymysql

在 mysite\\_\_init\_\_.py 文件中添加

    import pymysql
    pymysql.install_as_MySQLdb() 

迁移数据库

    cd mysite
    python manage.py makemigrations
    python manage.py migrate

如果出现数据库某项操作没有权限，请再次添加

#### URLS & Views
这里简单讲下我的理解：
1. mysite\urls：站点处理所有 APP\urls
2. APP\views：定义函数来得到 APP 不同的页面（处理 HttpRequest，并回传 HttpResponse 给 APP\urls）
3. APP\urls 则根据规则要执行哪个 views function 来渲染页面

引用总结：Django 需要知道 URL 与 view 的对应关系，通常定义在 urls.py，包含了一连串的规则 (URL patterns)，Django 收到 request 时，会一一比对 URL conf 中的规则，决定要执行哪个 view function [1]

**一图胜千言**
![urls][urls]

那么先写出 mysite\app 的 url patterns
![mysite_urls][mysite_urls]
<br>
新建 app\urls.py，写出要调用 views.py 中的函数，这里是index
![app_urls][app_urls]
<br>
在 app\views.py 补充 index 函数处理的 request 并输出到页面
![views_base][views_base]
<br>

### 启动
    python manage.py runserver  # 可以在 runserver 后面添加端口号以修改默认的 8000 端口
浏览器打开 `http://127.0.0.1:8080/app` 应该就可以看到 Hello Django 的字样了
![hello][hello]
<br>
不过为了规范，一般都会在 APP 下创建一个 templates 和 static 文件夹，HTML 文件放入 templates，静态文件如 css 就放在 static 里面。
上面 setting 操作中已经设置好了 STATIC_ROOT，所以在 HTML 中就可以直接这样调用本地 CSS

    link rel="stylesheet" href="/static/css/bootstrap.css">

我们在 views 中添加 function 来展示我们的 html 页面
![views_advance][views_advance]
<br>
这里我们用字典 result 把变量传给页面，然后创建一个 HTML 页面来输出变量
![html][html]

没错，在 HTML 页面中，把刚才传入的变量加入两个花括号中即可引用

    <html>
    ...
    value: {{value}}
    ...
    </html>

django 还允许我们使用循环和判断等基础函数来处理数据，下面使用了 for 循环输出变量，并在引用循环中的变量来判断是否等于 '1'。

forloop 这个变量能提供一些当前循环进展的信息
first：获取第一个变量， 返回布尔值
last：获取最后一个变量， 返回布尔值
counter0：从0开始计数
counter：从1开始计数

    <html>
    ...
    {% for x in values %}
        value: {{x}}
        {% for y in x %}
            {% if y == '1' %}
                <br>val = {{y}}<br>
            {% endif %}
        {% endfor %}
        <br>cout0: {{forloop.counter0}}<br>
        <br>cout: {{forloop.counter}}<br>
    {% endfor %}
    ...
    </html>

<br>
### 从MySQL获取数据并输出到Django
app\urls：添加匹配规则

    url(r'^show_sql/', views.show_sql, name='show_sql'),

app\models：由于 Django 原生的 SQL 查询比较麻烦，而且限制不少。所以要引入 `connection` 模块重写查询函数

    from django.db import models, connection

    # Create your models here.
    def custom_sql():
        results = []
        cursor = connection.cursor()
        cursor.execute("""SELECT * FROM httpproxy""")
        for cf in cursor.fetchall():
            proxy = cf[0]
            results.append(proxy)
        return results

app\views 添加页面渲染处理

    def show_sql(request):
        tmp = models.custom_sql()
        return HttpResponse(tmp)

<br>
结果如图
![results][results]

<br>
## REF
1. [Django Girls][Django Girls] 很不错的 Django 入门教程，推荐
2. [django + bootstrap 使用网页模板][django + bootstrap 使用网页模板]

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]

[99]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E7%86%8A%E6%9C%AC%E7%86%8A%E8%B6%85%E5%B8%85_alipay.gif?imageView2/1/w/200/h/200
[100]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200


[django-model]: https://of4jd0bcc.qnssl.com/Django/django-model.png
[create]: https://of4jd0bcc.qnssl.com/Django/create.png
[virtualenv简明使用教程]: https://evilmass.cc/2017/08/25/virtualenv-%E4%B8%8E-pip-%E7%AE%80%E6%98%8E%E4%BD%BF%E7%94%A8%E6%95%99%E7%A8%8B/
[tree]: https://of4jd0bcc.qnssl.com/Django/tree.png
[setting1]: https://of4jd0bcc.qnssl.com/Django/setting1.png
[setting2]: https://of4jd0bcc.qnssl.com/Django/setting2.png
[setting_database]: https://of4jd0bcc.qnssl.com/Django/setting_database.png
[urls]: https://of4jd0bcc.qnssl.com/Django/urls.png
[mysite_urls]: https://of4jd0bcc.qnssl.com/Django/mysite_urls.png
[app_urls]: https://of4jd0bcc.qnssl.com/Django/app_urls.png
[views_base]: https://of4jd0bcc.qnssl.com/Django/views_base.png
[html]: https://of4jd0bcc.qnssl.com/Django/html.png
[hello]: https://of4jd0bcc.qnssl.com/Django/hello.png
[output]: https://of4jd0bcc.qnssl.com/Django/output.png
[results]: https://of4jd0bcc.qnssl.com/Django/results.png
[html]: https://of4jd0bcc.qnssl.com/Django/html.png
[database]: https://of4jd0bcc.qnssl.com/Django/database.png
[migrate]: https://of4jd0bcc.qnssl.com/Django/migrate.png

[Django Girls]: https://www.gitbook.com/book/djangogirls/djangogirls-tutorial/details/zh
[django + bootstrap 使用网页模板]: http://www.shuang0420.com/2017/02/23/django%20+%20bootstrap%20%E4%BD%BF%E7%94%A8%E7%BD%91%E9%A1%B5%E6%A8%A1%E6%9D%BF/