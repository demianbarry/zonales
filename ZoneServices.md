# Introduction #

Listado de los servicios necesarios para el manejo de zonas.

<br>
<h1>Zonas</h1>
<br>
<hr />
<h2>Obtener todas las zonas</h2>
<hr />

<b><i>PATH</i></b>: /zone/getAll <br>
<b><i>Respuesta</i></b>: Id y nombre de todas las zonas <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener zonas</h2>
<hr />

<b><i>PATH</i></b>: /zone/get <br>
<b><i>Respuesta</i></b>: Conjunto de zonas según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de zonas </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener zonas por nombre</h2>
<hr />

<b><i>PATH</i></b>: /zone/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de zonas según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre de la zona a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear una nueva zona</h2>
<hr />

<b><i>PATH</i></b>: /zone/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> zone </td><td> SI </td><td> Zona a crear </td><td> JSON </td><td> Campos obligatorios: id, name, tpye </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar una zona existente</h2>
<hr />

<b><i>PATH</i></b>: /zone/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id de la zona a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar una zona existente</h2>
<hr />

<b><i>PATH</i></b>: /zone/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id de la zona a eliminar </td><td> String </td><td>  </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener zona a partir de cadena extendida</h2>
<hr />

<b><i>PATH</i></b>: /zone/get <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtro para la busqueda de zona </td><td> Cadena JSON con pares extendedstring=value a buscar</td><td>  </td></tr></tbody></table>

<h3>Ejemplo</h3>
<a href='http://192.168.0.2:4000/zone/get?filters={'>http://192.168.0.2:4000/zone/get?filters={</a>"extendedString":"puerto_madryn, chubut, argentina"}<br>
<b><i>Respuesta</i></b><br>
[{"<i>id":"4e42a2e17f8b9a8e5a0000b1","extendedString":"puerto_madryn, chubut, argentina","id":"416","name":"puerto_madryn","parent":"164","type":"localidad","state":"generated"}]<br></i><br>

<hr />
<h2>Obtener cadena extendida a partir de ID</h2>
<hr />

<b><i>PATH</i></b>: /zone/getExtendedString <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id de la zona a buscar </td><td> String</td><td>  </td></tr></tbody></table>

<h3>Ejemplo</h3>
<a href='http://192.168.0.2:4000/zone/getExtendedString?id=416'>http://192.168.0.2:4000/zone/getExtendedString?id=416</a><br>
<b><i>Respuesta</i></b><br>
"puerto_madryn, chubut, argentina"<br>
<br>
<br>

<hr />
<h1>Tipos de zonas</h1>
<br>
<hr />
<h2>Obtener todos los tipos de zonas</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/getAll <br>
<b><i>Respuesta</i></b>: Nombre de todos los tipos zonas <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener tipos de zonas</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/get <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de zonas según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de tipos de zonas </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener tipos de zonas por nombre</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de zonas según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de zona a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear un nuevo tipo de zona</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> zoneType </td><td> SI </td><td> Tipo de zona a crear </td><td> JSON </td><td> Campos obligatorios: name </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar un tipo de zona existente</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de zona a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar un tipo de zona existente</h2>
<hr />

<b><i>PATH</i></b>: /zoneType/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de zona a eliminar </td><td> String </td><td>  </td></tr>