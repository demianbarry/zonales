/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.MalformedURLException;
import java.util.Date;
import org.apache.solr.client.solrj.SolrServerException;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.entities.SolrPost;

/**
 *
 * @author nacho
 */
public class ZSolrServer extends CommonsHttpSolrServer {

    public ZSolrServer(String solrUrl) throws MalformedURLException {
        super(solrUrl);
    }

    public void indexPosts(Posts posts) throws SolrServerException, IOException {
        for (Post post : posts.getPost()) {
            Gson postGson = new Gson();
            String verbatim = postGson.toJson(post, Post.class);

            Date created = new Date(post.getCreated());
            Date modified = new Date(post.getModified());

            SolrPost solrPost = new SolrPost(post.getSource(), post.getId(), post.getFromUser().getName(), post.getFromUser().getCategory(), post.getFromUser().getId(), post.getFromUser().getUrl(), post.getTitle(), post.getText(), post.getZone(), post.getTags(), created, modified, post.getRelevance(), verbatim);

            this.addBean(solrPost);
            this.commit();
        }
    }
    
    public Posts retrievePosts(String query) {
        Posts posts = new Posts();
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
        return posts;
    }


}
