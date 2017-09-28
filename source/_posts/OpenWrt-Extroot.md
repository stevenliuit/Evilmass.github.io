---
title: OpenWrt Extroot
date: 2017-04-13 13:45:23
tags: Openwrt
---

overlay的展现形式，按照 Openwrt 文档上的描述，其实是有两种:

1. 完全拷贝，将文件系统完全拷贝到外部存储中，然后系统启动的时候，直接跳转到外部存储设备中启动
2. 将文件系统的内容拷贝到外部存储设备中，然后编辑原文件系统中的fstab文件，让其在系统启动之后运行时将外部设备中的文件系统挂载为系统的根文件系统，覆盖一开始的文件系统

推荐 rootfs 挂载 '/overlay' 的模式（本文使用该方法），不再推荐 '/' 的模式。如果U盘损坏，拔下U盘可以启动系统

<!--more-->
## Requirement
    opkg update
    opkg install block-mount kmod-usb-storage kmod-fs-ext4 e2fsprogs

## U盘分区操作
较为合理的做法是分成3个区

    extroot -> 拓展系统空间
    swap -> 内存交换    
    disk -> 数据存放空间
 
这里只分了 extroot 和 swap

windows 分区推荐使用 `MiniTool Partition Wizard` 工具

![Partition][Partition]
<br>
## Extroot
### 挂载U盘

    mkdir /mnt/sda1
    mount -t ext4 /dev/sda1 /mnt/sda1
    df -h

### 将系统的/overlay目录下的所有的内容都拷贝到/mnt/sda1里面去

    tar -C /overlay -cvf - . | tar -C /mnt/sda1 -xf -
### 查看 UUID


    block info

### 配置 fstab

    /etc/init.d/fstab enable
    
    vim /etc/config/fstab
    
    config 'global'
        option  anon_swap       '0'
        option  anon_mount      '0'
        option  auto_swap       '0'
        option  auto_mount      '0'
        option  delay_root      '5'
        option  check_fs        '0'

    config 'mount'
        option  target  '/overlay'
        option  device  '/dev/sda1'
        option  uuid    'UUID'
        option  fstype  'ext4'
        option  options 'rw,sync'
        option  enabled '1'
        option  enabled_fsck 0

auto_swap 和 auto_mount 都设置为0，如果置为1的话，那么系统就会自动的去找和 mount 相应的设备，如果USB外设有多个分区的话，可能我们在  'mount' 里面的配置就会不起作用
<br>    
### 重启

    reboot
    
    root@OpenWrt:~# df -h
    Filesystem                Size      Used Available Use% Mounted on
    rootfs                    6.7G     33.0K      6.3G   0% /
    /dev/root                 2.3M      2.3M         0 100% /rom
    tmpfs                    61.5M    496.0K     61.1M   1% /tmp
    /dev/sda6                 6.7G     33.0K      6.3G   0% /overlay
    overlayfs:/overlay        6.7G     33.0K      6.3G   0% /
    tmpfs                   512.0K         0    512.0K   0% /dev


<br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E6%B3%A2%E5%B0%94%E5%BE%B3_alipay.gif?imageView2/1/w/200/h/200

[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200

[Partition]: https://of4jd0bcc.qnssl.com/Openwrt/Partition.png

