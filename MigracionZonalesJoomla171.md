# Introducción #

Con el objetivo de estabilizar la instalación de Joomla en producción se comienza el proceso de migración a Joomla 1.7.1

# Proceso #

El proceso se desarrolla ordenadamente partiendo de una instalación fresca de Joomla 1.7.1 y agregando las extensiones, librerías y demás componentes necesarios.

## Instalación ##

Previo a la instalación de Joomla 1.7.1 se realizó una copia de la instancia de **test** de Zonales.
A continuación se utilizó la extensión [jUpgrade](http://redcomponent.com/jupgrade) para llevar los componentes _core_ de esta nueva copia a la nueva versión pero sobre todo para tener una versión actualizada de los datos de la base de datos.
Finalmente, se descargó el paquete full de Joomla 1.7.1, se limpió la carpeta de la instancia nueva, se descomprimió Joomla 1.7.1 en dicha carpeta y se realizó el proceso estándar de instalación.


Instalacion de template Z20, componente com\_zonales, com\_eqzonales.

## Variables mainframe y option ##
Se utilizan en varias extensiones de desarrollo propio para obtener datos de contexto.

Reemplazo de la variable mainframe por JFactory::getApplication(), antes era global ahora hay que setearla.
$mainframe = JFactory::getApplication();

Reemplazo masivo por línea de comandos:

'$mainframe=JFactory::getApplication(); global ' -lHIRZ /var/www/html/zonales-nuevo/ | xargs -0 -l sed -i 's/$mainframe=JFactory::getApplication(); global /\$mainframe=JFactory::getApplication();/g'

Correcion de variable option, antes era global ahora hay que setearla, $option = JRequest::getCMD('option');


## Instalacion de mod\_menuzonales. ##

## Isntalación de com\_eqzonales ##

## Instalación de com\_zonales ##

Vista en la red, archivo default.php en version de moootools existente no tiene sentido la funcion setHTML, se reemplaza por set('html',html):

grep 'setHTML(' -lHIRZ /var/www/html/zonales-nuevo/components/com\_zonales/views/enlared/tmpl/default.php | xargs -0 -l sed -i 's/setHTML(/set("html"/g'

  * Se deben unificar imagenes.

  * Se deben parametrizar datos de conexión a Solr.

Cambios del archivo de configuración config.xml. La estructura nueva es:

<?xml version="1.0" encoding="utf-8"?>


&lt;config&gt;


> 

&lt;fields&gt;


> > 

&lt;fieldset name="basic" label="Zonales config" description="Configuración de componente Zonales"&gt;


> > > 

&lt;field name="menu\_tag\_prefix" type="text" default="menu\_" label="MENUTAGPREFIX" description="TIP\_MENUTAGPREFIX" /&gt;


> > > 

&lt;field name="zonal\_tag\_prefix" type="text" default="zonal\_" label="ZONALTAGPREFIX" description="TIP\_ZONALTAGPREFIX" /&gt;


> > > 

&lt;field name="@spacer" type="spacer" default="" label="" description="" /&gt;


> > > 

&lt;field name="width\_mapa\_flash" type="text" default="800" label="Alto" description="tip\_alto\_mapa"/&gt;


> > > 

&lt;field name="height\_mapa\_flash" type="text" default="600" label="Ancho" description="tip\_ancho\_mapa"/&gt;


> > > 

&lt;field name="flash\_file" type="text" default="mapa.swf" label="FLASHFILE" description="TIP\_FLASHFILE"/&gt;


> > > 

&lt;field name="@spacer" type="spacer" default="" label="" description="" /&gt;


> > > 

&lt;field name="recaptcha\_publickey" type="text" default="6Lc8fwcAAAAAAP8aw0ojn\_bac-OpAiPv3NnAg9lN" label="RECAPTCHA\_PUBLICKEY" description="TIP\_RECAPTCHA\_PUBLICKEY"/&gt;


> > > 

&lt;field name="recaptcha\_privatekey" type="text" default="6Lc8fwcAAAAAAMT7Q1z7Zy2xGWX5W6BVtBKB3foJ" label="RECAPTCHA\_PRIVATEKEY" description="TIP\_RECAPTCHA\_PRIVATEKEY"/&gt;


> > > 

<field name="root\_value" type="sql" default="" query="SELECT id, label FROM #\_\_custom\_properties\_values WHERE parent\_id = 0" key\_field="id" value\_field="label" description="root\_value\_tip" />



> > 

&lt;/fieldset&gt;



> 

&lt;/fields&gt;




&lt;/config&gt;



## Instalación CKEditor ##
Conflicto con función de la librería _simplephp_ de php. Joomla! usa la función dom\_import\_simplexml(), pero en la versión de php instalada la función es simplexml\_dom\_import().
Se incorporó función:
```
function dom_import_simplexml($el){
                return simplexml_import_dom($el);
}
```

en archivo **/libraries/joomla/form/form.php**. Además la función removeChild($nodo) ya no existe, por lo que se reemplazó por unset($nodo).

# PENDIENTES #

## Seteo de zona ? ##
  * Crear módulo que recupere zonas de MongoDB y permita seleccionar una ?.
  * Crear módulo que permita seleccionar punto desde el mapa?

## Revisar internacionalizacion de Login ##

## Login ##
  * Chequear funcionamiento estándar: registro de usuario, activación y login. (1/2 h)
  * Agregar y/o modificar estilos de módulo de login para template z20 (2 hs).
  * Chequear funcionamiento y evaluar refactoring de com\_aapu (2 hs).
  * En función de lo anterior, agregar seteo de zona del usuario (1 hs).
  * Agregar plugin para seteo de la zona del usuario on login (2 hs).
  * SSO ?
    * Incorporar plugin de autenticación múltiple (mínimo Facebook, Twitter, Google, Microsoft Live, Yahoo) (6 hs).
      * Evaluar y decidir herramienta de SSO.
      * Instalar y probar.
      * Plan de migración.
      * Puesta en marcha.
      * Integración con el resto de las extensiones.
    * Chequear funcionamiento de com\_alias e integrar (no por ahora).

## Content ##
  * Chequear funcionamiento estándar de carga de noticias en front (garantizando criterios de satisfacción de User Stories de Zonales).
    * Instalar plugin editor CKEditor o similar (1 h).
    * Crear usuario autor (1/2 h).
    * Cargar noticias con imágenes y estilos complejos tomando las ya cargadas en Zonales (1/2 h).
    * Revisar noticias sin publicar (1/2 h).
    * Editar noticias tanto sin publicar como publicadas (1/2).
    * Publicar noticias (1/2 h).
    * Despublicar noticia (1/2 h).
  * Indexado
    * Capturar evento onContentAfterSave mediante plugin para indexar en Solr en formato JZone con campo fuente seteado en "Zonales" (8 hs).
  * Metadata
    * Incorporar mediante plugin seteo de localidad a la noticia (8 hs).
    * Incorporar mediante plugin seteo de GeoPoint (8 hs).
    * Incorporar mediante plugin seteo de tags (8 hs).
  * Recuperación
    * Analizar funcionalidad del helper que pueda ser útil (2 hs).
  * Migración
    * Migrar contenido a nuevas tablas.
    * Ejecutar proceso de indexación de contenido.

## Com Zonales ##
  * Agregar vista similar a Nuevos y Relevantes, pero con fuente "Zonales" (4 hs).
  * Incorporar filtro por zona (2 hs).
  * Incoporar ecualizador?
  * Incorporar fuente al ecualizador?

## Custom Properties ##
Se debe reemplazar o directamente suprimir. No existe versión nativa para Joomla 1.7.
  * Evaluar vinculación de tags con contenido en mysql (2 hs).
  * Generar front view para administración de tags de mongo (4 hs).
  * Generar front view para administración de zonas mongo (8 hs).

## Com Eq Zonales (Ecualizador)? ##
  * Vinculación con bandas y ecualizador del usuario ?.
  * Probar consulta con ecualizador.