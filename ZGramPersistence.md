# Introducción #

---


Descripción de los servicios de persistencia de gramáticas de extracción, con sus respectivos parámetros.


# Servicios para persistencia de extracciones #

<br>
<hr />
<h2>GetZGram</h2>
<hr />

<b><i>URI</i></b>: /getZGram <br>
<b><i>Respuesta</i></b>: Extracción solicitada <br>
<b><i>Formato</i></b>: JSON <br>
<b><i>Observaciones</i></b>: Deben especificarse alguno de los parámetros o ambos.<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> No </td><td> Id de la extracción a recuperar / all / allNames </td><td> Cualquier cadena </td><td> Si se especifica "all" como name trae todas las extracciones, "AllNames" trae solamente los datos básicos de la extracción </td></tr>
<tr><td> filtros </td><td> No </td><td> Filtros para recuperar extracciones </td><td> JSON de filtros </td><td>  </td></tr></tbody></table>

<b><i>Ejemplo</i></b>:<br>
<br>
<br>
<hr />
<h2>SetZGram</h2>
<hr />

<b><i>URI</i></b>: /setZGram <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> cod </td><td> Si </td><td> Código de respuesta del parser </td><td> Códigos de ZMessage </td><td>  </td></tr>
<tr><td> msg </td><td> No </td><td> Mensaje de respuesta del parser </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> metadata </td><td> Si </td><td> Metadata obtenida como respuesta del parser </td><td> Formato ZCrawling </td><td>  </td></tr>
<tr><td> verbatim </td><td> Si </td><td> Texto original de la consulta </td><td> Consulta de extracción </td><td>   </td></tr></tbody></table>

<b><i>Ejemplo</i></b>:<br>
<br>
<br>
<hr />
<h2>UpdateZGram</h2>
<hr />

URI: /updateZGram <br>
<b><i>Respuesta</i></b>: success / error <br>
<b><i>Formato</i></b>: JSON<br>
<br>
<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Posibles valores</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> Si </td><td> Id de la extracción a actualizar </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> newcod </td><td> No </td><td> Nuevo código de respuesta del parser </td><td> Códigos de ZMessage </td><td>  </td></tr>
<tr><td> newmsg </td><td> No </td><td> Nuevo mensaje de respuesta del parser </td><td> Cualquier cadena </td><td>  </td></tr>
<tr><td> newmetadata </td><td> No </td><td> Nueva metadata obtenida como respuesta del parser </td><td> Formato ZCrawling </td><td>  </td></tr>
<tr><td> newverbatim </td><td> no </td><td> Nuevo texto de la consulta </td><td> Consulta de extracción </td><td>   </td></tr></tbody></table>

<b><i>Ejemplo</i></b>: