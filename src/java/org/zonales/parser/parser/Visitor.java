/* -----------------------------------------------------------------------------
 * Visitor.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Fri Aug 05 11:06:59 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import org.zonales.metadata.ZCrawling;

public interface Visitor {

    public Object visit(ZCrawling zcrawling, Rule$zcrawling rule);

    public Object visit(ZCrawling zcrawling, Rule$descripcion rule);

    public Object visit(ZCrawling zcrawling, Rule$localidad rule);

    public Object visit(ZCrawling zcrawling, Rule$tags rule);

    public Object visit(ZCrawling zcrawling, Rule$tag rule);

    public Object visit(ZCrawling zcrawling, Rule$fuente rule);

    public Object visit(ZCrawling zcrawling, Rule$uri_fuente rule);

    public Object visit(ZCrawling zcrawling, Rule$criterios rule);

    public Object visit(ZCrawling zcrawling, Rule$criterio rule);

    public Object visit(ZCrawling zcrawling, Rule$nocriterio rule);

    public Object visit(ZCrawling zcrawling, Rule$deLosUsuarios rule);

    public Object visit(ZCrawling zcrawling, Rule$amigosDelUsuario rule);

    public Object visit(ZCrawling zcrawling, Rule$siosi rule);

    public Object visit(ZCrawling zcrawling, Rule$usuarios rule);

    public Object visit(ZCrawling zcrawling, Rule$commenters rule);

    public Object visit(ZCrawling zcrawling, Rule$usuario rule);

    public Object visit(ZCrawling zcrawling, Rule$palabras rule);

    public Object visit(ZCrawling zcrawling, Rule$palabra rule);

    public Object visit(ZCrawling zcrawling, Rule$filtros rule);

    public Object visit(ZCrawling zcrawling, Rule$filtro rule);

    public Object visit(ZCrawling zcrawling, Rule$listaNegraUsuarios rule);

    public Object visit(ZCrawling zcrawling, Rule$listaNegraPalabras rule);

    public Object visit(ZCrawling zcrawling, Rule$minActions rule);

    public Object visit(ZCrawling zcrawling, Rule$min_num_shuld_match rule);

    public Object visit(ZCrawling zcrawling, Rule$cadena rule);

    public Object visit(ZCrawling zcrawling, Rule$ALPHA rule);

    public Object visit(ZCrawling zcrawling, Rule$DQUOTE rule);

    public Object visit(ZCrawling zcrawling, Rule$QUOTE rule);

    public Object visit(ZCrawling zcrawling, Rule$mspace rule);

    public Object visit(ZCrawling zcrawling, Rule$space rule);

    public Object visit(ZCrawling zcrawling, Rule$comma rule);

    public Object visit(ZCrawling zcrawling, Rule$DIGIT rule);

    public Object visit(ZCrawling zcrawling, Rule$HEXDIG rule);

    public Object visit(ZCrawling zcrawling, Rule$int rule);

    public Object visit(ZCrawling zcrawling, Rule$URI rule);

    public Object visit(ZCrawling zcrawling, Rule$hier_part rule);

    public Object visit(ZCrawling zcrawling, Rule$URI_reference rule);

    public Object visit(ZCrawling zcrawling, Rule$absolute_URI rule);

    public Object visit(ZCrawling zcrawling, Rule$relative_ref rule);

    public Object visit(ZCrawling zcrawling, Rule$relative_part rule);

    public Object visit(ZCrawling zcrawling, Rule$scheme rule);

    public Object visit(ZCrawling zcrawling, Rule$authority rule);

    public Object visit(ZCrawling zcrawling, Rule$userinfo rule);

    public Object visit(ZCrawling zcrawling, Rule$host rule);

    public Object visit(ZCrawling zcrawling, Rule$port rule);

    public Object visit(ZCrawling zcrawling, Rule$IP_literal rule);

    public Object visit(ZCrawling zcrawling, Rule$IPvFuture rule);

    public Object visit(ZCrawling zcrawling, Rule$IPv6address rule);

    public Object visit(ZCrawling zcrawling, Rule$h16 rule);

    public Object visit(ZCrawling zcrawling, Rule$ls32 rule);

    public Object visit(ZCrawling zcrawling, Rule$IPv4address rule);

    public Object visit(ZCrawling zcrawling, Rule$dec_octet rule);

    public Object visit(ZCrawling zcrawling, Rule$reg_name rule);

    public Object visit(ZCrawling zcrawling, Rule$path rule);

    public Object visit(ZCrawling zcrawling, Rule$path_abempty rule);

    public Object visit(ZCrawling zcrawling, Rule$path_absolute rule);

    public Object visit(ZCrawling zcrawling, Rule$path_noscheme rule);

    public Object visit(ZCrawling zcrawling, Rule$path_rootless rule);

    public Object visit(ZCrawling zcrawling, Rule$path_empty rule);

    public Object visit(ZCrawling zcrawling, Rule$segment rule);

    public Object visit(ZCrawling zcrawling, Rule$segment_nz rule);

    public Object visit(ZCrawling zcrawling, Rule$segment_nz_nc rule);

    public Object visit(ZCrawling zcrawling, Rule$pchar rule);

    public Object visit(ZCrawling zcrawling, Rule$query rule);

    public Object visit(ZCrawling zcrawling, Rule$fragment rule);

    public Object visit(ZCrawling zcrawling, Rule$pct_encoded rule);

    public Object visit(ZCrawling zcrawling, Rule$unreserved rule);

    public Object visit(ZCrawling zcrawling, Rule$reserved rule);

    public Object visit(ZCrawling zcrawling, Rule$gen_delims rule);

    public Object visit(ZCrawling zcrawling, Rule$sub_delims rule);

    public Object visit(ZCrawling zcrawling, Terminal$StringValue value);

    public Object visit(ZCrawling zcrawling, Terminal$NumericValue value);

    public Object visit(ZCrawling zcrawling, Rule$incluyeTagsFuente rule);
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
