# Introducción #

Breve resumen de comandos para generar una copia exacta de un servidor Tomcat. Utilizado para replicar el servidor de Zonales Test en Zonales QA.


# Copia de directorio #
Copia recursivamente el contenido del directorio del servidor Tomcat de test en un nuevo directorio de Tomcat QA.

**cp -pR /opt/tomcat6-test /opt/tomcat6-qa**

# Reemplazo de nombre y puertos #
Se deben reemplazar en el nuevo directorio el nombre del directorio y los puertos de escucha de Tomcat.

**grep 'tomcat6-test' -lHIRZ /opt/tomcat6-qa/ | xargs -0 -l  sed -i 's/tomcat6-test/tomcat6-qa/g'**

**grep '38080' -lHIRZ /opt/tomcat6-qa/ | xargs -0 -l  sed -i 's/38080/38081/g'**

**grep '38005' -lHIRZ /opt/tomcat6-qa/ | xargs -0 -l  sed -i 's/38005/38006/g'**

**grep '38009' -lHIRZ /opt/tomcat6-qa/ | xargs -0 -l  sed -i 's/38009/38010/g'**

**grep '38443' -lHIRZ /opt/tomcat6-qa/ | xargs -0 -l  sed -i 's/38443/38444/g'**

# Solr #
En nuestro caso y dado que Apache Solr está localizado dentro del directorio de Tomcat este proceso también duplica la instalación de Solr.