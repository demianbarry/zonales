/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.impl.XMLResponseParser;
import org.apache.solr.client.solrj.response.FacetField;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.zonales.BaseService;
import org.zonales.tagsAndZones.daos.ZoneDao;
import org.zonales.tagsAndZones.objects.Zone;

/**
 *
 * @author nacho
 */
public class getProvince extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("application/vnd.google-earth.kml+xml");
        //response.setContentType("text/plain");
        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();
        String url = props.getProperty("solr_url");
        String ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><kml xmlns=\"http://www.opengis.net/kml/2.2\">";
        ZoneDao zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        try {
            CommonsHttpSolrServer server = new CommonsHttpSolrServer(url);

            server.setSoTimeout(1000);  // socket read timeout
            server.setConnectionTimeout(100);
            server.setDefaultMaxConnectionsPerHost(100);
            server.setMaxTotalConnections(100);
            server.setFollowRedirects(false);  // defaults to false
            // allowCompression defaults to false.
            // Server side must support gzip or deflate for this to have any effect.
            server.setAllowCompression(true);
            server.setMaxRetries(1); // defaults to 0.  > 1 not recommended.
            server.setParser(new XMLResponseParser()); // binary parser is used by default

            SolrQuery query = new SolrQuery();
            query.setQueryType("zonalesContent");
            query.setRows(0);
            query.setFacet(true);
            query.add("facet.field", "provincia");

            QueryResponse rsp = server.query( query );

            FacetField ff = rsp.getFacetField("provincia");
            List<FacetField.Count> list = ff.getValues();

            for (FacetField.Count count : list) {
                //out.print(count.getName() + " - " + count.getCount() + "\n");
                Zone zone = zoneDao.retrieve(count.getName().replace(" ", "_")
                                    .replace("á", "a").replace("é" ,"e")
                                    .replace("í", "i").replace("ó", "o")
                                    .replace("ú", "u").toLowerCase(), "provincia");

                if (zone != null) {
                    ret += "<Placemark>"
                            + "<name>" + count.getName() + "</name>"
                            + "<description>" + count.getCount() + "</description>"
                            + "<Point><coordinates>"
                            + String.valueOf(zone.getCenterLat()) + "," + String.valueOf(zone.getCenterLon())
                            + "</coordinates></Point>"
                            + "</Placemark>";
                }
            }
            
            ret += "</kml>";

            out.print(ret);

        } catch (Exception ex) {
            Logger.getLogger(ConnHelper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
