/* -----------------------------------------------------------------------------
 * XmlDisplayer.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Jul 28 10:46:27 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

package parser;

import java.util.ArrayList;

public class XmlDisplayer implements Visitor
{

  public Object visit(Rule$zcrawling rule)
  {
    System.out.println("<zcrawling>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</zcrawling>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$localidad rule)
  {
    System.out.println("<localidad>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</localidad>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$tags rule)
  {
    System.out.println("<tags>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</tags>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$tag rule)
  {
    System.out.println("<tag>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</tag>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$fuente rule)
  {
    System.out.println("<fuente>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</fuente>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$uri_fuente rule)
  {
    System.out.println("<uri_fuente>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</uri_fuente>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$criterios rule)
  {
    System.out.println("<criterios>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</criterios>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$siosi rule)
  {
    System.out.println("<siosi>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</siosi>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$criterio rule)
  {
    System.out.println("<criterio>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</criterio>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$deTodo rule)
  {
    System.out.println("<deTodo>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</deTodo>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$delUsuario rule)
  {
    System.out.println("<delUsuario>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</delUsuario>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$amigosDelUsuario rule)
  {
    System.out.println("<amigosDelUsuario>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</amigosDelUsuario>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$commenters rule)
  {
    System.out.println("<commenters>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</commenters>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$usuario rule)
  {
    System.out.println("<usuario>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</usuario>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$palabras rule)
  {
    System.out.println("<palabras>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</palabras>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$palabra rule)
  {
    System.out.println("<palabra>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</palabra>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$filtros rule)
  {
    System.out.println("<filtros>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</filtros>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$filtro rule)
  {
    System.out.println("<filtro>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</filtro>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$listaNegraUsuarios rule)
  {
    System.out.println("<listaNegraUsuarios>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</listaNegraUsuarios>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$listaNegraPalabras rule)
  {
    System.out.println("<listaNegraPalabras>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</listaNegraPalabras>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$minActions rule)
  {
    System.out.println("<minActions>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</minActions>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$min_num_shuld_match rule)
  {
    System.out.println("<min-num-shuld-match>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</min-num-shuld-match>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$cadena rule)
  {
    System.out.println("<cadena>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</cadena>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$ALPHA rule)
  {
    System.out.println("<ALPHA>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</ALPHA>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$DQUOTE rule)
  {
    System.out.println("<DQUOTE>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</DQUOTE>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$QUOTE rule)
  {
    System.out.println("<QUOTE>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</QUOTE>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$mspace rule)
  {
    System.out.println("<mspace>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</mspace>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$space rule)
  {
    System.out.println("<space>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</space>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$comma rule)
  {
    System.out.println("<comma>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</comma>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$DIGIT rule)
  {
    System.out.println("<DIGIT>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</DIGIT>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$HEXDIG rule)
  {
    System.out.println("<HEXDIG>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</HEXDIG>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$int rule)
  {
    System.out.println("<int>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</int>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$URI rule)
  {
    System.out.println("<URI>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</URI>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$hier_part rule)
  {
    System.out.println("<hier-part>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</hier-part>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$URI_reference rule)
  {
    System.out.println("<URI-reference>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</URI-reference>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$absolute_URI rule)
  {
    System.out.println("<absolute-URI>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</absolute-URI>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$relative_ref rule)
  {
    System.out.println("<relative-ref>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</relative-ref>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$relative_part rule)
  {
    System.out.println("<relative-part>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</relative-part>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$scheme rule)
  {
    System.out.println("<scheme>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</scheme>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$authority rule)
  {
    System.out.println("<authority>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</authority>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$userinfo rule)
  {
    System.out.println("<userinfo>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</userinfo>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$host rule)
  {
    System.out.println("<host>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</host>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$port rule)
  {
    System.out.println("<port>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</port>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$IP_literal rule)
  {
    System.out.println("<IP-literal>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</IP-literal>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$IPvFuture rule)
  {
    System.out.println("<IPvFuture>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</IPvFuture>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$IPv6address rule)
  {
    System.out.println("<IPv6address>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</IPv6address>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$h16 rule)
  {
    System.out.println("<h16>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</h16>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$ls32 rule)
  {
    System.out.println("<ls32>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</ls32>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$IPv4address rule)
  {
    System.out.println("<IPv4address>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</IPv4address>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$dec_octet rule)
  {
    System.out.println("<dec-octet>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</dec-octet>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$reg_name rule)
  {
    System.out.println("<reg-name>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</reg-name>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path rule)
  {
    System.out.println("<path>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path_abempty rule)
  {
    System.out.println("<path-abempty>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path-abempty>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path_absolute rule)
  {
    System.out.println("<path-absolute>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path-absolute>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path_noscheme rule)
  {
    System.out.println("<path-noscheme>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path-noscheme>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path_rootless rule)
  {
    System.out.println("<path-rootless>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path-rootless>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$path_empty rule)
  {
    System.out.println("<path-empty>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</path-empty>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$segment rule)
  {
    System.out.println("<segment>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</segment>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$segment_nz rule)
  {
    System.out.println("<segment-nz>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</segment-nz>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$segment_nz_nc rule)
  {
    System.out.println("<segment-nz-nc>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</segment-nz-nc>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$pchar rule)
  {
    System.out.println("<pchar>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</pchar>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$query rule)
  {
    System.out.println("<query>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</query>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$fragment rule)
  {
    System.out.println("<fragment>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</fragment>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$pct_encoded rule)
  {
    System.out.println("<pct-encoded>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</pct-encoded>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$unreserved rule)
  {
    System.out.println("<unreserved>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</unreserved>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$reserved rule)
  {
    System.out.println("<reserved>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</reserved>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$gen_delims rule)
  {
    System.out.println("<gen-delims>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</gen-delims>");

    return Boolean.FALSE;
  }

  public Object visit(Rule$sub_delims rule)
  {
    System.out.println("<sub-delims>");
    if (visitRules(rule.rules).booleanValue()) System.out.println("");
    System.out.println("</sub-delims>");

    return Boolean.FALSE;
  }

  public Object visit(Terminal$StringValue value)
  {
    System.out.print(value.spelling);
    return Boolean.TRUE;
  }

  public Object visit(Terminal$NumericValue value)
  {
    System.out.print(value.spelling);
    return Boolean.TRUE;
  }

  private Boolean visitRules(ArrayList<Rule> rules)
  {
    Boolean terminal = Boolean.FALSE;
    for (Rule rule : rules)
      terminal = (Boolean)rule.accept(this);
    return terminal;
  }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
