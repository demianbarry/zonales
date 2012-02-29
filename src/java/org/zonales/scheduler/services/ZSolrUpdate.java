/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler.services;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.MalformedURLException;
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
import org.zonales.tagsAndZones.daos.ZoneDao;

/**
 *
 * @author nacho
 */
public final class ZSolrUpdate extends BaseService {

    private static CommonsHttpSolrServer server;
    private Gson gson;
    ZoneDao zoneDao;

    public ZSolrUpdate(String solrUrl) throws MalformedURLException {
        setServer(solrUrl);
        gson = new Gson();
    }

    public ZSolrUpdate() {
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

    public void indexSolrPost(SolrPost oldSolrPost, SolrPost newSolrPost) {
        if (newSolrPost != null && !newSolrPost.equals(oldSolrPost)) {
            try {
                SolrPingResponse spr = server.ping();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta ping: {0}", spr.getStatus());

                if (newSolrPost.getId() == null || newSolrPost.getId().trim().equals("")) {
                    newSolrPost.setId(UUID.randomUUID().toString().toLowerCase(Locale.ENGLISH));
                }

                UpdateResponse ur = server.addBean(newSolrPost);

                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta addBean: {0}", ur.toString());

                ur = server.commit();

                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Respuesta commit: {0}", ur.toString());
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost indexado");

            } catch (Exception ex) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Exepcion indexando: {0}", ex);
            }
        }
    }

    public void indexPosts(Posts posts) throws SolrServerException, IOException {
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando posts en server: {0}", server.getBaseURL());

        //String postsJson = postGson.toJson(posts);

        for (Post post : posts.getPost()) {
            //indexPost(post);
        }
    }

    public void indexPost(List<String> ids) throws SolrServerException, IOException {
        SolrPost oldSolrPost = null;
        SolrQuery query = new SolrQuery();
        query.setQuery("!source:xxxxxxxx");
        if (ids != null && ids.size() > 0) {
            String queryString = " AND id:(";
            for (int i = 0; i < ids.size(); i++) {
                queryString += (i != 0 ? " " : "") + ids.get(i);
            }
            queryString += ")";
            query.setQuery(queryString);
        }
        query.setRows(150000);
        QueryResponse rsp = server.query(query);
        for (SolrPost solrPost : rsp.getBeans(SolrPost.class)) {
            Post postIn = gson.fromJson(solrPost.getVerbatim(), Post.class);
            Logger.getLogger(
                    this.getClass().getName()).log(Level.INFO, "Objeto Post creado: {0}", gson.toJson(postIn));
            SolrPost newSolrPost = new SolrPost();
            postToSolr(newSolrPost, postIn);


            Logger.getLogger(
                    this.getClass().getName()).log(Level.INFO, "Objeto SolrPost creado: {0}", newSolrPost);

            indexSolrPost(oldSolrPost, newSolrPost);
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

    public void postToSolr(SolrPost solrPost, Post post) {
        solrPost.setId(post.getId());
        solrPost.setDocType(post.getDocType());
        org.zonales.entities.Zone zone = post.getZone();
        if (zone != null) {
            if (zone.getId() == null || "".equals(zone.getId()) || zone.getName() == null || "".equals(zone.getName()) || zone.getType() == null || "".equals(zone.getType())) {
                org.zonales.tagsAndZones.objects.Zone zoneObj = zoneDao.retrieveByExtendedString(zone.getExtendedString());
                zone.setId(String.valueOf(zoneObj.getId()));
                zone.setName(zoneObj.getName());
                zone.setType(zoneObj.getType().getName());
            }

            solrPost.setZoneId(post.getZone().getId());
            solrPost.setZoneName(post.getZone().getName());
            solrPost.setZoneType(post.getZone().getType());
            solrPost.setZoneExtendedString(post.getZone().getExtendedString());
        }
        solrPost.setState(post.getState());
        solrPost.setCreated(new Date(post.getCreated()));
        solrPost.setFromUserId(post.getFromUser().getId());
        solrPost.setFromUserName(post.getFromUser().getName());
        solrPost.setFromUserCategory(post.getFromUser().getCategory());
        solrPost.setFromUserUrl(post.getFromUser().getUrl());
        if (post.getFromUser().getPlace() != null) {
            solrPost.setFromUserPlaceId(post.getFromUser().getPlace().getId());
            solrPost.setFromUserPlaceName(post.getFromUser().getPlace().getName());
            solrPost.setFromUserPlaceType(post.getFromUser().getPlace().getType());
        }
        solrPost.setModified(new Date(post.getModified()));
        solrPost.setRelevance(post.getRelevance());
        solrPost.setRelevanceDelta(post.getRelevanceDelta());
        solrPost.setSource(post.getSource());
        solrPost.setPostLatitude(post.getPostLatitude());
        solrPost.setPostLongitude(post.getPostLongitude());
        solrPost.setText(post.getText());
        solrPost.setTitle(post.getTitle());
        solrPost.setExtendedString(post.getExtendedString());
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
            String ids = req.getParameter("ids");
            
            String query = req.getParameter("q");            
            
            String doc = req.getParameter("d");
            
            zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            indexPost(ids != null && !"".equals(ids) ? Arrays.asList(ids.split(",")) : null);
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }


    }
}
