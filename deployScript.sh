#!/bin/sh
# Ejecucion de ejemplo: sh deployScript.sh 192.168.0.2 /opt/zonalesContentApi-1.1.0/ 200.69.225.53 27018 27017 38080 38081 /var/www/html/zonales-qa/ /opt/tomcat6-qa/webapps/ zonalesContentApi.tar.gz fb.tar.gz /var/www/html/

# DEPLOY DE NODE
cp -p $10 $2
cd $2
mv $10 ..
cd ..
find $2 ! -type d -print | egrep -v '/,|%$|node_modules|svn|~$|\.old$|SCCS|/core$|\.o$|\.orig$' > Exclude
cat Exclude | xargs -l rm -rf
tar xfz $10

# DEPLOY DE FACEBOOK
cp -p $11 $12
cd $12
rm -rf $12/fb
tar xfz $11

# SERVICIOS DE NODE
grep "$1" -lHIRZi $2 | xargs -0 -l sed -i "s/$1/$3/ig"
grep "localhost/crawl" -lHIRZi $2 | xargs -0 -l sed -i "s/localhost\/crawl/localhost:$4\/crawl/ig"
grep "$5" -lHIRZi $2 | xargs -0 -l sed -i "s/$5/$4/ig"
grep "$6" -lHIRZi $2 | xargs -0 -l sed -i "s/$6/$7/ig"

# ZONALES JOOMLA
grep "$6" -lHIRZi $8 | xargs -0 -l sed -i "s/$6/$7/ig"
grep "$5" -lHIRZi $8 | xargs -0 -l sed -i "s/$5/$4/ig"
grep "localhost/crawl" -lHIRZi $8 | xargs -0 -l sed -i "s/localhost\/crawl/localhost:$4\/crawl/ig"
grep "$1" -lHIRZi $8 | xargs -0 -l sed -i "s/$1/$3/ig"

# WEBAPPS DE TOMCAT
grep "$6" -lHIRZi $9 | xargs -0 -l sed -i "s/$6/$7/ig"
grep "$5" -lHIRZi $9 | xargs -0 -l sed -i "s/$5/$4/ig"
grep "localhost/crawl" -lHIRZi $9 | xargs -0 -l sed -i "s/localhost\/crawl/localhost:$4\/crawl/ig"
grep "$1" -lHIRZi $9 | xargs -0 -l sed -i "s/$1/$3/ig"

sh $9../bin/shutdown.sh
sh $9../bin/startup.sh
sh /root/start-node.sh
service httpd restart