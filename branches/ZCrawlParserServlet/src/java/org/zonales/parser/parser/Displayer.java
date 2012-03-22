/* -----------------------------------------------------------------------------
 * Displayer.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Fri Sep 23 12:10:58 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.util.ArrayList;
import org.zonales.metadata.ZCrawling;

public class Displayer implements Visitor {

    public Object visit(ZCrawling zcrawling, Rule$zcrawling rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$descripcion rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$localidad rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$tags rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$tag rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$fuente rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$uri_fuente rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$criterios rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$criterio rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$nocriterio rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$deLosUsuarios rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$amigosDelUsuario rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$siosi rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$usuarios rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$geoFeed rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$geo rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$latitude rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$longitude rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$incluyeComentarios rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$pnum rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$num rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$commenters rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$usuario rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$place rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$feedPlace rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$palabras rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$palabra rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$filtros rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$filtro rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$listaNegraUsuarios rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$listaNegraPalabras rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$minActions rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$min_num_shuld_match rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$incluyeTagsFuente rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$temporalidad rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$minutos rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$horas rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$dias rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$cadena rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$ALPHA rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$DQUOTE rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$QUOTE rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$mspace rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$space rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$comma rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$DIGIT rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$HEXDIG rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$int rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$URI rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$hier_part rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$URI_reference rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$absolute_URI rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$relative_ref rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$relative_part rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$scheme rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$authority rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$userinfo rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$host rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$port rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$IP_literal rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$IPvFuture rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$IPv6address rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$h16 rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$ls32 rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$IPv4address rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$dec_octet rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$reg_name rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path_abempty rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path_absolute rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path_noscheme rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path_rootless rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$path_empty rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$segment rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$segment_nz rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$segment_nz_nc rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$pchar rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$query rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$fragment rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$pct_encoded rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$unreserved rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$reserved rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$gen_delims rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Rule$sub_delims rule) {
        return visitRules(zcrawling, rule.rules);
    }

    public Object visit(ZCrawling zcrawling, Terminal$StringValue value) {
        System.out.print(value.spelling);
        return null;
    }

    public Object visit(ZCrawling zcrawling, Terminal$NumericValue value) {
        System.out.print(value.spelling);
        return null;
    }

    private Object visitRules(ZCrawling zcrawling, ArrayList<Rule> rules) {
        for (Rule rule : rules) {
            rule.accept(zcrawling, this);
        }
        return null;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
