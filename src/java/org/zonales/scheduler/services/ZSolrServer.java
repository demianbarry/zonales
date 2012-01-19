/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler.services;

import com.google.gson.Gson;
import com.google.gson.stream.JsonReader;
import java.io.IOException;
import java.io.StringReader;
import java.net.MalformedURLException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.Properties;
import java.util.UUID;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.SolrServer;
import org.apache.solr.client.solrj.SolrServerException;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.client.solrj.response.SolrPingResponse;
import org.apache.solr.client.solrj.response.UpdateResponse;
import org.zonales.BaseService;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.entities.SolrPost;

/**
 *
 * @author nacho
 */
public final class ZSolrServer extends BaseService {

    private static CommonsHttpSolrServer server;
    private Gson gson;

    public ZSolrServer(String solrUrl) throws MalformedURLException {
        setServer(solrUrl);
        gson = new Gson();
    }

    public ZSolrServer() {
        gson = new Gson();
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

    public void indexSolrPost(SolrPost solrPost) {
        try {
            SolrPingResponse spr = server.ping();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta ping: {0}", spr.getStatus());

            if (solrPost.getId() == null || solrPost.getId().trim().equals("")) {
                solrPost.setId(UUID.randomUUID().toString().toLowerCase(Locale.ENGLISH));
            }

            UpdateResponse ur = server.addBean(solrPost);

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta addBean: {0}", ur.toString());

            ur = server.commit();

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta commit: {0}", ur.toString());
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost indexado");

        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Exepcion indexando: {0}", ex);
        }
    }

    public void indexPosts(Posts posts) throws SolrServerException, IOException {
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando posts en server: {0}", server.getBaseURL());

        //String postsJson = postGson.toJson(posts);

        for (Post post : posts.getPost()) {
            indexPost(post);
        }
    }

    public void indexPost(Post post) throws SolrServerException, IOException {
        String verbatim = gson.toJson(post, Post.class);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando post: {0}", verbatim);

        if (post.getId() != null && !"".equals(post.getId())) {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + post.getId().replace(' ', '+') + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost solrP = rsp.getBeans(SolrPost.class).get(0);
                Post postIn = gson.fromJson(solrP.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(solrP));
                if (postIn.getTags() != null) {
                    for (String tag : postIn.getTags()) {
                        if (!post.getTags().contains(tag)) {
                            post.getTags().add(tag);
                        }
                    }
                }
            }
        }

        Date created = null;
        if (post.getCreated() != null) {
            created = new Date(post.getCreated());
        }

        Date modified = new Date();

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Creando objeto SolrPost");

        SolrPost solrPost = new SolrPost(post.getSource(), post.getSourceLatitude(), post.getSourceLongitude(), post.getId(), post.getFromUser() != null ? post.getFromUser().getName() : null, post.getFromUser() != null ? post.getFromUser().getCategory() : null, post.getFromUser() != null ? post.getFromUser().getId() : null, post.getFromUser() != null ? post.getFromUser().getUrl() : null, post.getFromUser() != null ? post.getFromUser().getLatitude() : null, post.getFromUser() != null ? post.getFromUser().getLongitude() : null, post.getTitle(), post.getText(), post.getZone(), post.getTags(), created, modified, post.getRelevance(), verbatim);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost creado: {0}", solrPost);

        indexSolrPost(solrPost);
        post.setId(solrPost.getId());
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

    public void addTags(String id, List<String> tags) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id.replace(' ', '+') + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost solrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(solrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(solrPost));
                for (String tag : tags) {
                    if (post.getTags() == null) {
                        post.setTags(new ArrayList<String>());
                    }
                    if (!post.getTags().contains(tag)) {
                        post.getTags().add(tag);
                    }
                }
                postToSolr(solrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> SolrPost: {0}", gson.toJson(solrPost));
                indexSolrPost(solrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void removeTag(String id, String tag) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id.replace(' ', '+') + "\"");
            QueryResponse rsp = server.query(query);


            if (rsp.getResults().getNumFound() == 1) {
                SolrPost solrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(solrPost.getVerbatim(), Post.class);
                post.getTags().remove(tag);
                postToSolr(solrPost, post);
                indexSolrPost(solrPost);
            }


        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void postToSolr(SolrPost solrPost, Post post) {
        solrPost.setZone(post.getZone());
        solrPost.setState(post.getState());
        solrPost.setCreated(new Date(post.getCreated()));
        solrPost.setFromUserCategory(post.getFromUser().getCategory());
        solrPost.setFromUserId(post.getFromUser().getId());
        solrPost.setFromUserLatitude(post.getFromUser().getLatitude());
        solrPost.setFromUserLongitude(post.getFromUser().getLongitude());
        solrPost.setFromUserName(post.getFromUser().getName());
        solrPost.setFromUserUrl(post.getFromUser().getUrl());
        solrPost.setModified(new Date(post.getModified()));
        solrPost.setRelevance(post.getRelevance());
        solrPost.setSource(post.getSource());
        solrPost.setSourceLatitude(post.getSourceLatitude());
        solrPost.setSourceLongitude(post.getSourceLongitude());
        solrPost.setText(post.getText());
        solrPost.setTitle(post.getTitle());
        solrPost.setTags(post.getTags());
        solrPost.setVerbatim(gson.toJson(post, Post.class));
    }

    @Override
    public void serve(HttpServletRequest req, HttpServletResponse resp, Properties props) throws ServletException, IOException, Exception {
        gson = new Gson();

        try {
            req.setCharacterEncoding("UTF-8");
            resp.setCharacterEncoding("UTF-8");
            String solrURL = req.getParameter("url");
            setServer(solrURL);
            String json = req.getParameter("doc");
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> Doc: {0}", json);
            JsonReader jr = new JsonReader(new StringReader(json));
            jr.setLenient(true);
            Post post = gson.fromJson(jr, Post.class);

            String aTags = req.getParameter("aTags");

            if (aTags
                    != null) {
                addTags(post.getId(), Arrays.asList(aTags.split(",")));
            }
            String rTag = req.getParameter("rTag");

            if (rTag
                    != null) {
                removeTag(post.getId(), rTag);
            }

            if (aTags
                    == null && rTag
                    == null) {
                indexPost(post);
            }

            resp.getWriter().print(gson.toJson(post));
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
