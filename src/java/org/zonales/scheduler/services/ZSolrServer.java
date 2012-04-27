/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler.services;

import com.google.gson.Gson;

import java.io.IOException;
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
import org.apache.commons.lang3.text.WordUtils;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.SolrServer;
import org.apache.solr.client.solrj.SolrServerException;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.client.solrj.response.SolrPingResponse;
import org.apache.solr.client.solrj.response.UpdateResponse;
import org.zonales.BaseService;
import org.zonales.entities.Place;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.entities.SolrPost;
import org.zonales.entities.Zone;
import org.zonales.tagsAndZones.daos.PlaceDao;
import org.zonales.tagsAndZones.daos.ZoneDao;

/**
 *
 * @author nacho
 */
public final class ZSolrServer extends BaseService {

    private static CommonsHttpSolrServer server;
    private Gson gson;
    private ZoneDao zoneDao;
    private PlaceDao placeDao;

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

    public void indexPosts(Posts posts, String db_host, Integer db_port, String db_name) throws SolrServerException, IOException {
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando posts en server: {0}", server.getBaseURL());

        zoneDao = new ZoneDao(db_host, db_port, db_name);
        placeDao = new PlaceDao(db_host, db_port, db_name);

        //String postsJson = postGson.toJson(posts);

        for (Post post : posts.getPost()) {
            indexPost(post);
        }
    }

    public void indexPost(Post post) throws SolrServerException, IOException {
        SolrPost oldSolrPost = null;
        if (post.getId() != null && !"".equals(post.getId())) {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + post.getId() + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Encontré SOLRPOST: {0}", gson.toJson(oldSolrPost));
                Post postIn = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "POST FROM VERBATIM: {0}", gson.toJson(postIn));
                if (postIn.getTags() != null) {
                    for (String tag : postIn.getTags()) {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "TAG POSTIN: {0}", tag.trim());
                        if (!post.getTags().contains(tag.trim())) {
                            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "EL TAG POSTIN NO ESTA:");
                            post.getTags().add(tag.trim());
                            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "TAG AGREGADO AL POST: {0}", tag.trim());
                        }
                    }
                }
            }
        }

        String verbatim = gson.toJson(post, Post.class);
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Indexando post: {0}", verbatim);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "CREATED: {0}", post.getCreated());

        if (post.getCreated() == null) {
            if (oldSolrPost != null && oldSolrPost.getCreated() != null) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "FECHA CREADO EXISTENTE: {0}", oldSolrPost.getCreated().getTime());
                post.setCreated(oldSolrPost.getCreated().getTime());
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "FECHA CREADO HOY: {0}", (new Date()).getTime());
                post.setCreated((new Date()).getTime());
            }
        }

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "MODIFIED: {0}", post.getModified());

        if (post.getModified() == null) {
            if (oldSolrPost != null && oldSolrPost.getModified() != null) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "FECHA MODIFICADO EXISTENTE: {0}", oldSolrPost.getModified().getTime());
                post.setModified(oldSolrPost.getModified().getTime());
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "FECHA MODIFICADO HOY: {0}", (new Date()).getTime());
                post.setModified((new Date()).getTime());
            }
        }

        //Logger.getLogger(this.getClass().getName()).log(Level.INFO, "CREATED: " + modified + " MODIFIED: " + modified);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Creando objeto SolrPost");

        SolrPost newSolrPost = new SolrPost();
        postToSolr(newSolrPost, post);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Objeto SolrPost creado: {0}", newSolrPost);

        indexSolrPost(oldSolrPost, newSolrPost);
        post.setId(newSolrPost.getId());
    }

    public Posts retrievePosts(String query) {
        Posts posts = new Posts();
        /*
         * SolrQuery query = new SolrQuery(); query.setQuery( "*:*" );
         *
         * Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Antes
         * query {0}",new Object[]{query});
         */
        /*
         * QueryResponse rsp = server.query( query );
         *
         * Logger.getLogger(this.getClass().getName()).log(Level.INFO,
         * "Respuesta {0}",new Object[]{rsp});
         *
         * List<PostType> posts = rsp.getBeans(PostType.class);
         *
         * for (PostType pos : posts) {
         * Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Post
         * {0}",new Object[]{pos}); out.print(pos.getId());
        }
         */
        return posts;
    }

    public void addTags(String id, List<String> tags) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(oldSolrPost));
                for (String tag : tags) {
                    if (post.getTags() == null) {
                        post.setTags(new ArrayList<String>());
                    }
                    if (!post.getTags().contains(tag.trim())) {
                        post.getTags().add(tag.trim());
                        post.setModified((new Date()).getTime());
                    }
                }
                SolrPost newSolrPost = new SolrPost();
                postToSolr(newSolrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> SolrPost: {0}", gson.toJson(oldSolrPost));
                indexSolrPost(oldSolrPost, newSolrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void removeTag(String id, String tag) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(oldSolrPost));
                if (post.getTags() == null) {
                    post.setTags(new ArrayList<String>());
                }
                if (post.getTags().contains(tag)) {
                    post.getTags().remove(tag);
                    post.setModified((new Date()).getTime());
                }
                SolrPost newSolrPost = new SolrPost();
                postToSolr(newSolrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> SolrPost: {0}", gson.toJson(oldSolrPost));
                indexSolrPost(oldSolrPost, newSolrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * 
     */
    public void addPlaces(String id, String place) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id + "\"");
            QueryResponse rsp = server.query(query);

            if (rsp.getResults().getNumFound() == 1) {
                SolrPost oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(oldSolrPost));
                placeDao.retrieveByExtendedString(place);
                post.getFromUser().setPlace(new Place());
                /*
                 * if (!post.getTags().contains(tag.trim())) {
                 * post.getTags().add(tag.trim()); post.setModified((new
                 * Date()).getTime());
                    }
                 */

                SolrPost newSolrPost = new SolrPost();
                postToSolr(newSolrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> SolrPost: {0}", gson.toJson(oldSolrPost));
                indexSolrPost(oldSolrPost, newSolrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void removePlace(String id, String place) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id + "\"");
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(oldSolrPost));
                if (post.getFromUser().getPlace() == null) {
                    post.getFromUser().setPlace(new Place());
                }
                /*
                 * if (post.getTags().contains(tag)) {
                 * post.getTags().remove(tag); post.setModified((new
                 * Date()).getTime());
                }
                 */
                SolrPost newSolrPost = new SolrPost();
                postToSolr(newSolrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> SolrPost: {0}", gson.toJson(oldSolrPost));
                indexSolrPost(oldSolrPost, newSolrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * 
     */
    public void updateRelevance(String id, Integer relevance) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery("id:\"" + id + "\"");
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Id: {0}", id);
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() == 1) {
                SolrPost oldSolrPost = rsp.getBeans(SolrPost.class).get(0);
                Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SolrPost: {0}", gson.toJson(oldSolrPost));
                // Si cambia la relevancia, modifico la fecha de modificado.
                if (relevance != 0) {
                    post.setModified((new Date()).getTime());
                }
                post.setRelevance(post.getRelevance() + relevance);
                post.setRelevanceDelta(post.getRelevanceDelta() + relevance);
                SolrPost newSolrPost = new SolrPost();
                postToSolr(newSolrPost, post);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> NewSolrPost: {0}", gson.toJson(newSolrPost));
                indexSolrPost(oldSolrPost, newSolrPost);
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void updateDate(String q, Long newDate) throws IOException {
        try {
            SolrQuery query = new SolrQuery();
            query.setQuery(q);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Query: {0}", q);
            QueryResponse rsp = server.query(query);
            if (rsp.getResults().getNumFound() > 0) {
                for (SolrPost oldSolrPost : rsp.getBeans(SolrPost.class)) {
                    Post post = gson.fromJson(oldSolrPost.getVerbatim(), Post.class);
                    post.setId(oldSolrPost.getId());
                    post.setCreated(newDate);
                    post.setModified(newDate);
                    SolrPost newSolrPost = new SolrPost();
                    postToSolr(newSolrPost, post);
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> OldSolrPost: {0}", gson.toJson(oldSolrPost));
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> NewSolrPost: {0}", gson.toJson(newSolrPost));
                    indexSolrPost(oldSolrPost, newSolrPost);
                }
            }
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void postToSolr(SolrPost solrPost, Post post) {
        solrPost.setId(post.getId());
        solrPost.setDocType(post.getDocType());
        Zone zone = post.getZone();
        Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, "------------>ZONE: {0}", zone);
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
            solrPost.setZoneExtendedString(WordUtils.capitalize(post.getZone().getExtendedString().replace("_", " ")));
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
        solrPost.setExtendedString(WordUtils.capitalize((post.getFromUser().getPlace() != null ? post.getFromUser().getPlace().getName() + ", " : "") + post.getZone().getExtendedString().replace("_", " ")));
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
            Post post = null;

            if (json != null) {
           //     post = gson.fromJson(json.replace("\\\"", "\"").replace("\\'", "\""), Post.class);
                post = gson.fromJson(json, Post.class);
            }

            zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "-----------> Doc: {0}\n-----------> Post: {1}", new Object[]{json, post});

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

            String aPlaces = req.getParameter("aPlaces");
            if (aPlaces
                    != null) {
                addPlaces(post.getId(), aPlaces);
            }

            String rPlace = req.getParameter("rPlace");
            if (rPlace
                    != null) {
                removePlace(post.getId(), rPlace);
            }

            String relevance = req.getParameter("rel");
            if (relevance
                    != null) {
                updateRelevance(post.getId(), Integer.valueOf(relevance));
            }

            String query = req.getParameter("q");
            String newDate = req.getParameter("newDate");
            if (query != null && newDate != null) {
                updateDate(query, Long.parseLong(newDate));
            }

            if (aTags
                    == null && rTag
                    == null && relevance == null && query == null && newDate == null) {
                indexPost(post);
            }

            resp.getWriter().print(gson.toJson(post));
        } catch (SolrServerException ex) {
            Logger.getLogger(ZSolrServer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
