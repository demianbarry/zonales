/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;
import java.util.Properties;
import java.util.Random;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.impl.XMLResponseParser;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.client.solrj.response.UpdateResponse;
import org.zonales.BaseService;

/**
 *
 * @author nacho
 */
public class SolrjTester extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String text = request.getParameter("text");
        Integer start = Integer.valueOf(request.getParameter("start"));
        Integer cant = Integer.valueOf(request.getParameter("cant"));
        String url = "http://localhost:8080/solr-geo";
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

            /*SolrQuery query = new SolrQuery();
            query.setQuery("*");
            query.setSortField("id", SolrQuery.ORDER.desc);
            query.setRows(1);

            QueryResponse rsp = server.query( query );
            List<SolrGeoPost> maxIndexPost = rsp.getBeans(SolrGeoPost.class);

            int index = maxIndexPost.size() > 0 ? maxIndexPost.get(0).getId() : 0;*/
            double lat = 0.0;
            double lon = 0.0;

            SolrGeoPost geoPost;
            Random generator = new Random();

            for (int i = start; i < cant; i++) {
                lat = (generator.nextDouble() * 0.20056511 + 34.533604) * -1;
                lon = (generator.nextDouble() * 0.209427 + 58.327921) * -1;
                String location[] = {String.valueOf(lat),String.valueOf(lon)};
                geoPost = new SolrGeoPost(String.valueOf(i), text, location);
                ur = server.addBean(geoPost);
                out.print(ur);
                server.commit();
            }

            out.print("Indexación correcta");
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error en SolrjTester: {0} - {1}", new Object[]{ex, ur});
            out.print(ex);
        }

    }

}
