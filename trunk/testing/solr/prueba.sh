#!/bin/sh

numero()
{
	echo "1.5"
}

cadena()
{
	echo "hola"
}

###########

num=`numero`
#num=$?

cad=`cadena`

#########

echo $num
echo $cad
