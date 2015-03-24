# Introduction #

Crear un nueva instancia de mongo replicando las dbs.


# Details #

Creamos un nuevo directorio donde se almacenaran los archivos y logs de Mongo.

> mkdir /var/lib/mongo-qa

> cp -pr /var/log/mongo /var/log/mongo-qa

> chown mongod:mongod /var/lib/mongo-qa

Espedificamos el puerto de escucha de conexiones mongo en QA, utilizamos la opción - dbpath
para indicar el donde se almacenaran los archivos y creamos una nueva instancia de mongo:

> mongod --port 27018 --dbpath /var/lib/mongo-qa --logpath /var/log/mongo-qa/mongod.log --fork

Verificamos la nueva instancia:

> mongo --port 27018

Creamos Dump de mongo en Test:

> mongodump --port 27017

Replicamos la db a la nueva instancia en QA:

> mongorestore --port 27018 dump

Para levantar el servicio en el puerto 27018,creamos en el directorio /etc/init.d el archivo mongod-qa con el siguiente contenido:

  1. /bin/bash

# mongod - Startup script for mongod

# chkconfig: 35 85 15
# description: Mongo is a scalable, document-oriented database.
# processname: mongod
# config: /etc/mongod.conf
# pidfile: /var/run/mongo/mongo.pid

. /etc/rc.d/init.d/functions

# things from mongod.conf get there by mongod reading it



OPTIONS=" -f /etc/mongod2.conf"
SYSCONFIG="/etc/sysconfig/mongod-qa"

mongod=${MONGOD-/usr/bin/mongod}

MONGO\_USER=mongod
MONGO\_GROUP=mongod

. "$SYSCONFIG" || true

start()
{
> echo -n $"Starting mongod: "
> daemon --user "$MONGO\_USER" $mongod $OPTIONS
> RETVAL=$?
> echo
> [$RETVAL -eq 0 ](.md) && touch /var/lock/subsys/mongod
}

stop()
{
> echo -n $"Stopping mongod: "
> killproc -p /var/lib/mongo-qa/mongod.lock -t30 -TERM /usr/bin/mongod
> RETVAL=$?

> echo
> [$RETVAL -eq 0 ](.md) && rm -f /var/lock/subsys/mongod
}

restart () {
> stop
> start
}

ulimit -n 12000
RETVAL=0

case "$1" in
> start)
> > start
> > ;;

> stop)
> > stop
> > ;;

> restart|reload|force-reload)
> > restart
> > ;;

> condrestart)
> > [-f /var/lock/subsys/mongod ](.md) && restart || :
> > ;;

> status)
> > status $mongod
> > RETVAL=$?
> > ;;
  * 
> > echo "Usage: $0 {start|stop|status|restart|reload|force-reload|condrestart}"
> > RETVAL=1
esac

exit $RETVAL
#FIN


Luego en el directorio /etc creamos el archivo mongod2.conf :

# mongo2.conf

#where to log
logpath=/var/log/mongo-qa/mongod.log

logappend=true

# fork and run in background
fork = true

port = 27018

dbpath=/var/lib/mongo-qa

# Enables periodic logging of CPU utilization and I/O wait
#cpu = true

# Turn on/off security.  Off is currently the default
#noauth = true
#auth = true

# Verbose logging output.
#verbose = true

# Inspect all client data for validity on receipt (useful for
# developing drivers)
#objcheck = true

# Enable db quota management
#quota = true

# Set oplogging level where n is
#   0=off (default)
#   1=W
#   2=R
#   3=both
#   7=W+some reads
#oplog = 0

# Diagnostic/debugging option
#nocursors = true

# Ignore query hints
#nohints = true

# Disable the HTTP interface (Defaults to localhost:27018).
#nohttpinterface = true

# Turns off server-side scripting.  This will result in greatly limited
# functionality
#noscripting = true

# Turns off table scans.  Any query that would do a table scan fails.
#notablescan = true

# Disable data file preallocation.
#noprealloc = true

# Specify .ns file size for new databases.
# nssize = 

&lt;size&gt;



# Accout token for Mongo monitoring server.
#mms-token = 

&lt;token&gt;



# Server name for Mongo monitoring server.
#mms-name = 

&lt;server-name&gt;



# Ping interval for Mongo monitoring server.
#mms-interval = 

&lt;seconds&gt;



# Replication Options

# in replicated mongo databases, specify here whether this is a slave or master
#slave = true
#source = master.example.com
# Slave only: specify a single database to replicate
#only = master.example.com
# or
#master = true
#source = slave.example.com

#FIN

Cambiamos los permisos necesarios:


> chcon system\_u:object\_r:var\_log\_t -R /var/log/mongo-qa
> chcon system\_u:object\_r:etc\_t /etc/mongod2.conf

Agregamos el servicio:

> chkconfig --add mongod-qa

Comprobamos si el servicio esta levantado:

> netstat -tanp



