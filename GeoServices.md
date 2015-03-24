# Introduction #

Listado de los servicios necesarios para el manejo de zonas.

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
<tr><td> filters </td><td> SI </td><td> Filtros para búsqueda de zonas </td><td> JSON </td><td> El filtro es una cadena JSON con pares key=value a buscar </td></tr></tbody></table>

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
<tr><td> name </td><td> SI </td><td> Nombre de la zona a buscar </td><td> String </td><td> Puede ser una parte del nombre, funciona similar al LIKE de SQL </td></tr></tbody></table>

