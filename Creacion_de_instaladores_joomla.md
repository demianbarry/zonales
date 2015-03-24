# Armado del xml de instalación #

## Especificación de los archivos de la extensión ##
Para facilitar la especificación de los archivos necesarios de la extensión, se utiliza el shellscript getfiles.sh de la siguiente forma:

```
getfiles.sh <directorio/de/la/extensión/joomla>
```

El script es el siguiente:
```
cd $1 && tree -f -i -F | cut -c '3-' | grep -v '^$' | grep -v 'director' | grep -v '/$' | awk '{printf("<filename>%s</filename>\n",$0)}'
```