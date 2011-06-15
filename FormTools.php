<?php

  /**
   * Clase donde unifica métodos para interactuar con los parámetros recibidos
   * desde un formulario.
   * NOTA: no es necesario instanciar ésta clase para utilizar sus métodos.
   */
  class FormTools
  {
    /**
     * Recupera un parámetro que indiquemos.
     * @param string $name [in] Nombre del parámetro buscado.
     *      NOTA: el nombre es sensible a mayúsculas y minúsculas.
     * @param string $method [in] Indica dónde tenemos que buscar el parámetro.
     *      Los valores posibles son:
     *        - 'get': recupera los parámetros que vienen por GET.
     *        - 'post': recupera los parámetros que vienen por POST.
     *        - 'both': recupera los parámetros que vienen por REQUEST (GET y
     *          POST)
     * @param boolean $clean [in] Indica si debemos tratar la cadena, en caso
     *      de que las "Magic Quotes" estén activadas, antes de devolverla.
     * @return mixed Una cadena de texto si el parámetro existe, o FALSE en caso
     *      contrario.
     *      NOTA: se debe usar la comprobación de tipo (===) para diferenciar
     *            entre FALSE y cadena vacía.
     */
    public static function getParameter($name, $method = 'both', $clean = true)
    {
      $name = (string)$name;
      $method = (string)$method;
      $method = strtolower($method);

      switch ($method)
      {
        case 'both':
          $value = (isset($_REQUEST[$name]) ? (string)$_REQUEST[$name] : false);
          break;
        case 'post':
          $value = (isset($_POST[$name]) ? (string)$_POST[$name] : false);
          break;
        case 'get':
          $value = (isset($_GET[$name]) ? (string)$_GET[$name] : false);
          break;
        default:
          return false;
      }

      # Si no lo hemos encontrado.
      if ($value === false) return $value;

      # Si tenemos que tratar la cadena recuperada antes de devolverla.
      if ($clean === true)
        return self::antiMagicQuotes($value);
      else
        return $value;
    }

    /**
     * Recupera los parámetros existentes de la lista solicitada. Si no existe,
     * no se añade al vector devuelto.
     * @param array $names [in] Listado de nombres de los parámetros buscados.
     *      NOTA: el nombre es sensible a mayúsculas y minúsculas.
     * @param mixed $not_found [out] Si no se ha encontrado algunos de los
     *      parámetros solicitados, se devuelve a través de éste parámetro un
     *      vector con los nombres de éstos. Si se han encontrado todos los
     *      parámetros solicitados, el valor devuelto por éste parámetro es
     *      FALSE.
     * @param string $method [in] Indica dónde tenemos que buscar el parámetro.
     *      Los valores posibles son:
     *        - 'get': recupera los parámetros que vienen por GET.
     *        - 'post': recupera los parámetros que vienen por POST.
     *        - 'both': recupera los parámetros que vienen por REQUEST (GET y
     *          POST)
     * @param boolean $clean [in] Indica si debemos tratar la cadena, en caso
     *      de que las "Magic Quotes" estén activadas, antes de devolverla.
     * @return mixed Un vector asociativo 'nombre->valor' con todos los
     *      parámetros recuperados o FALSE si no hay ninguno.
     */
    public static function getParameters(array $names, &$not_found = false, $method = 'both', $clean = true)
    {
      $values = array();
      foreach ($names as $name)
        if(self::getParameter($name, $method, $clean) !== false)
          $values[$name] = self::getParameter($name, $method, $clean);
        else
          $not_found[] = $name;

      return (empty($values) ? false : $values);
    }

    /**
     * Recupera todos los parámetros recibidos.
     * @param string $method [in] Indica dónde tenemos que buscar el parámetro.
     *      Los valores posibles son:
     *        - 'get': recupera los parámetros que vienen por GET.
     *        - 'post': recupera los parámetros que vienen por POST.
     *        - 'both': recupera los parámetros que vienen por REQUEST (GET y
     *          POST)
     * @param boolean $clean [in] Indica si debemos tratar todas las cadenas, en
     *      caso de que las "Magic Quotes" estén activadas, antes de
     *      devolverlas.
     * @return mixed Un vector asociativo 'nombre->valor' con todos los
     *      parámetros recuperados o FALSE si no hay ninguno.
     */
    public static function getAllParameters($method = 'both', $clean = true)
    {
      $name = (string)$name;
      $method = (string)$method;
      $method = strtolower($method);

      switch ($method)
      {
        case 'both':
          $value = $_REQUEST;
          break;
        case 'post':
          $value = $_POST;
          break;
        case 'get':
        default:
          $value = $_GET;
          break;
      }

      # Si no hay ningún parámetro.
      if (empty($value)) return false;

      # Si tenemos que tratar la cadena recuperada antes de devolverla.
      if ($clean === true)
      {
        foreach ($value as $index => $v)
          $value[$index] = self::antiMagicQuotes($v);

        return $value;
      }
      else
        return $value;
    }

    /**
     * Limpia una cadena las barras de escape (\).
     * @param string $string [in] La cadena a tratar.
     * @return string La cadena tratada.
     */
    protected static function antiMagicQuotes($string)
    {
      if(get_magic_quotes_gpc())
        $string = stripslashes($string);

      return $string;
      }
  }

?>