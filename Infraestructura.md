# Introducción #

Página destinada a listar los problemas surgidos en la configuración de infraestructura de Zonales y las guías para su resolución


# Detalles #

## Agregar puertos de escucha a Apache ##

**Descripción:** Al intentar agregar nuevos puertos de escucha al servidor apache, no iniciaba debido a que no tenía permisos para utilizar esos sockets.

**Solución:** El problema era con la configuración de SELinux, debe habilitarse el puerto con el siguiente comando:
  * semanage port -a -t http\_port\_t -p tcp 81

Para listar todas las reglas referentes a puertos en SELInux, utilizar:
  * semanage port -l

El sitio de CentOS proporciona un instructivo completo referente a SELinux:
  * http://wiki.centos.org/HowTos/SELinux

## Agregar reglas a SELinux a partir de audit ##


**Descripción:** Al intentar conectar a MySQL desde phpmyadmin, puede obtenerse un mensaje en el audit de SELinux, indicando que httpd no puede acceder al socket de MySQL. Ejemplo:
type=SYSCALL msg=audit(1301675951.666:103): arch=40000003 syscall=4 success=yes exit=1843546 a0=4 a1=b7d96000 a2=1c215a a3=bfd5c0f8 items=0 ppid=6166 pid=6167 auid=501 uid=0 gid=0 euid=0 suid=0 fsuid=0 egid=0 sgid=0 fsgid=0 tty=pts0 ses=1 comm="load\_policy" exe="/usr/sbin/load\_policy" subj=user\_u:system\_r:load\_policy\_t:s0 key=(null)
type=AVC msg=audit(1301676035.624:104): avc:  denied  { name\_connect } for  pid=6302 comm="httpd" dest=3306 scontext=root:system\_r:httpd\_t:s0 tcontext=system\_u:object\_r:mysqld\_port\_t:s0 tclass=tcp\_socket


**Solución:** Pueden crearse módulos de reglas de SELinux a partir de mensajes del audit, mediante el siguiente comando:
  * grep httpd /var/log/audit/audit.log | audit2allow -M postgreylocal
Luego debe activarse el módulo:
  * semodule -i postgreylocal.pp