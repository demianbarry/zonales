#!/bin/sh

########### declaraciones ###########
database='mysql -u zonales -pzonales'

############ funciones #####################

promedio()
{
	suma=$1
	cantidad=$2

	prom=`echo "${suma}/${cantidad}" | bc -l`

	echo $prom
}

desviacion_estandar()
{
	tiempos_individuales=$1
	cantidad_consultas=$2
	tiempo_promedio=$3
	suma=0

	for time in $tiempos_individuales
	do
		# sumatoria para el calculo de la varianza
		suma=`echo "((${time} - ${tiempo_promedio}) ^ 2) + $suma" | bc -l`
	done

	desv=`echo "sqrt($suma / $cantidad_consultas)" | bc -l`

	echo $desv
}

indexar()
{
	import=$1
	delim=$2
	arch='data.temp'
	logfile=$3
	sum=$4

	# realiza el indexado y calcula el tiempo aproximado
	tactual=`\time -f '%e:%U:%S' wget -O $arch $import 2>&1 | grep '^[0-9]' | grep -v saved | awk -F${delim} '{printf("%.2f\n",$1-$2-$3);}'`
	
	let cant_consultas=$cant_consultas+1 

	# guarda los tiempos individuales
	echo $tiempo >> $logfile

	# elimina el xml descargado
	rm -f $arch

	# calcula la sumatoria de los tiempos
	suma=`echo "$sum + $tactual" | bc -l`

	echo $suma
}

cargarbase()
{
	feed=$1
	sql_script='.temp.sql'
	script='.joomla.sql'
	cantidad_palabras=$2
	template=$3
	temp_script='.temporal'

echo "entro a cargar base" 1>&2

	# obtiene el script para guardar las noticias en la base de datos
	rsstool --since="2009-09-01" --joomla $feed -o=$sql_script 2>/dev/null

	echo "hizo el fetch" 1>&2

	# calcula la cantidad de palabras recuperadas

	palabras=`rsstool --template2=$template $feed 2>/dev/null | wc -w`
	#let cantidad=${cantidad_palabras}+${palabras} 
echo @@@@@@@@@@@ $cantidad_palabras $palabras 1>&2
	cantidad=`echo "$cantidad_palabras + $palabras" | bc`
	#cantidad=0

	echo "calculo la cantidad de palabras" 1>&2

	cat $sql_script | sed 'y/áéíóúñ/aeioun/' > $temp_script # quita los caracteres especificos del espanol (facilita ascii)
	echo "transformo a ascii" 1>&2

	echo "use zonales" > $script # especifica que se use el schema zonales
	echo "agrego encabezado" 1>&2

	grep -v "^--" $temp_script >> $script # elimina los comentarios
cp $sql_script nada
	echo "quito los comentarios" 1>&2

	$database < $script
	echo "agrego los feeds" 1>&2

	printf "%d\n" $cantidad

echo "descpues de cantidad" 1>&2
}


secuencia=`seq 1 1 1`
fullimport='http://127.0.0.1:8983/solr/dataimporter?command=full-import'
deltaimport='http://127.0.0.1:8983/solr/dataimporter?command=delta-import'
arch_temp='data.temp'
cant_consultas=0
tiempo=0.0
delimitador=':'
filelog='.times.log'
filelogdelta='.timesdelta.log'
sumatoria=0
solrinstalldir=$1
feeds=`ls $2`
templatefile=$3
cant_palabras=0

########### inicio (preparacion) ################

########### comienzan las pruebas
for i in $secuencia
do
	echo $i

	# elimina la base de datos para forzar el reindexado
	rm -R -f ${solrinstalldir}'/solr/data'

	echo "borro solr database"

	#elimina la base de datos
	$database -e "use zonales; DELETE FROM jos_content"

	echo "elimino contenido base"

	# carga la primera vez los datos oficiales de prueba
	for feed_actual in $feeds
	do
		rss=`echo ${2}$feed_actual`
echo $cant_palabras $templatefile
		cant_palabras=`cargarbase $rss $cant_palabras $templatefile`
		echo "cargo la base $cant_palabras"
	done

	echo "cargo todo"


	tiempo_delta=0.0
	cant_indexado_delta=0
	
	tiempo=`indexar $fullimport $delimitador $filelog $tiempo`

	echo "hizo el full import"

	# calcula la cantidad de palabras recuperadas
	cant_palabras=`$database -B -e 'use zonales; select jc.title, jc.introtext from jos_content jc' | wc -w`

	cant_consultas=`echo "$cant_consultas + 1" | bc`

	############## realiza test de delta import

	for feed_actual in $feeds
	do
rss=`echo ${2}$feed_actual`
		cant_palabras_delta=`cargarbase $rss $cant_palabras_delta $templatefile`

		tiempo_delta=`indexar $deltaimport $delimitador $filelogdelta $tiempo_delta`

		#cant_indexado_delta=`echo "$cant_indexado_delta + 1" | bc -l`
		let cant_indexado_delta=$cant_indexado_delta + 1
	done

	echo "cargo mas datos e hizo el delta import"

	# calcular tiempos delta y guardarlos para su posterior procesamiento
	tdeltapromedio=`promedio $tiempo_delta $cant_indexado_delta`
echo $tdeltapromedio
	echo "calculo el promedio de delta import"

	tiemposdelta=`cat $filelogdelta`

	desviaciondelta=`desviacion_estandar $tiemposdelta $cant_indexado_delta $tdeltapromedio`

	echo "calculo la desviacion estandar"

	rm -f $filelogdelta
	rm -f $filelog

	# elimina la base de datos de solr para forzar el reindexado
	#rm -R -f ${solrinstalldir}'/solr/data'

	#elimina la base de datos
	#$database -e "use zonales; DELETE FROM jos_content"

	#echo "borro la base de datos"
done

tiempos=`cat $filelog`

############# calculo de estadisticos

# tiempo promedio
#tiempo=`echo "${tiempo}/${cant_consultas}" | bc -l`
tpromedio=`promedio $tiempo $cant_consultas`
cant_palabras_delta_promedio=`promedio $cant_palabras_delta $cant_consultas`

desviacion=`desviacion_estandar $tiempos $cant_consultas $tpromedio`

################# Muestra los datos ######################
printf "\n----------------------\n"
echo "Tiempo promedio de indexado aproximado (full import): ${tiempo}"
echo "Con una desviacion estandar aproximada de: ${desviacion}"
echo "Palabras procesadas: $cantidad_fullimport"
printf "\n----------------------\n"
echo "Tiempo promedio de indexado aproximado (delta import): ${tdeltapromedio}"
echo "Con una desviacion estandar aproximada de: ${desviaciondelta}"
echo "Palabras procesadas: $cant_palabras_delta_promedio"


