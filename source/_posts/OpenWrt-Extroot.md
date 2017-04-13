---
title: OpenWrt Extroot
date: 2017-04-13 13:45:23
tags: Linux
---

## Prepare Tool
    opkg update
    opkg install block-mount kmod-usb-storage kmod-fs-ext4 e2fsprogs
<br>   
## Format USB Storage
    mkfs.ext4 /dev/sda1
<br>

<!--more-->
## Extroot

### Transfer Data

    mount /dev/sda1 /mnt
    mkdir /tmp/root
    mount -o bind / /tmp/root
    cp /tmp/root/* /mnt -a
    umount /tmp/root
    umount /mnt
    
### Config fstab
    
    vim /etc/config/fstab
    
        config global automount
                option from_fstab 1
                option anon_mount 1
        
        config global autoswap
                option from_fstab 1
                option anon_swap 0
        
        config mount
                option target   /       # root
                option device   /dev/sda1
                option fstype   ext4
                option options  rw,sync
                option enabled  1           
                option enabled_fsck 0
        
        config swap
                option device   /dev/sda2
                option enabled  0
<br>    
### Starup -> reboot -> check

    /etc/init.d/fstab enable
    
    reboot
    
    root@Gargoyle:~# df -h
        Filesystem                Size      Used Available Use% Mounted on
        rootfs                    6.2G     59.7M      5.8G   1% /
        /dev/root                 6.0M      6.0M         0 100% /rom
        tmpfs                    61.6M      1.0M     60.6M   2% /tmp
        /dev/sda1                 6.2G     59.7M      5.8G   1% /
        tmpfs                   512.0K         0    512.0K   0% /dev

<br>
> **这个打赏二维码好像有什么不对**

**支付宝** 
![支付宝][支付宝]

**微信**  
![微信][微信]

[支付宝]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/alipay/%E6%B3%A2%E5%B0%94%E5%BE%B3_alipay.gif?imageView2/1/w/200/h/200

[微信]: https://of4jd0bcc.qnssl.com/Blog/%E6%89%93%E8%B5%8F/wechat/girl_wechat.gif?imageView2/1/w/200/h/200
