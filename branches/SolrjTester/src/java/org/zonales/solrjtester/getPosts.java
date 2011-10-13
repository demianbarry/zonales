/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import com.google.gson.Gson;
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
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.common.SolrDocument;
import org.apache.solr.common.SolrDocumentList;
import org.zonales.BaseService;
import org.zonales.entities.Post;
import org.zonales.entities.SolrPost;

/**
 *
 * @author nacho
 */
public class getPosts extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("application/vnd.google-earth.kml+xml");
        //response.setContentType("text/plain");
        Integer cant = Integer.valueOf(request.getParameter("cant"));
        String sortField = request.getParameter("sortfield");
        String sortOrder = request.getParameter("sortOrder");
        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();
        String url = props.getProperty("solr_url");
        String ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><kml xmlns=\"http://www.opengis.net/kml/2.2\">";

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
            query.setFields("*");
            query.setRows(cant);
            query.setSortField(sortField, sortOrder.equals("desc") ? SolrQuery.ORDER.desc : SolrQuery.ORDER.asc);
            query.setQuery("fromUserLatitude:[-300.0 TO 300.0] AND fromUserLongitude:[-300.0 TO 300.0]");

            QueryResponse rsp = server.query( query );

            SolrDocumentList postList = rsp.getResults();

            //List<SolrPost> geoPosts = rsp.getBeans(SolrPost.class);
            String postJSON = "";
            Gson postGSON = new Gson();
            Post post;

            for  (SolrDocument solrdoc : postList) {
                    postJSON = (String) solrdoc.getFieldValue("verbatim");
                    post = postGSON.fromJson(postJSON, Post.class);

                    ret += "<Placemark>"
                            + "<name>" + post.getId() + "</name>"
                            + "<description>" + post.getSource() + "</description>"
                            + "<Point><coordinates>"
                            + post.getFromUser().getLongitude() + "," + post.getFromUser().getLatitude()
                            + "</coordinates></Point>"
                            + "</Placemark>";
            }

            ret += "</kml>";

            out.print(ret);
            
        } catch (Exception ex) {
            Logger.getLogger(ConnHelper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
