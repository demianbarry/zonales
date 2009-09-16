#!/bin/sh

secuencia=`seq 1 1 1000`
realiza_consulta='querymaker.sh'
archtemp='.temp'
archivo_prueba=$1

numero_aleatorio()
{
	minimo=$1
	maximo=$2
	calculo=`printf "%d %% %d" $RANDOM $2`

	num_aleatorio=`echo $calculo | bc`

	# si el numero obtenido es menor al minimo solicitado
	if [ $num_aleatorio -lt $minimo ]
		then
			# llamada recursiva
			numero_aleatorio $minimo $maximo
	fi

	return $num_aleatorio
}

invalidar_cache()
{
	cant_frases=$1
	testfile=$2
	temp_file=$3
	secu=`seq 1 1 $cant_frases`

	cant_lineas=`wc -l $testfile`

	for j in $secu
	do
		# calcula el numero de linea a usar
		numero_aleatorio 1 $cant_lineas
		num_alea=$?

		# obtiene la linea a usar
		linea=`awk -F: '{if (NR == '$num_alea') {printf $0}}' $testfile`

		# guarda la linea recuperada
		echo $linea >> $temp_file
	done
}

for i in $secuencia
do
	# proceso para evitar el cacheado
	invalidar_cache $cantidad_frases $archivo_prueba $archtemp

	# se lanza un nuevo proceso para realizar consultas en paralelo
	$realiza_consulta $archtemp &
done
