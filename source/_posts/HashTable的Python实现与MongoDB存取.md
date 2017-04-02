---
title: HashTable的Python实现与MongoDB存取
date: 2017-03-23 17:32:10
tags: Python
---

## Requirement
*  [MongoDB][MongoDB]
下载好对应平台版本的MongoDB，设置MongoDB目录到环境变量，在命令行（Windows下需要管理员权限）执行

        mongod --dbpath "C:\Program Files\MongoDB\db"
        
* [pymongo][pymongo]

        pip install pymongo==3.4.0

<!--more-->

<br>
## HashTable原理
一个典型的 Hash 算法是将整数除以一个常量并且取余法，得到的余数就是散列值。然而多个数据的散列值可能相等（“碰撞”），当进行数据插入到哈希表的时候，如果发现该数据的散列值在之前已经存在，则把散列值一样的数据做成一个链表，把最新的数据插入到该链表的尾部，而该链表本身则会把插入到 “槽” 中，这是一种由数组和链表组合的数据结构。
![HashTable][HashTable]

    #!/usr/bin/env python
    # -*- coding: utf-8 -*-
    
    num = 10
    
    
    # 一个数据节点
    class Node(object):
        def __init__(self, data):
            self.data = data
            self.next_node = None
    
        def set_next(self, node):
            self.next_node = node
    
        def get_next(self):
            return self.next_node
    
        def get_data(self):
            return self.data
    
        def data_equals(self, data):
            return self.data == data
    
    
    class HashTable(object):
        def __init__(self):
            self.value = [None] * num
    
        def insert(self, data):
            if self.search(data):
                return True
    
            i = data % num
            node = Node(data)
            if self.value[i] is None:
                self.value[i] = node
                return True
            else:
                head = self.value[i]
                while head.get_next() is not None:
                    head = head.get_next()
                head.set_next(node)
                return True
    
        def search(self, data):
            i = data % num
            if self.value[i] is None:
                return False
            else:
                head = self.value[i]
                while head and not head.data_equals(data):
                    head = head.get_next()
                if head:
                    return head
                else:
                    return False
    
        def delete(self, data):
            if self.search(data):
                i = data % num
                if self.value[i].data_equals(data):
                    self.value[i] = self.value[i].get_next()
                else:
                    head = self.value[i]
                    while not head.get_next().data_equals(data):
                        head = head.get_next()
                    head.set_next(head.get_next().get_next())
                return True
            else:
                return False
    
        def echo(self):
            i = 0
            for head in self.value:
                print(str(i) + ':\t',)
                if head is None:
                    print(None,)
                else:
                    while head is not None:
                        print(str(head.get_data()) + ' ->',)
                        head = head.get_next()
                    print(None,)
                print('')
                i += 1
            print('')
    
    
    if __name__ == '__main__':
        hashTable = HashTable()
        large_data = []
        for x in range(10001):
            hashTable.insert(x)
        hashTable.echo()

<br>
## 还好我们有Python
> 字典是python中唯一的映射类型，采用键值对（key-value）的形式存储数据。Python对key进行**哈希函数运算**，根据计算的结果决定value的存储地址，所以字典是无序存储的，且key必须是可哈希的
    
| 操作          | 平均情况 |
|---------------|----------|
| 取元素        | O(1)     |
| 更改元素      | O(1)     |
| 删除元素      | O(1)     |

那么只要把数据填充到字典里面，剩下的工作就是对MongoDB数据库进行读写

    #!/usr/bin/env python
    # -*- coding: utf-8 -*-
    
    import pymongo
    
    
    class HashTable(object):
        def __init__(self, host, port, db_name, table_name):
            try:
                self.connection = pymongo.MongoClient(host, port)
                self.db = self.connection[db_name]
                self.table = self.db[table_name]
            except:
                print("could not connect to server")
    
        def __del__(self):
            self.connection.close()
    
        def search(self, values={}):
            if not values:
                return False
            else:
                return self.table.find(values)
    
        def echo(self):
            for i in self.table.find():
                print(i)
    
        def insert(self, values={}):
            if not values:
                print("no values to insert")
                return False
            else:
                try:
                    # self.table.create_index(str(values.keys()), unique=True)
                    self.table.insert(values)
                    return True
                except:
                    print("index exist, insert failed")
                    return False
    
        def remove(self, values={}):
            if not values:
                print("no values to delete")
                return False
            else:
                self.table.remove(values)
                return True
    
        def update(self, values={}, new_values={}):
            if not values:
                print("no values to update")
                return False
            else:
                self.table.update(values, new_values)
                return True
    
    
    if __name__ == '__main__':
        hashTable = HashTable("localhost", 27017, "HashTable_db", "HashTable")
        hashTable.db.drop_collection(hashTable.table)
        # 添加多条数据到集合中
        users = [{"name": "name1", "age": "1"}, {"name": "name2", "age": "2"}, {"name": "name3", "age": "3"}]
        hashTable.insert(users)  # 插入数据
        hashTable.echo()
        print("\n----insert new user----")
        hashTable.insert({"name": "Evilmass", "tag": "delete"})
        hashTable.echo()
    
        print("\n----update new user----")
        hashTable.update({"name": "Evilmass"}, {"name": "Evilmass", "tag": "delete", "sex": "man"})  # 更新数据
        hashTable.echo()
    
        print("\n----remove new user----")
        hashTable.remove({"tag": "delete"})  # 删除数据
        hashTable.echo()
    
        # large_data = []
        # for x in range(100001):
        #     data = {"num": x}
        #     large_data.append(data)
        # hashTable.insert(large_data)
        # print(hashTable.search({"num": 50000}))
    
        hashTable.__del__()

<br>
## Reference

1. [散列函数 - 维基百科，自由的百科全书][1]
2. [一步一步写算法（之 hash 表）][2]

<br><br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/shakalaka_ailipay.gif?imageView2/1/w/200/h/200

[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/%E9%85%9A%E9%85%9E%E7%93%9C_wechat.gif?imageView2/1/w/200/h/200
[1]: https://zh.wikipedia.org/wiki/%E6%95%A3%E5%88%97%E5%87%BD%E6%95%B8
[2]: http://blog.csdn.net/feixiaoxing/article/details/6885657
[HashTable]: https://of4jd0bcc.qnssl.com/%E6%9D%82/HashTable.png
[MongoDB]: https://www.mongodb.com/download-center#community
[pymongo]: https://pypi.python.org/pypi/pymongo/3.4.0