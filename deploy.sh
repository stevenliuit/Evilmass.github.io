#!/bin/bash
PORT=4000
WEB_PATH='/home/Evilmass.github.io' #Hexo目录
WEB_USER='root' #用户
WEB_USERGROUP='root' #用户组

echo "Start deployment"
cd $WEB_PATH
echo "pulling source code..."
git reset --hard origin/master
git clean -f
git pull
git checkout master
echo "changing permissions..."
chown -R $WEB_USER:$WEB_USERGROUP $WEB_PATH
NUM=`ps -ef | grep 'hexo' | head -n1 | awk '{print$2}'` #取出hexo进程的pid，请先验证是否得到hexo进程的pid
if [ -n "$NUM" ];then
        echo "kill hexo process pid: $NUM"
    kill -9 $NUM
else
        echo "hexo process not found"
fi
HEXO_BASH=`which hexo`
HEXO_CLEAN=${HEXO_BASH}" clean" 
HEXO_GENERATE=${HEXO_BASH}" generate"            #执行hexo generate命令
HEXO_START_SERVER=${HEXO_BASH}" server -p $PORT &"   #在后台启动hexo服务
echo "HEXO_CLEAN: $HEXO_CLEAN"
eval $HEXO_CLEAN
echo "HEXO_GENERATE: $HEXO_GENERATE"
eval $HEXO_GENERATE
echo "HEXO_START_SERVER: $HEXO_START_SERVER"
eval $HEXO_START_SERVER 
echo "Finished."