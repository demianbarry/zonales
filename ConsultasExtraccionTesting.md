# Introducción #

Se define el siguiente set de consultas de testing en la gramática ZGram.


# Detalles #

  * **Utilitarios Disponibles:**
    * Generador de Consultas mediante la metadata de consulta --> http://200.69.225.53:30082/CMUtils/qbuilder.html
    * Consulta mediante Lenguaje de consulta ZCrawl --> http://200.69.225.53:30082/CMUtils/extractUtil.html
    * Utilitario para obtener datos de facebook --> http://200.69.225.53:30082/CMUtils/

  * **Zona:**
Puerto Madryn / Chubut
  * **Usuarios sugeridos de Facebook a testear**
Lu17com (199219160505), Cultura Puerto Madryn (1836046085),Museo Puerto Madryn (100000352270923), Archivo Cultural Madryn (100001092692977), MadrynTV (188758487813112), CCultural Caracol (100000814444262), El Madryn (122257437825063), Deportivo Madryn (210333288980557), Brown de Puerto Madryn (173455296011243), Puerto Madryn (Chubut) (1215856616), Lic. en Informática PM, Apam Puerto Madryn (100000867650195), G2P, Coro Municipal (100000384739969)l, PM Rugby Club (111287862220502), Club Alumni (176721481157), a todo deporte (148718161861236), Paraiso FM (106513019373901), Radio Popular, CNAS Club Nautico Atlántico Sud (124727724231453), Argentina Vision (100000545777637), Puerto Madryn Turismo (1821074059), Cuyunco (131929666830457),Chucao (1485368759), Sector 7 (168085533241135), El ojo que todo lo ve (129724167066840), La Frontera (1643432852), La Noche de Madryn (257393286102), Cine Teatro Auditorium Puerto Madryn (114415061974688), Madryn Decaravana (100001226876533), Cetich Prensa Utn (100000467861695), Bienestar Universitario Pm-unpsjb (100002497536261), TutoriasIngenieria Unpsjb Puerto Madryn (100002289860633), DelegadoAcadémico Fcn-puerto Madryn-unpsjb (100002052389618), G2P (110265212331715), Lic. en Informatica PM (123669000990875), Museo Municipal Puerto Madryn (100000352270923), Grupo Azucar Puerto Madryn (804714795), "Madryn Salsa (100001626622047)", Milonga Del Golfo Azul (100000830568178), Archivo Culturaldemadryn (100001092692977), Educación Artística Chubut (100000905740251), Tango Madryn (1810370469)
  * **Diarios sugeridos a testear**
TiempoOnLine.com, NotiChubut, ChubutParaTodos,radiochubut.com
  * **Usuarios sugeridos de Facebook a testear**
ozonodigital, JornadaWeb, ichubut, RadioBravafm, tiemponline, CSyDepMadryn, madrynpasionweb

  * **Extracción 1**
extraer para la localidad "Puerto Madryn" asignando los tags "Cultura" mediante la fuente facebook a partir
del usuario "1836046085" y de amigos del usuario "1836046085"
y del usuario "100000814444262"
y del usuario "100001092692977"
y del usuario "100000384739969"
incluye comentarios de los usuarios: "1370187228"
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Cultura&users=1836046085,100000814444262,100001092692977,100000384739969&commenters=1370187228&minactions=1
  * **Observaciones:** Amigos del usuario no implementado aún.

  * **Extracción 2**
extraer para la localidad "Puerto Madryn" asignando los tags "Deportes" mediante la fuente facebook a partir
del usuario "122257437825063"
y del usuario "210333288980557"
y del usuario "173455296011243"
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Deportes&users=122257437825063,210333288980557,173455296011243&minactions=1

  * **Extracción 3**
extraer para la localidad "Puerto Madryn" asignando los tags "Entretenimiento" mediante la fuente facebook a partir
del usuario "168085533241135"
y del usuario "129724167066840"
y del usuario "1643432852"
y del usuario "257393286102"
y del usuario "114415061974688"
y del usuario "100001226876533"
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Entretenimiento&users=168085533241135,129724167066840,1643432852,257393286102,114415061974688,100001226876533&minactions=1

  * **Extracción 4**
extraer para la localidad "Puerto Madryn" asignando los tags "Turismo" mediante la fuente facebook a partir
del usuario "1485368759"
y del usuario "100000545777637"
y del usuario "1821074059"
y del usuario "131929666830457"
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Turismo&users=1485368759,100000545777637,1821074059,131929666830457&minactions=1

  * **Extracción 5**
extraer para la localidad "Puerto Madryn" asignando los tags "Tecnología" mediante la fuente feed ubicada en "rss\_de\_tecnología\_tiempo\_online" a partir
de "todo"
y filtrando por lista negra "Microsoft"

  * **Extracción 6**
extraer para la localidad "Puerto Madryn" asignando los tags "Deporte" mediante la fuente feed ubicada en "rss\_de\_deporte\_tiempo\_online" a partir de "todo"
y flitrando por lista negra de palabras

  * **Extracción 7**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad","Interes General"
mediante la fuente facebook a partir
del usuario "199219160505"
y del usuario "188758487813112"
y del usuario "1836046085"
y del usuario "100000814444262"
y del usuario "1215856616"
y del usuario "100000867650195"
y del usuario "100000384739969"
incluye comentarios de los usuarios: "100000302251472"
y filtrando por lista negra de usuarios "1046447140"

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Actualidad,Interes%20General&users=199219160505,188758487813112,1836046085,100000814444262,1215856616,100000867650195,100000384739969&commenters=100000302251472

  * **Extracción 8**
extraer para la localidad "Puerto Madryn" asignando los tags "Universidad" mediante la fuente facebook a partir
del usuario "123669000990875" y de amigos del usuario "123669000990875"
y del usuario "110265212331715" y de amigos del usuario "110265212331715"
y del usuario "100000467861695" y de amigos del usuario "100000467861695"
y del usaurio "100002497536261" y de amigos del usuario "100002497536261"
y del usuario "100002289860633" y de amigos del usuario "100002289860633"
y del usuario "100002052389618" y de amigos del usuario "100002052389618"
incluye comentarios
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Universidad&users=123669000990875,110265212331715,100000467861695,100002497536261,100002289860633,100002052389618&commenters=all
  * **Observaciones:** Amigos del usuario no implementado aún.

  * **Extracción 9**
extraer para la localidad "Puerto Madryn" asignando los tags "Legislativas" mediante la fuente feed ubicada en "rss\_de\_legislativas\_tiempo\_online"
a patir de las palabras "kirchnerismo" pero no "D'elia"
y filtrando por lista negra de palabras

  * **Extracción 10**
extraer para la localidad "Puerto Madryn" asignando los tags "Educativas"
mediante la fuente feed ubicada en "http://chubutparatodos.com.ar/feed/"
a partir de las palabras "Universidad"
y de las palabras "Escuela"
y de las palbras "Coloegio"
y de las palabras "Instituto"

  * **Extracción 11**
extraer para la locaildad "Puerto Madryn" asignando los tags "Politica" mediante la fuente feed ubicada en "http://chubutparatodos.com.ar/feed/"
a partir de las palabras "Eliceche"
y de las palabras "Das Neves"
y de la palabras "Buzzi"
y de las palabras "Sastre"
y de las palabras "Candidatos"
y filtrando por lista negra de palabras


  * **Extracción 12**
extraer para la locaildad "Puerto Madryn" asignando los tags "Salud" mediante la fuente feed ubicada en "http://chubutparatodos.com.ar/feed/"
a partir de las palabras "Hospital"
y de las palabras "Clinica"
y de la palabras "Sanatorio"
y de las palabras "Obra Social"


  * **Extracción 13**
extraer para la localidad "Puerto Madryn" asignando los tags "Deporte", "Futbol"
mediante la fuente facebook
a partir del usuario "148718161861236"
y del usuario "199219160505"
y del usuario "173455296011243"
y del usuario "188758487813112"
y de las palabras "Brown"
y de las palabras "Futbol"
y de las palabras "Nacional B"
y filtrando por con al menos 1 actions.

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Deporte,Futbol&users=148718161861236,199219160505,173455296011243,188758487813112&keywords=Brown,Futbol,Nacional%20B&commenters=all&minactions=1

  * **Extracción 14**
extraer para la localidad "Puerto Madryn" asignando los tags "Cultura"
mediante la fuente facebook a partir
del usuario "1836046085" y de amigos del usuario "1836046085"
y del usuario "100000352270923" y de amigos del usuario "100000352270923"
y del usuario "100000814444262" y de amigos del usuario "100000814444262"
y del usuario "100001092692977"
y del usuario "100000867650195"
incluye comentarios
y filtrando por palabras "insultos"

  * **Llamada servicio:** http://200.69.225.53:30080/fb/index.php?zone=Puerto%20Madryn&tags=Cultura&users=1836046085,100000352270923,100000814444262,100001092692977,100000867650195&commenters=all&keywords=!insultos
  * **Observaciones:** Amigos del usuario no implementado aún.

  * **Extracción 15**
extraer para la localidad "Puerto Madryn" asignando los tags "Entretenimiento" mediante la fuente facebook a partir
del usuario "114415061974688"
y del usuario "804714795"
y del usuario "100001626622047"
y del usuario "100000830568178"
y del usuario "100001092692977"
y del usuario "100000905740251"
y del usuario "1810370469"
y palabras "Eventos"
y las palabras "Encuentro"
y las palabras "Clases"
y filtrando por con al menos 1 actions.
y filtrando por lista negra de usuario "100000545777637"

  * **Extracción 16**
extraer para la localidad "Puerto Madryn" asignando los tags "Campo"
mediante la fuente feed ubicada en "http://www.tiemponline.com.ar/index.php?format=feed&type=rss"
a partir de las palabras "Campo"
y las palabras "Estancia"
y las palabras "Cultivo"
y de las palabras "Cosecha"
y filtrando por con al menos 1 actions

  * **Extracción 17**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad", "Politica"
mediante la fuente feed ubicada en "http://www.tiemponline.com.ar/index.php?format=feed&type=rss"
a partir de las palabras "Gobierno"
y las palabras "Elecciones"
y las palabras "Modelo Chubut"
y las palabras "Frente para la victoria"
y las palabras "Intendente"
y las palabras "Gobernador"
y filtrando por lista negra de palabras


  * **Extracción 18**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad", "Policiales",
mediante la fuente feed ubicada en "http://chubutparatodos.com.ar/feed/"
a partir de las palabras "Robo"
y las palabras "Choque"
y las palabras "Accidente"
y las palabras "Asesinato"
y las palabras "Drogas"
y filtrando por lista negra de palabras

  * **Extracción 19**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad", "Policiales"
mediante la fuente feed ubicada en "http://www.notichubut.com/news/index.php?format=feed&type=rss"
a partir de las palabras "Robo"
y las palabras "Choque"
y las palabras "Accidente"
y las palabras "Asesinato"
y las palabras "Drogas"
y filtrando por lista negra de palabras

  * **Extracción 20**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad", "Deporte"
mediante la fuente feed ubicada en "http://www.notichubut.com/news/index.php?format=feed&type=rss"
a partir de las palabras "Rugby"
filtrando por lista negra de palabras

  * **Extracción 21**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad", "Edcucativas", "Interes General"
mediante las fuente feed ubicada en "http://www.radiochubut.com/index.php?format=feed&type=rss"
a partir de las palabras "Educacion"
y las palabras "Atech"
y las palabras "Colegio"
y las palabras "Escuela"
filtrando por lista negra de palabras

  * **Extracción 22**
extraer para la localidad "Puerto Madryn" asignando los tags "Actualidad"
mediante la fuente twitter a partir de usuario "ozonodigital"
y filtrando por lista de palabras negras "Turismo"

  * **Extracción 23**
extraer para la localidad "Puerto Madryn" asignando los Tags "Turismo"
mediante la fuente twitter
a partir del usuario "ozonodigital"
y el usuario "ichubut"
y el usuario "JornadaWeb"
y el usuario "tiempoonline"
y de las palabras "Turismo"
y de las palabtas "Avistaje"
y filtrando por con al menos 1 actions

  * **Extracción 24**
extraer para la loclaiddad "Puerto Madryn" asignando los tags "Deporte"
mediante la fuente twiter
a partir del usuario "ozonodigital"
y del usuario "ichubut"
y del usuario "JornadaWeb"
y del usuario "tiempoonline"
y del usuario "CSyDepMadryn"
y del usuario "madrynpasionweb"

  * **Extracción 25**
extraer para la localidad "Puerto Madryn" asignando los tags "Policiales"
mediante la fuente twitter
a partir del usuario "JornadaWed"
y del usuario "tiempoonline"
y de las palabras "Robo"
y de las palabras "Policia"
y de las palabras "Accidente"
y de las palabras "Incendio"
y de las palabras "contrabando"

  * **Extracción 26**
extraer para la localidad "Puerto Madryn"
asignando los tags "Cultura" mediante la fuente facebook
a partir del usuario "1836046085" y del usuario "100001092692977"
y del usuario "100000895652536" y del usuario "100000106568164"
y del usuario "100000814444262" y del usuario "1788957609"
y del usuario "100001028867714" y del usuario "100001163737415"
y del usuario "100001502473289" y del usuario "100001976354221"
y del usuario "1176596895" y del usuario "100002476403978"
y del usuario "100001186045232"
incluye comentarios
incluye los tags de la fuente.

  * Da error **Illegal character in URL**