/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.MalformedURLException;
import java.util.Date;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.solr.client.solrj.SolrServer;
import org.apache.solr.client.solrj.SolrServerException;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.response.SolrPingResponse;
import org.apache.solr.client.solrj.response.UpdateResponse;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.entities.SolrPost;

/**
 *
 * @author nacho
 */
public final class ZSolrServer {

    private static CommonsHttpSolrServer server;

    public ZSolrServer(String solrUrl) throws MalformedURLException {
        setServer(solrUrl);
    }

    public ZSolrServer() {
    }

    public SolrServer getServer() {
        return server;
    }

    public void setServer(String solrUrl) throws MalformedURLException {
        if (server == null) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Creando server - URL: {0}", solrUrl);
            server = new CommonsHttpSolrServer(solrUrl);
            server.setSoTimeout(1000);  // socket read timeout
            server.setConnectionTimeout(100);
            server.setDefaultMaxConnectionsPerHost(100);
            server.setMaxTotalConnections(100);
            server.setFollowRedirects(false);  // defaults to false
            // allowCompression defaults to false.
            // Server side must support gzip or deflate for this to have any effect.
            server.setAllowCompression(true);
        }
    }

    public void indexPosts(Posts posts) throws SolrServerException, IOException {
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando posts en server: {0}", server.getBaseURL());
        Gson postGson = new Gson();

        String postsJson = postGson.toJson(posts);

        for (Post post : posts.getPost()) {

            String verbatim = postGson.toJson(post, Post.class);

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando post: {0}", verbatim);

            Date created = new Date(post.getCreated());
            Date modified = new Date(post.getModified());

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Creando objeto SolrPost");

            SolrPost solrPost = new SolrPost(post.getSource(), post.getSourceLatitude(), post.getSourceLongitude(), post.getId(), post.getFromUser().getName(), post.getFromUser().getCategory(), post.getFromUser().getId(), post.getFromUser().getUrl(), post.getFromUser().getLatitude(), post.getFromUser().getLongitude(), post.getTitle(), post.getText(), post.getZone(), post.getTags(), created, modified, post.getRelevance(), verbatim);

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost creado: {0}", solrPost);

            try {
                SolrPingResponse spr = server.ping();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta ping: {0}", spr.getStatus());

                UpdateResponse ur = server.addBean(solrPost);

                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta addBean: {0}", ur.toString());

                ur = server.commit();

                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta commit: {0}", ur.toString());
            } catch (Exception ex) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Exepcion indexando: {0}", ex);
            }

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost indexado");
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
