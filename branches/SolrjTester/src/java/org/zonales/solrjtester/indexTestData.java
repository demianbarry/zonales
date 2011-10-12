/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.Random;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.impl.XMLResponseParser;
import org.apache.solr.client.solrj.response.UpdateResponse;
import org.zonales.BaseService;

/**
 *
 * @author nacho
 */
public class indexTestData extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String text = request.getParameter("text");
        Integer start = Integer.valueOf(request.getParameter("start"));
        Integer cant = Integer.valueOf(request.getParameter("cant"));
        Double minLat = Double.valueOf(request.getParameter("minLat"));
        Double minLon = Double.valueOf(request.getParameter("minLon"));
        Double latGap = Double.valueOf(request.getParameter("latGap"));
        Double lonGap = Double.valueOf(request.getParameter("lonGap"));
        String url = props.getProperty("solr_url");
        UpdateResponse ur = new UpdateResponse();

        try {
            CommonsHttpSolrServer server = new CommonsHttpSolrServer(url);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Servidor Solr creado segun URL: {0}", url);


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

            double lat = 0.0;
            double lon = 0.0;

            SolrGeoPost geoPost;
            Random generator = new Random();

            for (int i = start; i < start + cant; i++) {
                lat = (generator.nextDouble() * latGap + minLat);
                lon = (generator.nextDouble() * lonGap + minLon);
                geoPost = new SolrGeoPost(String.valueOf(i), text, lat, lon);
                server.addBean(geoPost);
                server.commit();
            }

            out.print("Indexación correcta");
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error en SolrjTester: {0}", new Object[]{ex});
            out.print(ex);
        }

    }

}
