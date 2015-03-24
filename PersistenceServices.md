# Introducción #

---


Descripción de los servicios de persistencia, con sus respectivos parámetros.


# Servicios para la configuración de fuentes de extracción #

<br>
<hr />
<h2>GetConfig</h2>
<hr />

<b><i>URI</i></b>: /getConfig <br>
<b><i>Respuesta</i></b>: Configuración de la/s fuente/s de extracción solicitada <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> Si </td><td> Nombre de la configuración de la fuente de extracción a recuperar / All / AllNames </td><td> Cualquier cadena </td><td> Si se especifica "all" como name trae todas las configuraciones, AllNames trae todos los nombres </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/getConfig?name=facebook'>http://200.69.225.53:8080/ZCrawlSources/getConfig?name=facebook</a>

<br>
<hr />
<h2>SetConfig</h2>
<hr />

<b><i>URI</i></b>: /setConfig <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> Si </td><td> Nombre de la configuración de la fuente de extracción </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> uri </td><td> Si </td><td> Uri de la fuente de extracción </td><td> Cualquier cadena que represente una URL </td><td>  </td></tr>
<tr><td> plugins </td><td> Si </td><td> Plugins vinculados a la fuente de extracción </td><td> Lista de plugins separados por punto y coma (";"). Cada plugins se compone de un par nombre,tipo separado por coma(",") </td><td>  </td></tr>
<tr><td> params </td><td> No </td><td> Parámetros de la fuente de extracción </td><td> Lista de parámetros separados por punto y coma (";"). Cada parámetro se compone de un par nombre,requerido separado por coma(",") </td><td>  </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/setConfig?name=facebook&uri=http://200.69.225.53:30080/fb/index.php&plugins=GetFacebookServiceURL,URLGetter&params=users,false;keywords,false;zone,true;tags,false;commenters,false;limit,false;since,false;format,false;minactions,false'>http://200.69.225.53:8080/ZCrawlSources/setConfig?name=facebook&amp;uri=http://200.69.225.53:30080/fb/index.php&amp;plugins=GetFacebookServiceURL,URLGetter&amp;params=users,false;keywords,false;zone,true;tags,false;commenters,false;limit,false;since,false;format,false;minactions,false</a>

<br>
<hr />
<h2>UpdateConfig</h2>
<hr />

URI: /updateConfig <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> Si </td><td> Nombre de la configuración de la fuente de extracción a modificar </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> newname </td><td> No </td><td> Nuevo nombre de la fuente de extracción </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> newuri </td><td> No </td><td> Nueva Uri de la fuente de extracción </td><td> Cualquier cadena que represente una URL </td><td>  </td></tr>
<tr><td> newplugins </td><td> No </td><td> Plugins vinculados a la fuente de extracción que se modifican o agregan </td><td> Lista de plugins separados por punto y coma (";"). Cada plugins se compone de un par nombre,tipo separado por coma(",") </td><td> Falta control sobre existencia previa de plugins del mismo tipo </td></tr>
<tr><td> newparams </td><td> No </td><td> Parámetros de la fuente de extracción que se modifican o agregan </td><td> Lista de parámetros separados por punto y coma (";"). Cada parámetro se compone de un par nombre,requerido separado por coma(",") </td><td> Falta control sobre existencia previa de los parámetros </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/updateConfig?name=facebook&newplugins=GetFacebookServiceURL,URLGetter&newstate=Generada'>http://200.69.225.53:8080/ZCrawlSources/updateConfig?name=facebook&amp;newplugins=GetFacebookServiceURL,URLGetter&amp;newstate=Generada</a>

<br>
<hr />
<h2>GetServiceURL</h2>
<hr />

URI: /getServiceURL <br>
<b><i>Respuesta</i></b>: URL de llamada al servicio <br>
<b><i>Formato</i></b>: Plain Text (Falta mejorar esquema de errores)<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> q </td><td> Si </td><td> Metadata de la cual se desea obtener la URL del servicio de extracción </td><td> ZGramData </td><td>  </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/updateConfig?q={'>http://200.69.225.53:8080/ZCrawlSources/updateConfig?q={</a>"localidad":"Puerto Madryn","tags":["Turismo"],"fuente":"twitter","criterios":[{"delUsuario":"ozonodigital"},{"delUsuario":"ichubut"},{"delUsuario":"JornadaWeb?"},{"delUsuario":"tiempoonline"},{"palabras":["Turismo"]},{"palabras":["Avistaje"]}],"filtros":[{"minActions":1}]}<br>
<br>
<br>
<hr />
<h2>GetPluginTypes</h2>
<hr />

<b><i>URI</i></b>: /getPluginTypes <br>
<b><i>Respuesta</i></b>: Lista de tipos de plugins disponibles (Duro) <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/getPluginTypes'>http://200.69.225.53:8080/ZCrawlSources/getPluginTypes</a>


<br>
<hr />
<h2>PublishConfig</h2>
<hr />

<b><i>URI</i></b>: /publishConfig <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: Plain Text (Falta mejorar esquema de errores)<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> Si </td><td> Nombre de la configuración de la fuente de extracción a publicar o despublicar </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> publish </td><td> Si </td><td> Booleano para indicar si deseo publicar o despublicar </td><td> true/false </td><td> Para publicar el estado de la configuración debe ser "Generada" y para despublicar debe ser "Publicada" </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/publishConfig?name=facebook&publish=true'>http://200.69.225.53:8080/ZCrawlSources/publishConfig?name=facebook&amp;publish=true</a>

<br>
<hr />
<h2>RemoveConfig</h2>
<hr />

<b><i>URI</i></b>: /removeConfig <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: Plain Text (Falta mejorar esquema de errores)<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> Si </td><td> Nombre de la configuración de la fuente de extracción que se desea anular </td><td> Cualquier cadena </td><td> El estado de la fuente de extracción debe ser "Despublicada" o "Generada" para poder anular </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: <a href='http://200.69.225.53:8080/ZCrawlSources/removeConfig?name=facebook'>http://200.69.225.53:8080/ZCrawlSources/removeConfig?name=facebook</a>