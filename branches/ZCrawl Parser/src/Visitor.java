/* -----------------------------------------------------------------------------
 * Visitor.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Wed Jul 06 11:11:55 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

public interface Visitor
{
  public Object visit(Rule$zcrawling rule);
  public Object visit(Rule$localidad rule);
  public Object visit(Rule$tags rule);
  public Object visit(Rule$tag rule);
  public Object visit(Rule$fuente rule);
  public Object visit(Rule$uri_fuente rule);
  public Object visit(Rule$criterios rule);
  public Object visit(Rule$criterio rule);
  public Object visit(Rule$usuario rule);
  public Object visit(Rule$palabras rule);
  public Object visit(Rule$palabra rule);
  public Object visit(Rule$filtros rule);
  public Object visit(Rule$filtro rule);
  public Object visit(Rule$min_num_shuld_match rule);
  public Object visit(Rule$cadena rule);
  public Object visit(Rule$ALPHA rule);
  public Object visit(Rule$DQUOTE rule);
  public Object visit(Rule$QUOTE rule);
  public Object visit(Rule$mspace rule);
  public Object visit(Rule$space rule);
  public Object visit(Rule$comma rule);
  public Object visit(Rule$DIGIT rule);
  public Object visit(Rule$HEXDIG rule);
  public Object visit(Rule$int rule);
  public Object visit(Rule$URI rule);
  public Object visit(Rule$hier_part rule);
  public Object visit(Rule$URI_reference rule);
  public Object visit(Rule$absolute_URI rule);
  public Object visit(Rule$relative_ref rule);
  public Object visit(Rule$relative_part rule);
  public Object visit(Rule$scheme rule);
  public Object visit(Rule$authority rule);
  public Object visit(Rule$userinfo rule);
  public Object visit(Rule$host rule);
  public Object visit(Rule$port rule);
  public Object visit(Rule$IP_literal rule);
  public Object visit(Rule$IPvFuture rule);
  public Object visit(Rule$IPv6address rule);
  public Object visit(Rule$h16 rule);
  public Object visit(Rule$ls32 rule);
  public Object visit(Rule$IPv4address rule);
  public Object visit(Rule$dec_octet rule);
  public Object visit(Rule$reg_name rule);
  public Object visit(Rule$path rule);
  public Object visit(Rule$path_abempty rule);
  public Object visit(Rule$path_absolute rule);
  public Object visit(Rule$path_noscheme rule);
  public Object visit(Rule$path_rootless rule);
  public Object visit(Rule$path_empty rule);
  public Object visit(Rule$segment rule);
  public Object visit(Rule$segment_nz rule);
  public Object visit(Rule$segment_nz_nc rule);
  public Object visit(Rule$pchar rule);
  public Object visit(Rule$query rule);
  public Object visit(Rule$fragment rule);
  public Object visit(Rule$pct_encoded rule);
  public Object visit(Rule$unreserved rule);
  public Object visit(Rule$reserved rule);
  public Object visit(Rule$gen_delims rule);
  public Object visit(Rule$sub_delims rule);

  public Object visit(Terminal$StringValue value);
  public Object visit(Terminal$NumericValue value);
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
