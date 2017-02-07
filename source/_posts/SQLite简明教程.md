---
title: SQLite简明教程
date: 2017-01-14 01:39:34
tags: 爬虫
---

| Python type        | SQLite type |
| :----------------- | :---------- |
| None               | NULL        |
| int                | INTEGER     |
| long               | INTEGER     |
| float              | REAL        |
| str (UTF8-encoded) | TEXT        |
| unicode            | TEXT        |
| buffer             | BLOB        |
<br>
<!--more-->

> 数据库操作无外乎"**增改删查**"

## 增

    import sqlite3
    con = sqlite3.connect(':memory:')  # con = sqlite3.connect(":memory:") create a database in RAM
    con.cursor()  # 游标, 类似指针, 执行数据处理操作
    
    con.execute('''CREATE TABLE Test (Company text NOT NULL, OrderNumber real NOT NULL)''')  # 创建一个数据表, PRIMARY KEY 为定义约束主键的关键字
    
    con.execute('INSERT INTO Test VALUES (?, ?)', ('Apple', 100))  # 插入一组数据
    con.execute('INSERT INTO Test VALUES (?, ?)', ('Bot', 200))
    con.execute('INSERT INTO Test VALUES (?, ?)', ('Apple', 300)) # 若有约束主键的情况下, 插入与主键相同的元素组, 相当于无效操作
    
    temp = [('Cherry', 600), ('DigitalOcean', 1000)]  # 该形式可以用executemany一次插入多组数据
    con.executemany('INSERT INTO Test VALUES (?, ?)', temp) 
    
    con.commit()  # 执行后数据表才会变更(提交)
    
**数据表图**

| Company        | OrderNumber |
|----------------|-------------|
| 'Apple'        | 100         |
| 'Bot'          | 200         |
| 'Apple'        | 300         |
| 'Cherry'       | 600         |
| 'DigitalOcean' | 10000       |
<br>

## 改

    con.execute('UPDATE Test SET OrderNumber=666 WHERE Company="Cherry"')
    
    
**修改后数据表图**

| Company        | OrderNumber |
|----------------|-------------|
| 'Apple'        | 100         |
| 'Bot'          | 200         |
| 'Apple'        | 300         |
| 'Cherry'       | 666         |
| 'DigitalOcean' | 10000       |
<br>


## 删
> SQLite 的 DISTINCT 关键字与 SELECT 语句一起使用，来消除所有重复的记录，并只获取唯一一次记录

    con.execute('DELETE FROM Test WHERE Company="Apple" AND OrderNumber=300')  # 除了AND还有OR关键字操作

**修改后数据表图**

| Company        | OrderNumber |
|----------------|-------------|
| 'Apple'        | 100         |
| 'Bot'          | 200         |
| 'Cherry'       | 666         |
| 'DigitalOcean' | 10000       |
<br>

## 查

> WHERE 用于指定从一个表或多个表中获取数据的条件
  ORDER BY 基于一个或多个列按升序或降序顺序排列数据
  SELECT 取数据, 或添加条件筛选数据


    c = con.cursor()
    c.execute('SELECT * FROM Test') # 获取表中所有数据
    c.fetchall()
    c.execute('SELECT Company, OrderNumber FROM Test ORDER BY Company ASC, OrderNumber DESC')  # 根据Company升序, OrderNumber降序

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![alipay][99]

**微信**  
![wechat][100]


  [99]:  https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E9%85%9A%E9%85%9E%E7%93%9C%E6%95%B2%E7%A2%97_alipay.gif?imageView2/1/w/200/h/200
  [100]:  https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200