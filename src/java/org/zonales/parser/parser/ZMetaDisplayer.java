package org.zonales.parser.parser;
/* -----------------------------------------------------------------------------
 * XmlDisplayer.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Wed Jul 06 11:11:55 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

import java.util.ArrayList;
import org.zonales.metadata.ZCrawling;

public class ZMetaDisplayer implements Visitor {

    @Override
    public Object visit(ZCrawling zcrawling, Rule$zcrawling rule) {
        //System.out.println("<zcrawling>");
        //zcrawling = new ZCrawling();
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$localidad rule) {


        //zcrawling.setLocalidad(rule.spelling);
        //System.out.println("<localidad>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</localidad>");           

        return Boolean.FALSE;

    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$tags rule) {
        //System.out.println("<tags>");
        //zcrawling.setTags(Arrays.asList(rule.spelling.split(",")));
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</tags>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$tag rule) {
        //System.out.println("<tag>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</tag>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$fuente rule) {
        //System.out.println("<fuente>");

        //zcrawling.setFuente(rule.spelling.matches("feed.*") ? "feed" : rule.spelling);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</fuente>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$uri_fuente rule) {
        //System.out.println("<uri_fuente>");
        //zcrawling.setUriFuente(rule.spelling);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</uri_fuente>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$criterios rule) {
        //System.out.println("<criterios>");
        /*if (zcrawling.getCriterios() == null) {
            zcrawling.setCriterios(new ArrayList<Criterio>());
        }*/
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</criterios>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$criterio rule) {
        //Globals.nocriterios = false;
        //System.out.println(rule.spelling);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</criterio>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$usuario rule) {
        //System.out.println("<usuario>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</usuario>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$palabras rule) {
        //System.out.println("<palabras>");        

        /*Criterio criterio = new Criterio();
        criterio.setPalabras(Arrays.asList(rule.spelling.split(",")));
        criterio.setSiosi(Globals.siosi);
        Globals.siosi = false;

        if (Globals.nocriterios) {
            zcrawling.getNoCriterios().add(criterio);
        } else {
            zcrawling.getCriterios().add(criterio);
        }
        Globals.nocriterios = false;*/

        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</palabras>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$palabra rule) {
        //System.out.println("<palabra>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</palabra>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$filtros rule) {
        //System.out.println("<filtros>");
        /*if (zcrawling.getFiltros() == null) {
            zcrawling.setFiltros(new ArrayList<Filtro>());
        }*/
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</filtros>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$filtro rule) {
        //System.out.println("<filtro>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</filtro>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$min_num_shuld_match rule) {
        //System.out.println("<min-num-shuld-match>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</min-num-shuld-match>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$cadena rule) {
        //System.out.println("<cadena>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</cadena>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$ALPHA rule) {
        //System.out.println("<ALPHA>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</ALPHA>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$DQUOTE rule) {
        //System.out.println("<DQUOTE>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</DQUOTE>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$QUOTE rule) {
        //System.out.println("<QUOTE>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</QUOTE>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$mspace rule) {
        //System.out.println("<mspace>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</mspace>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$space rule) {
        //System.out.println("<space>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</space>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$comma rule) {
        //System.out.println("<comma>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</comma>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$DIGIT rule) {
        //System.out.println("<DIGIT>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</DIGIT>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$HEXDIG rule) {
        //System.out.println("<HEXDIG>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</HEXDIG>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$int rule) {
        //System.out.println("<int>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</int>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$URI rule) {
        //System.out.println("<URI>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</URI>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$hier_part rule) {
        //System.out.println("<hier-part>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</hier-part>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$URI_reference rule) {
        //System.out.println("<URI-reference>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</URI-reference>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$absolute_URI rule) {
        //System.out.println("<absolute-URI>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</absolute-URI>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$relative_ref rule) {
        //System.out.println("<relative-ref>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</relative-ref>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$relative_part rule) {
        //System.out.println("<relative-part>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</relative-part>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$scheme rule) {
        //System.out.println("<scheme>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</scheme>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$authority rule) {
        //System.out.println("<authority>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</authority>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$userinfo rule) {
        //System.out.println("<userinfo>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</userinfo>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$host rule) {
        //System.out.println("<host>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</host>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$port rule) {
        //System.out.println("<port>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</port>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$IP_literal rule) {
        //System.out.println("<IP-literal>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</IP-literal>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$IPvFuture rule) {
        //System.out.println("<IPvFuture>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</IPvFuture>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$IPv6address rule) {
        //System.out.println("<IPv6address>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</IPv6address>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$h16 rule) {
        //System.out.println("<h16>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</h16>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$ls32 rule) {
        //System.out.println("<ls32>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</ls32>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$IPv4address rule) {
        //System.out.println("<IPv4address>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</IPv4address>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$dec_octet rule) {
        //System.out.println("<dec-octet>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</dec-octet>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$reg_name rule) {
        //System.out.println("<reg-name>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</reg-name>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path rule) {
        //System.out.println("<path>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path_abempty rule) {
        //System.out.println("<path-abempty>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path-abempty>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path_absolute rule) {
        //System.out.println("<path-absolute>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path-absolute>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path_noscheme rule) {
        //System.out.println("<path-noscheme>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path-noscheme>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path_rootless rule) {
        //System.out.println("<path-rootless>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path-rootless>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$path_empty rule) {
        //System.out.println("<path-empty>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</path-empty>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$segment rule) {
        //System.out.println("<segment>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</segment>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$segment_nz rule) {
        //System.out.println("<segment-nz>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</segment-nz>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$segment_nz_nc rule) {
        //System.out.println("<segment-nz-nc>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</segment-nz-nc>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$pchar rule) {
        //System.out.println("<pchar>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</pchar>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$query rule) {
        //System.out.println("<query>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</query>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$fragment rule) {
        //System.out.println("<fragment>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</fragment>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$pct_encoded rule) {
        //System.out.println("<pct-encoded>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</pct-encoded>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$unreserved rule) {
        //System.out.println("<unreserved>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</unreserved>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$reserved rule) {
        //System.out.println("<reserved>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</reserved>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$gen_delims rule) {
        //System.out.println("<gen-delims>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</gen-delims>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$sub_delims rule) {
        //System.out.println("<sub-delims>");
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        //System.out.println("</sub-delims>");

        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Terminal$StringValue value) {
        //System.out.print(value.spelling);

        return Boolean.TRUE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Terminal$NumericValue value) {
        //System.out.print(value.spelling);
        return Boolean.TRUE;
    }

    private Boolean visitRules(ZCrawling zcrawling, ArrayList<Rule> rules) {
        Boolean terminal = Boolean.FALSE;
        for (Rule rule : rules) {
            terminal = (Boolean) rule.accept(zcrawling, this);
        }
        return terminal;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$amigosDelUsuario rule) {

//        Criterio criterio = new Criterio();
//        criterio.setAmigosDe(rule.spelling);
//        if (Globals.nocriterios) {
//            zcrawling.getNoCriterios().add(criterio);
//        } else {
//            zcrawling.getCriterios().add(criterio);
//        }
//        Globals.nocriterios = false;


        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$commenters rule) {
//        zcrawling.setComentarios(Arrays.asList(rule.spelling.split(",")));
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$minActions rule) {
//        Filtro filtro = new Filtro();
//        filtro.setMinActions(Integer.parseInt(rule.spelling));
//        zcrawling.getFiltros().add(filtro);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$listaNegraUsuarios rule) {
//        Filtro filtro = new Filtro();
//        filtro.setListaNegraDeUsuarios(true);
//        zcrawling.getFiltros().add(filtro);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$listaNegraPalabras rule) {
//        Filtro filtro = new Filtro();
//        filtro.setListaNegraDePalabras(true);
//        zcrawling.getFiltros().add(filtro);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$siosi rule) {
//        Globals.siosi = true;
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$descripcion rule) {
//        zcrawling.setDescripcion(rule.spelling);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$deLosUsuarios rule) {
//        Criterio criterio = new Criterio();
//        criterio.setDeLosUsuarios(Arrays.asList(rule.spelling.split(",")));
//        if (Globals.nocriterios) {
//            zcrawling.getNoCriterios().add(criterio);
//        } else {
//            zcrawling.getCriterios().add(criterio);
//        }
//        Globals.nocriterios = false;
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$usuarios rule) {
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    @Override
    public Object visit(ZCrawling zcrawling, Rule$nocriterio rule) {
//        if (zcrawling.getNoCriterios() == null) {
//            zcrawling.setNoCriterios(new ArrayList<Criterio>());
//        }
//        Globals.nocriterios = true;
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }

    public Object visit(ZCrawling zcrawling, Rule$incluyeTagsFuente rule) {
//        zcrawling.setTagsFuente(true);
        if (visitRules(zcrawling, rule.rules).booleanValue()) {
            //System.out.println("");
        }
        return Boolean.FALSE;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
