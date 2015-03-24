# Introduction #

Listado de los servicios necesarios para el manejo de tags.

<br>
<h1>Tags</h1>
<br>
<hr />
<h2>Obtener todos los tags</h2>
<hr />

<b><i>PATH</i></b>: /tag/getAll <br>
<b><i>Respuesta</i></b>: Id y nombre de todos los tags <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener tags</h2>
<hr />

<b><i>PATH</i></b>: /tag/get <br>
<b><i>Respuesta</i></b>: Conjunto de tag según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de tags </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener tags por nombre</h2>
<hr />

<b><i>PATH</i></b>: /tag/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de tags según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tag a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear un nuevo tag</h2>
<hr />

<b><i>PATH</i></b>: /tag/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> tag </td><td> SI </td><td> Tag a crear </td><td> JSON </td><td> Campos obligatorios: id, name, tpye </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar un tag existente</h2>
<hr />

<b><i>PATH</i></b>: /tag/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id del tag a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar un tag existente</h2>
<hr />

<b><i>PATH</i></b>: /tag/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id del tag a eliminar </td><td> String </td><td>  </td></tr></tbody></table>

<br>
<h1>Tipos de tags</h1>
<br>
<hr />
<h2>Obtener todos los tipos de tag</h2>
<hr />

<b><i>PATH</i></b>: /tagType/getAll <br>
<b><i>Respuesta</i></b>: Nombre de todos los tipos de tags <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener tipos de tags</h2>
<hr />

<b><i>PATH</i></b>: /tagType/get <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de tags según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de tipos de tags </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener tipos de tags por nombre</h2>
<hr />

<b><i>PATH</i></b>: /tagType/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de tags según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de tags a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear un nuevo tipo de tags</h2>
<hr />

<b><i>PATH</i></b>: /tagType/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> tagType </td><td> SI </td><td> Tipo de tag a crear </td><td> JSON </td><td> Campos obligatorios: name </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar un tipo de tag existente</h2>
<hr />

<b><i>PATH</i></b>: /tagType/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de tag a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar un tipo de tag existente</h2>
<hr />

<b><i>PATH</i></b>: /tagType/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de tag a eliminar </td><td> String </td><td>  </td></tr>