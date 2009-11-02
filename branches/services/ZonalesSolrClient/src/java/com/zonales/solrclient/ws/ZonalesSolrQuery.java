/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.zonales.solrclient.ws;

import com.zonales.solrclient.jaxb.requerimiento.ParametroType;
import com.zonales.solrclient.jaxb.requerimiento.Requerimiento;
import com.zonales.solrclient.jaxb.requerimiento.SortType;
import com.zonales.solrclient.jaxb.respuesta.ContenidoType;
import com.zonales.solrclient.jaxb.respuesta.Respuesta;
import com.zonales.solrclient.jaxb.respuesta.ResultadoType;
import com.zonales.solrclient.jaxb.respuesta.ResultadosType;
import java.io.StringReader;
import java.io.StringWriter;
import java.math.BigInteger;
import java.net.URL;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.Hashtable;
import java.util.List;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebService;
import javax.ejb.Stateless;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import javax.xml.datatype.DatatypeConfigurationException;
import javax.xml.datatype.DatatypeFactory;
import javax.xml.datatype.XMLGregorianCalendar;
import javax.xml.transform.stream.StreamSource;
import javax.xml.validation.Schema;
import javax.xml.validation.SchemaFactory;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.SolrServer;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.common.SolrDocument;

/**
 *
 * @author fep
 */
@WebService()
@Stateless()
public class ZonalesSolrQuery {

    private StringBuffer xmlstr;
    private StringReader stream;
    private StreamSource source;

    /**
     * Construye una objeto SolrQuery configurado según los parametros indicados
     * por el usuario y la configuración del ecualizador actual.
     * 
     * @param requerimiento Parametros del usuario
     * @return objeto de SolrQuery para búsqueda en Solr
     */
    private SolrQuery buildQuery(Requerimiento requerimiento) {

        SolrQuery solrQuery = new SolrQuery();

        // Query type used to determine the request handler.
        solrQuery.setQueryType("zonalesContent");

        /**
         * Los campos (fields) sobre los cuales realizar la búsqueda pueden ser
         * especificados mediante el método setFields(). Sin embargo, se
         * prefiere la configuración indicada por "qf" en la sección correspondiente
         * al request handler utilizado en el archivo solrconfig.xml
         *
         * solrQuery.setFields("*");
         */
        
        // Parametros opcionales enviados en el requerimiento
        Map<String, String> params = new Hashtable<String, String>();
        for (ParametroType p : requerimiento.getContenido().getParametros().getParametro()) {
            params.put(p.getClave(), p.getValor());
        }

        // Criterio de búsqueda principal indicado por el usuario
        String userQuery = requerimiento.getContenido().getQuery();

        // Se asigna el criterio de búsqueda
        solrQuery.setQuery(userQuery);

        // Orden de los resultados
        for (SortType sort : requerimiento.getContenido().getSortFields().getSort()) {
            solrQuery.addSortField(sort.getField(),
                    "asc".equalsIgnoreCase(sort.getOrder()) ? SolrQuery.ORDER.asc : SolrQuery.ORDER.desc);
        }

        // Filter Querys
        solrQuery.addFilterQuery(
                "published:true",
                "section_published:true",
                "category_published:true",
                "(hasPublishUpDate:false OR publishUpDate:[* TO NOW])",
                "(hasPublishDownDate:false OR publishDownDate:[NOW TO *])",
                "a_access:[* TO " + params.get("usuario_aid") + "]",
                "section_access:[* TO " + params.get("usuario_aid") + "]",
                "category_access:[* TO " + params.get("usuario_aid") + "]"
                );

        // se recupera puntaje
        solrQuery.setIncludeScore(Boolean.TRUE);

        // boost querys (ecualizador)
        // solrQuery.setParam("bq", "tags_values:actualidad^40 tags_values:deportes^20");

        // offset
        solrQuery.setStart(0);
        // numero de resultados
        solrQuery.setRows(Integer.valueOf(params.get("limit")).intValue());

        // Highlighting
        /*
        solrQuery.setHighlight(true);
        solrQuery.setHighlightFragsize(350);
        solrQuery.setParam("hl.fl", "intro_content,full_content");
         */

        return solrQuery;
    }

    /**
     * 
     * @param response
     * @return
     * @throws DatatypeConfigurationException
     */
    private Respuesta buildResponse(QueryResponse response) throws DatatypeConfigurationException {
        Map<String, Map<String, List<String>>> highlighting = response.getHighlighting();

        ResultadosType resultados = new ResultadosType();

        // XML de respuesta
        for (SolrDocument solrDocument : response.getResults()) {

            // Resultado (id, score, titulo, fecha)
            ResultadoType resultado = new ResultadoType();
            long id = Long.valueOf((Integer) solrDocument.getFieldValue("id"));
            resultado.setId(BigInteger.valueOf(id));
            resultado.setScore(solrDocument.getFieldValue("score").toString());
            resultado.setTitulo((String) solrDocument.getFieldValue("title"));

            // Fecha
            GregorianCalendar cal = new GregorianCalendar();
            cal.setTime((Date) solrDocument.getFieldValue("publishUpDate"));
            XMLGregorianCalendar xcal = DatatypeFactory.newInstance().newXMLGregorianCalendar(cal);
            resultado.setFechaPublicacion(xcal);

            // Concatena texto de la sección 'highlighting' para mostrar en el resultado
            Map<String, List<String>> highlight = highlighting.get(String.valueOf(id));
            StringBuilder sb = new StringBuilder();
            for (Map.Entry<String, List<String>> content : highlight.entrySet()) {
                for (String t : content.getValue()) {
                    sb.append(" ... " + t);
                }
            }
            resultado.setTexto(sb.toString());

            resultados.getResultado().add(resultado);
        }

        ContenidoType contenido = new ContenidoType();
        contenido.setResultados(resultados);

        Respuesta respuesta = new Respuesta();
        respuesta.setContenido(contenido);
        return respuesta;
    }


    /**
     * Web service operation
     */
    @WebMethod(operationName = "solrQuery")
    public String query(@WebParam(name = "mensaje") String mensaje) {

        String xmlRespuesta = null;

        System.out.println("--> solrQuery");
        System.out.println(mensaje);

        try {
            // StreamSource a partir del parametro mensaje para usar en el unmarshaller de jaxb
            xmlstr = new StringBuffer(mensaje);
            stream = new StringReader(xmlstr.toString());
            source = new StreamSource(stream);

            JAXBContext jc = JAXBContext.newInstance(
                    "com.zonales.solrclient.jaxb.requerimiento:" +
                    "com.zonales.solrclient.jaxb.respuesta"
                    );
            Unmarshaller u = jc.createUnmarshaller();

            // XSD para validación de request de requerimiento
            //URL xsdUrl = getClass().getResource("/com/zonales/solrclient/ZonalesSolrQueryRequerimiento.xsd");
            URL xsdUrl = null;
            if (xsdUrl != null) {
                SchemaFactory sf = SchemaFactory.newInstance(javax.xml.XMLConstants.W3C_XML_SCHEMA_NS_URI);
                Schema schema = sf.newSchema(xsdUrl);
                u.setSchema(schema);
            } else {
                Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.WARNING, "No se encontro XSD para validación del request.");
            }

            // Conexión con Servidor Solr
            SolrServer solrServer = new CommonsHttpSolrServer("http://192.168.0.29:8983/solr");
            //SolrServer solrServer = new CommonsHttpSolrServer("http://localhost:8983/solr");

            // Se obtiene query enviada por cliente
            Requerimiento r = (Requerimiento) u.unmarshal(source);            
            SolrQuery solrQuery = this.buildQuery(r);

            // Se realiza el query contra Solr
            QueryResponse solrReponse = solrServer.query(solrQuery);

            // Generar respuesta en base a resultados del query contra Solr
            Respuesta respuesta = this.buildResponse(solrReponse);

            // Representación XML del objeto respuesta
            Marshaller m = jc.createMarshaller();

            // XSD para validación de la respuesta
            //URL responseXsdUrl = getClass().getResource("/com/zonales/solrclient/ZonalesSolrQueryRespuesta.xsd");
            URL responseXsdUrl = null;
            if (responseXsdUrl != null) {
                SchemaFactory sf = SchemaFactory.newInstance(javax.xml.XMLConstants.W3C_XML_SCHEMA_NS_URI);
                Schema schema = sf.newSchema(responseXsdUrl);
                m.setSchema(schema);
            } else {
                Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.WARNING, "No se encontro XSD para validación de la respuesta.");
            }

            StringWriter xmlAttr = new StringWriter();
            m.marshal(respuesta, xmlAttr);
            xmlRespuesta = xmlAttr.toString();

            System.out.println(xmlAttr.toString());

        /*} catch (JAXBException ex) {
            Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.SEVERE, null, ex);
        } catch (ClassCastException ex) {
            Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.SEVERE, null, ex); */
        } catch (Exception e) {
            Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.SEVERE, null, e);
        } catch (Error e) {
            Logger.getLogger(ZonalesSolrQuery.class.getName()).log(Level.SEVERE, null, e);
        } finally {
            return xmlRespuesta;
        }
    }
}