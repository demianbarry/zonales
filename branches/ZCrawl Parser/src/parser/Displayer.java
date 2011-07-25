/* -----------------------------------------------------------------------------
 * Displayer.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Jul 21 17:36:22 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

package parser;

import java.util.ArrayList;

public class Displayer implements Visitor
{

  public Object visit(Rule$zcrawling rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$localidad rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$tags rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$tag rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$fuente rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$uri_fuente rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$criterios rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$criterio rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$deTodo rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$delUsuario rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$amigosDelUsuario rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$commenters rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$usuario rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$palabras rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$palabra rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$filtros rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$filtro rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$listaNegraUsuarios rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$listaNegraPalabras rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$minActions rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$min_num_shuld_match rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$cadena rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$ALPHA rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$DQUOTE rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$QUOTE rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$mspace rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$space rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$comma rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$DIGIT rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$HEXDIG rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$int rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$URI rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$hier_part rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$URI_reference rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$absolute_URI rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$relative_ref rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$relative_part rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$scheme rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$authority rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$userinfo rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$host rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$port rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$IP_literal rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$IPvFuture rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$IPv6address rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$h16 rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$ls32 rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$IPv4address rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$dec_octet rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$reg_name rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path_abempty rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path_absolute rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path_noscheme rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path_rootless rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$path_empty rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$segment rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$segment_nz rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$segment_nz_nc rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$pchar rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$query rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$fragment rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$pct_encoded rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$unreserved rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$reserved rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$gen_delims rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Rule$sub_delims rule)
  {
    return visitRules(rule.rules);
  }

  public Object visit(Terminal$StringValue value)
  {
    System.out.print(value.spelling);
    return null;
  }

  public Object visit(Terminal$NumericValue value)
  {
    System.out.print(value.spelling);
    return null;
  }

  private Object visitRules(ArrayList<Rule> rules)
  {
    for (Rule rule : rules)
      rule.accept(this);
    return null;
  }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
