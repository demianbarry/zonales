# Introduction #

Listado de los servicios necesarios para el manejo de lugares (places).

<br>
<h1>Lugares</h1>
<br>
<hr />
<h2>Obtener todos los lugares</h2>
<hr />

<b><i>PATH</i></b>: /place/getAll <br>
<b><i>Respuesta</i></b>: Id y nombre de todos los lugares <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener lugares</h2>
<hr />

<b><i>PATH</i></b>: /place/get <br>
<b><i>Respuesta</i></b>: Conjunto de lugares según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de lugares </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener lugares por nombre</h2>
<hr />

<b><i>PATH</i></b>: /place/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de lugares según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del lugar a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear un nuevo lugar</h2>
<hr />

<b><i>PATH</i></b>: /place/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> place </td><td> SI </td><td> Lugar a crear </td><td> JSON </td><td> Campos obligatorios: id, name, zone, type </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar un lugar existente</h2>
<hr />

<b><i>PATH</i></b>: /place/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id del lugar a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar un lugar existente</h2>
<hr />

<b><i>PATH</i></b>: /place/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> id </td><td> SI </td><td> Id del lugar a eliminar </td><td> String </td><td>  </td></tr></tbody></table>

<br>
<h1>Tipos de lugares</h1>
<br>
<hr />
<h2>Obtener todos los tipos de lugares</h2>
<hr />

<b><i>PATH</i></b>: /placeType/getAll <br>
<b><i>Respuesta</i></b>: Nombre de todos los tipos de lugares <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

Sin parámetros<br>
<br>
<br>
<hr />
<h2>Obtener tipos de lugares</h2>
<hr />

<b><i>PATH</i></b>: /placeType/get <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de lugares según filtros <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de tipos de lugares </td><td> JSON </td><td> Cadena JSON con pares key=value a buscar </td></tr></tbody></table>

<br>
<hr />
<h2>Obtener tipos de lugares por nombre</h2>
<hr />

<b><i>PATH</i></b>: /placeType/getLikeName <br>
<b><i>Respuesta</i></b>: Conjunto de tipos de lugares según nombre <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de lugar a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

<br>
<hr />
<h2>Crear un nuevo tipo de lugar</h2>
<hr />

<b><i>PATH</i></b>: /placeType/set <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> placeType </td><td> SI </td><td> Tipo de lugar a crear </td><td> JSON </td><td> Campos obligatorios: name </td></tr></tbody></table>

<br>
<hr />
<h2>Actualizar un tipo de lugar existente</h2>
<hr />

<b><i>PATH</i></b>: /placeType/update <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de lugar a actualizar </td><td> String </td><td>  </td></tr>
<tr><td> data </td><td> SI </td><td> Datos a actualizar </td><td> JSON </td><td> Cadena JSON con pares key=value a actualizar </td></tr></tbody></table>


<br>
<hr />
<h2>Eliminar un tipo de lugar existente</h2>
<hr />

<b><i>PATH</i></b>: /placeType/remove <br>
<b><i>Respuesta</i></b>: Mensaje de éxito o error <br>
<b><i>Formato</i></b>: JSON <br>

<h3>Parámetros</h3>

<table><thead><th> <b>Parámetro</b> </th><th> <b>Requerido</b> </th><th> <b>Descripción</b> </th><th> <b>Formato</b> </th><th> <b>Observaciones</b> </th></thead><tbody>
<tr><td> name </td><td> SI </td><td> Nombre del tipo de lugar a eliminar </td><td> String </td><td>  </td></tr>