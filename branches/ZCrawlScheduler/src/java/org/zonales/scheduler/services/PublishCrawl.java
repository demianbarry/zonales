/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler.services;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Date;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.SolrServer;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.zonales.BaseService;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.entities.SolrPost;

/**
 *
 * @author nacho
 */
public class PublishCrawl extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String url = "http://192.168.0.2:8080/solr/";
        SolrServer server = new CommonsHttpSolrServer(url);
        String postsJson = request.getParameter("posts");

        Gson postGson = new Gson();
        Posts posts = postGson.fromJson(postsJson, Posts.class);

        //out.print(post.toString());
        server.deleteByQuery( "*:*" );// delete everything!

        for (Post post : posts.getPost()) {
        
            Date created = new Date(post.getCreated());
            Date modified = new Date(post.getModified());

            SolrPost solrPost = new SolrPost(post.getSource(), post.getId(), post.getFromUser().getName(), post.getFromUser().getCategory(), post.getFromUser().getId(), post.getFromUser().getUrl(), post.getTitle(), post.getText(), post.getZone(), post.getTags(), created, modified, post.getRelevance(), postsJson);

            server.addBean(solrPost);
            server.commit();
        }

        /*
        SolrQuery query = new SolrQuery();
        query.setQuery( "*:*" );

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Antes query {0}",new Object[]{query});
*/
        /*QueryResponse rsp = server.query( query );

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta {0}",new Object[]{rsp});

        List<PostType> posts = rsp.getBeans(PostType.class);

        for (PostType pos : posts) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Post {0}",new Object[]{pos});
            out.print(pos.getId());
        }*/

    }

}
