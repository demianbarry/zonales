/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.HttpURLConnection;
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
import org.apache.solr.client.solrj.response.QueryResponse;
import org.zonales.BaseService;

/**
 *
 * @author nacho
 */
public class getAlfaFromGeoData extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        Integer rows = Integer.valueOf(request.getParameter("rows"));
        PrintWriter out = response.getWriter();
        String url = props.getProperty("solr_url");

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

            SolrQuery query = new SolrQuery();
            query.setQuery("-provincia:[\"\" TO *]");
            query.setQueryType("zonalesContent");
            query.setRows(rows);

            QueryResponse rsp = server.query( query );

            List<SolrGeoPost> geoPosts = rsp.getBeans(SolrGeoPost.class);

            HttpURLConnection connection;
            Gson OsmPlaceGson = new Gson();
            int code;

            for (SolrGeoPost geoPost : geoPosts) {
                connection = ConnHelper.getURLConnection("http://nominatim.openstreetmap.org/reverse?lat=" + geoPost.getLatitude() + "&lon=" + geoPost.getLongitude() + "&format=json", Integer.valueOf(props.getProperty("timeout")));
                code = connection.getResponseCode();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Code: {0}", code);

                if (code == 200) {
                    String OsmPlaceJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                    OSMPlace osmPlace = OsmPlaceGson.fromJson(OsmPlaceJson, OSMPlace.class);
                    OSMAddress osmAddress = osmPlace.getAddress();
                    if (osmAddress.getCity() != null) {
                        geoPost.setLocalidad(osmAddress.getCity());
                    } else {
                        if (osmAddress.getTown() != null) {
                            geoPost.setLocalidad(osmAddress.getTown());
                        }
                    }
                    if (osmAddress.getState() != null) {
                        geoPost.setProvincia(osmAddress.getState());
                    }
                    server.addBean(geoPost);
                    server.commit();
                }
            }

            out.print("Post actualizados");

        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error en SolrjTester: {0} - {1}", new Object[]{ex});
            out.print(ex);
        }
    }

}
