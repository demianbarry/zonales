#!/bin/sh

########### declaraciones ###########3
archivo_test=$1
lineas_test=`cat $archivo_test`
parte_fija_inicio_consulta='http://127.0.0.1:8983/solr/select?qt=dismax&q='
parte_fija_fin_consulta='&version=2.2&start=0&rows=10&indent=on&fl=*,score'
arch_temp='data.temp'
#arch_temp=$2
arch_resultados='resultados'
base=2
res_temp='.restemp'
salida_grep='salgrep'
log_analisis='.analisis.log'
print_file='.printfile'
print_filedos='.printdos'
cant_consultas=0
tiempo=0.0
delimitador=':'

########### consultas ################

for linea in $lineas_test 
do
	# obtiene el caso de test de la linea actual, y la prepara para agregarla a la consulta
	caso_test=`echo $linea | awk -F${delimitador} '{print $1}' | sed -e 's/_/\%20/g'`
	caso_test_print=`echo $linea | awk -F: '{print $1}'`
	
	### obtiene los resultados esperados
	echo $linea | awk -F${delimitador} '{for(i=2;i <= NF;++i)printf("%s\n",$i);}' > $res_temp
	resultados_esperados=`cat $res_temp`
	
	# arma la consulta
	consulta=${parte_fija_inicio_consulta}${caso_test}$parte_fija_fin_consulta
	
	# recupera el xml de solr y calcula el tiempo de busqueda aproximada
	tiempo_actual=`\time -f '%e:%U:%S' wget -O $arch_temp $consulta 2>&1 | grep '^[0-9]' | grep -v saved | awk -F${delimitador} '{printf("%.2f\n",$1-$2-$3);}'
	
	let cant_consultas=$cant_consultas+1 
`
	#let tiempo=$tiempo+$tiempo_actual
	tiempo=`echo "$tiempo + $tiempo_actual" | bc -l`

	# filtra los datos (recupera los datos del xml)
	ids=`xmlstarlet sel -t -m "/response/result/doc" -v "int[@name='id']" -n $arch_temp | grep -v '^$' | uniq` 

	#
	# analisis de los datos
	#
	cant_errores=0
	cant_aciertos=0
	uno='1'
	cero='0'
	for id_actual in $ids
	do
		# compara el id obtenido con los esperados
		grep $id_actual $res_temp > $salida_grep
		
		case $? in
			0 ) 	let cant_aciertos=$cant_aciertos+1 
				printf "${caso_test_print}${delimitador}${id_actual}${delimitador}${uno}${delimitador}${cero}\n" >> $log_analisis
				;;
			1 ) 	let cant_errores=$cant_errores+1
				printf "${caso_test_print}${delimitador}${id_actual}${delimitador}${cero}${delimitador}${uno}\n" >> $log_analisis
				;;
			2 ) 	echo "Ha ocurrido un error en el analisis"
				;;
		esac
	done
	
	# adiciona el xml al final del archivo maestro de resultados
	#echo "#######" | cat $arch_temp - | tee >> $arch_resultados
	
	# elimina el xml descargado
	#rm -f $arch_temp
done

#let tiempo=${tiempo}/$cant_consultas
tiempo=`echo "${tiempo}/${cant_consultas}" | bc -l`

#####################################################
############# analiza el log ########################
#####################################################
echo "Caso de test${delimitador}Id${delimitador}Acierto${delimitador}Fallo" >> $print_file
echo "Caso de test${delimitador}Cantidad de aciertos${delimitador}Cantidad de Fallos" >> $print_filedos

# obtiene la lista de casos de test (no se repiten)
log_casos=`cat $log_analisis | awk -F${delimitador} '{print $1}' | uniq`
	
for caso in $log_casos
do
	# obtiene la lista de ids asociados al caso de test actual/media/disk/Thesaurus_es_ES.txt/workspace
	log_ids=`cat $log_analisis | grep $caso | awk -F${delimitador} '{print $2}' | uniq`
	
	for idd in $log_ids
	do
		# obtiene la cantidad de aciertos de la busqueda
		cant_aciertos=`cat $log_analisis | grep $caso | grep $idd | awk -F${delimitador} '{print $3}' | grep -c $uno`

		# obtiene la cantidad de fallos de la busqueda
		cant_fallos=`cat $log_analisis | grep $caso | grep $idd | awk -F${delimitador} '{print $4}' | grep -c $uno`

		echo "${caso}${delimitador}${idd}${delimitador}${cant_aciertos}${delimitador}${cant_fallos}" >> $print_file
	done

	cantidad_aciertos=`cat $log_analisis | grep $caso | awk -F${delimitador} '{print $3}' | grep -c $uno`
	cantidad_fallos=`cat $log_analisis | grep $caso | awk -F${delimitador} '{print $4}' | grep -c $uno`

	echo "${caso}${delimitador}${cantidad_aciertos}${delimitador}${cantidad_fallos}" >> $print_filedos
done

################# Muestra los datos ######################
echo "Tiempo promedio de busqueda aproximado: ${tiempo}"
cat $print_file $print_filedos | column -s ${delimitador} -t


################ elimina los archivos temporales ##########
rm -f $log_analisis
rm -f $res_temp
rm -f $salida_grep
rm -f $print_file
rm -f $print_filedos


