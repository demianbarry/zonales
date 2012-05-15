
import com.google.gson.Gson;

//import com.sun.syndication.feed.rss.Content;
import it.sauronsoftware.feed4j.FeedParser;
import it.sauronsoftware.feed4j.bean.Feed;
import it.sauronsoftware.feed4j.bean.FeedItem;
import java.awt.Image;
import java.awt.Toolkit;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.StringWriter;
import java.io.Writer;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.text.AbstractDocument.Content;
import javax.swing.text.BadLocationException;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import org.apache.commons.lang3.text.WordUtils;
import org.jsoup.Connection;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.parser.Parser;
import org.jsoup.select.Elements;
import org.zonales.entities.ActionType;
import org.zonales.entities.ActionsType;
import org.zonales.entities.FeedSelector;
import org.zonales.entities.FeedSelectors;
import org.zonales.entities.LinkType;
import org.zonales.entities.LinksType;

import org.zonales.entities.Post;
import org.zonales.entities.PostType;
import org.zonales.entities.PostsType;
import org.zonales.entities.TagsType;
import org.zonales.entities.User;
import org.zonales.entities.Zone;
import org.zonales.entities.Comment;
import org.zonales.feedSelector.daos.FeedSelectorDao;
import org.zonales.tagsAndZones.daos.PlaceDao;
import org.zonales.tagsAndZones.daos.ZoneDao;
import org.zonales.tagsAndZones.objects.Place;

/*
 * To change this template, choose Tools | Templates and open the template in
 * the editor.
 */
/**
 *
 * @author juanma
 */
public class ZCrawlFeedsHelper {

    StringWriter sw = new StringWriter();
    FeedSelectorDao dao;
    ZoneDao zoneDao;
    PlaceDao placeDao;
    Integer maxImgSize;
    List<Comment> comments = null;

    public ZCrawlFeedsHelper(String host, Integer port, String name, Integer maxImgSize) {
        dao = new FeedSelectorDao(host, port, name);
        zoneDao = new ZoneDao(host, port, name);
        placeDao = new PlaceDao(host, port, name);
        this.maxImgSize = maxImgSize;
    }

    /**
     * Parses the given feed and extracts out and parsers all linked items
     * within the feed, using the underlying ROME feed parsing library.
     *
     * @param rss A {@link Content} object representing the feed that is being
     * parsed by this {@link Parser}.
     *
     * @return A {@link ParseResult} containing all {@link Parse}d feeds that
     * were present in the feed file that this {@link Parser} dealt with.
     *
     */
    public String getParse(String url, boolean json, HashMap<String, Object> params) throws Exception {

        url = URLDecoder.decode(url, "UTF-8");
        URL feedURL = new URL(url);
        //Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Encoding del Feed: {0}", new Object[]{feedURL.openConnection().getContentEncoding()});
        Feed feed = FeedParser.parse(feedURL);


        List<PostType> newsList = new ArrayList<PostType>();
        List<Post> newsListSolr = new ArrayList<Post>();

        PostType newEntry;
        Post newEntrySolr;
        //SyndFeed feed = null;           

        Gson gson = new Gson();

        List<LinkType> links;

         Document doc;

        FeedSelectors feedSelectors;

        String extendedString = (String) params.get("zone");
        Place place = null;
        if (params.containsKey("place")) {
            place = placeDao.retrieveByExtendedString(extendedString.replace(", ", ",+").replace(" ", "_").replace("+", " ").toLowerCase());
        }
        org.zonales.tagsAndZones.objects.Zone zone = zoneDao.retrieveByExtendedString(extendedString.replace(", ", ",+").replace(" ", "_").replace("+", " ").toLowerCase());

        if (!json) {
            for (int i = 0; i < feed.getItemCount(); i++) {
                FeedItem entry = feed.getItem(i);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Intentando conectar a {0}", new Object[]{entry.getLink().toString()});

                Connection conn = Jsoup.connect(entry.getLink().toString());
                conn.timeout(60000);
                doc = conn.get();
                String responseURL = conn.response().url().getHost();
//                doc = Jsoup.connect(entry.getLink().toString()).timeout(60000).get();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Parseando la URL: {0}", new Object[]{entry.getLink().toString()});
                feedSelectors = dao.retrieve(url);
                if (findWords(entry.getTitle(), doc, (ArrayList) params.get("searchlist"), (ArrayList) params.get("blacklist"), feedSelectors)) {
                    newEntry = new PostType();
                    String source;
                    if (feed.getHeader() == null || feed.getHeader().getLink() == null) {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "NULL: Link");
                        source = feedURL.getHost();
                    } else {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "NO NULL: {0}", feed.getHeader().getLink().toString());
                        source = feed.getHeader().getLink().getHost();
//                        if (source.indexOf("/") != -1) {
//                            source = source.substring(0, source.indexOf("/") + 1);
//                        }
                    }
                    newEntry.setSource(source);

                    newEntry.setZone(new Zone(String.valueOf(zone.getId()), zone.getName(), zone.getType().getName(), zone.getExtendedString()));

                    newEntry.setPostLatitude(Double.parseDouble((String) params.get("latitud")));
                    newEntry.setPostLongitude(Double.parseDouble((String) params.get("longitud")));
                    // newEntry.setId(entry.getUri());
                    // newEntry.setId(entry.getUri() != null && entry.getUri().length() > 0 ? entry.getUri().trim() : entry.getLink().trim()+entry.getTitle().trim());
                    newEntry.setId(entry.getGUID());
                    newEntry.setFromUser(new User(null, source, null, null, place != null ? new org.zonales.entities.Place(String.valueOf(place.getId()), place.getName(), place.getType().getName()) : null));
                    newEntry.setTitle(entry.getTitle());
                    newEntry.setText(entry.getDescriptionAsText());
                    newEntry.setTags(new TagsType((ArrayList) params.get("tagslist")));

                    if (newEntry.getLinks() == null) {
                        newEntry.setLinks(new LinksType(new ArrayList<LinkType>()));
                    }
                    if ((links = getLinks(feedSelectors, doc, responseURL)) != null) {
                        newEntry.getLinks().getLink().addAll(links);
                    }
                    newEntry.getLinks().getLink().add(new LinkType("source", entry.getLink().toString()));


                    if (newEntry.getActions() == null) {
                        newEntry.setActions(new ActionsType(new ArrayList<ActionType>()));
                    }
                    newEntry.setActions(new ActionsType(getActions(feedSelectors, doc)));

                    if (entry.getPubDate() != null) {
                        newEntry.setCreated(String.valueOf(entry.getPubDate().getTime()));
                    }


                    if (entry.getModDate() != null) {
                        newEntry.setModified(String.valueOf(entry.getModDate().getTime()));
                    }

                    for (ActionType action : newEntry.getActions().getAction()) {
                        if ("comments".equals(action.getType())) {
                            newEntry.setRelevance(action.getCant());
                        }
                    }

                    if (!json) {
                        newEntry.setVerbatim(gson.toJson(newEntry));
                    }

                    newsList.add(newEntry);

                    // addToMap(parseResult, feed, feedLink, entry, content, newEntry);
                }
            }

            PostsType news;

            news = new PostsType(newsList);
            completeLinks(news);
            Feed2XML(news, sw);
            return sw.toString() + comments.toString();
        } else {
            for (int i = 0; i < feed.getItemCount(); i++) {
                FeedItem entry = feed.getItem(i);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Intentando conectar a {0}", new Object[]{entry.getLink().toString()});

                Connection conn = Jsoup.connect(entry.getLink().toString());
                conn.timeout(60000);
                doc = conn.get();
                String responseURL = conn.response().url().getHost();
//                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RESPONSE URL: {0}", responseURL);
//                doc = Jsoup.connect(entry.getLink().toString()).timeout(60000).get();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Parseando la URL: {0}", new Object[]{entry.getLink().toString()});
                feedSelectors = dao.retrieve(url);
                if (findWords(entry.getTitle(), doc, (ArrayList) params.get("searchlist"), (ArrayList) params.get("blacklist"), feedSelectors)) {
                    newEntrySolr = new Post();
                    String source;
                    if (feed.getHeader() == null || feed.getHeader().getLink() == null) {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "NULL: Link");
                        source = feedURL.getHost();
                    } else {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "NO NULL: {0}", feed.getHeader().getLink().toString());
                        source = feed.getHeader().getLink().getHost();
//                        if (source.indexOf("/") != -1) {
//                            source = source.substring(0, source.indexOf("/") + 1);
//                        }
                    }
                    newEntrySolr.setSource(source);
                    newEntrySolr.setZone(new Zone(String.valueOf(zone.getId()), zone.getName(), zone.getType().getName(),zone.getExtendedString()));

                    newEntrySolr.setPostLatitude(Double.parseDouble((String) params.get("latitud")));
                    newEntrySolr.setPostLongitude(Double.parseDouble((String) params.get("longitud")));
                    // newEntry.setId(entry.getUri());
                    // newEntry.setId(entry.getUri() != null && entry.getUri().length() > 0 ? entry.getUri().trim() : entry.getLink().trim()+entry.getTitle().trim());
                    newEntrySolr.setId(entry.getGUID());
                    newEntrySolr.setFromUser(new User(null, source, null, null, place != null ? new org.zonales.entities.Place(String.valueOf(place.getId()), place.getName(), place.getType().getName()) : null));
                    newEntrySolr.setTitle(entry.getTitle());
                    newEntrySolr.setText(entry.getDescriptionAsText());
                    newEntrySolr.setTags(new ArrayList<String>((ArrayList) params.get("tagslist")));

                    if (newEntrySolr.getLinks() == null) {
                        newEntrySolr.setLinks(new ArrayList<LinkType>());
                    }
                    if ((links = getLinks(feedSelectors, doc, responseURL)) != null) {
                        newEntrySolr.getLinks().addAll(links);
                    }
                    newEntrySolr.getLinks().add(new LinkType("source", entry.getLink().toString()));


                    if (newEntrySolr.getActions() == null) {
                        newEntrySolr.setActions(new ArrayList<ActionType>());
                    }
                    newEntrySolr.getActions().addAll(getActions(feedSelectors, doc));

                    if (entry.getPubDate() != null) {
                        newEntrySolr.setCreated((entry.getPubDate().getTime()));
                    }
                    if (entry.getModDate() != null) {
                        newEntrySolr.setModified((entry.getModDate().getTime()));
                    }

                    for (ActionType action : newEntrySolr.getActions()) {
                        if ("comments".equals(action.getType())) {
                            newEntrySolr.setRelevance(action.getCant());
                        }
                    }

                    if (!json) {
                        newEntrySolr.setVerbatim(gson.toJson(newEntrySolr));
                    }

                    newsListSolr.add(newEntrySolr);

                    // addToMap(parseResult, feed, feedLink, entry, content, newEntry);
                }
            }
            return "{post: " + gson.toJson(newsListSolr) + "}" + comments.toString();
        }
    }

    public List<LinkType> getLinks(FeedSelectors feedSelectors, Document doc, String host) throws FileNotFoundException, IOException, BadLocationException {

        Boolean isAvatar = false;

        List<LinkType> list = new ArrayList<LinkType>();

        //FileInputStream datos= new FileInputStream (ConDatos);
        /**
         * **********
         */
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            feedSelectors = dao.retrieve("default");
        }
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            return null;
        }
        Elements elmts;
        for (FeedSelector feedSelector : feedSelectors.getSelectors()) {
            if ("picture".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                for (Element elmt : elmts) {
                    String link = addHost(elmt.attr("src"), host);
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "PICTURE URL: {0}", link);
                    if (checkImgSize(link)) {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SIZE VALIDO, AGREGO PICTURE");
                        list.add(new LinkType("picture", link));
                    } else {
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SIZE NO VALIDO: {0}", link);
                    }
                }
            } else if ("link".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                for (Element elmt : elmts) {
                    String link = addHost(elmt.attr("href"), host);
                    list.add(new LinkType("link", link));
                }
            } else if ("avatar".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                for (Element elmt : elmts) {
                    String link = addHost(elmt.attr("src"), host);
                    list.add(new LinkType("avatar", link));
                    isAvatar = true;
                }
            }
        }


        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Antes logo");
        if (!isAvatar && feedSelectors.getUrlLogo() != null) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obtengo logo por defecto: {0}", feedSelectors.getUrlLogo());
            list.add(new LinkType("avatar", feedSelectors.getUrlLogo()));
        }

        if (list.isEmpty()) {
            return null;
        } else {
            return list;
        }

    }

    private String addHost(String link, String host) {
        if (!link.startsWith("http")) {
            if (link.startsWith("/")) {
                return "http://" + host + link;
            } else {
                return "http://" + host + "/" + link;
            }
        }

        return link;
    }

    private Boolean checkImgSize(String imageURL) {
        try {
            URL url = new URL(imageURL);
            Image image = Toolkit.getDefaultToolkit().getImage(url);
            Integer size = image.getHeight(null) * image.getWidth(null);
            if (size == 1) //El link no es una imagen, sino ASP, PHP o algo por el estilo
            {
                return true;
            }
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "IMAGE SIZE: {0}", size);
            if (maxImgSize < size) {
                return true;
            }
        } catch (MalformedURLException ex) {
            Logger.getLogger(ZCrawlFeedsHelper.class.getName()).log(Level.SEVERE, null, ex);
        }
        return false;
    }

    /**
     * **********************************
     */
    public List<ActionType> getActions(FeedSelectors feedSelectors, Document doc) throws IOException, BadLocationException {

        List<ActionType> list = new ArrayList<ActionType>();
        
        String selectorAuthor = null , selectorText  = null, selectorDate = null ;
        String author, text , id  = null, timestamp = null;
        
        //FileInputStream datos= new FileInputStream (ConDatos);
        /**
         * **********
         */
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            feedSelectors = dao.retrieve("default");
        }
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            return null;
        }

        Elements elmts = null;
        boolean commenters = false;
        for (FeedSelector feedSelector : feedSelectors.getSelectors()) {

            if ("commentsAuthor".equals(feedSelector.getType())) {
                    selectorAuthor = feedSelector.getSelector();
            }
            if ("commentsText".equals(feedSelector.getType())) {
                    selectorText = feedSelector.getSelector();
            }
            if ("commentsDate".equals(feedSelector.getType())) {
                    selectorDate = feedSelector.getSelector();
            }

            if ("comments".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                list.add(new ActionType("comments", elmts.size()));
                commenters = true;
            }
        }
        
       /* if(commenters){
         for (Element comment : elmts) {
                     author = comment.select(selectorAuthor).text();
                     text = comment.select(selectorText).text();
                     timestamp = comment.select(selectorDate).text();
                     comments.add(new Comment(id , new User (id,author,null,null,null) , text , new Date(timestamp)));
                }
        }*/

        if (list.isEmpty()) {
            return new ArrayList();
        } else {
            return new ArrayList(list);
        }

    }

    public boolean findWords(String title, Document doc, List<String> slist, List<String> blist, FeedSelectors feedSelectors) throws FileNotFoundException, IOException, BadLocationException {
        return true;
        /*
         * String contenido = null;
         *
         * if (feedSelectors == null || feedSelectors.getSelectors() == null ||
         * feedSelectors.getSelectors().isEmpty()) { feedSelectors =
         * dao.retrieve("default"); } if (feedSelectors == null ||
         * feedSelectors.getSelectors() == null ||
         * feedSelectors.getSelectors().isEmpty()) { return false; }
         *
         * Elements noticia = null; for (FeedSelector feedSelector :
         * feedSelectors.getSelectors()) { if
         * ("content".equals(feedSelector.getType())) { noticia =
         * doc.select(feedSelector.getSelector()); } }
         *
         * if (noticia != null) { contenido = noticia.text(); }
         *
         * if (slist != null && !slist.isEmpty()) { // System.out.println("Entro
         * slist.isEmpty()"); for (String palabra : slist) { if ((title == null
         * || title.indexOf(palabra) == -1) && (contenido == null ||
         * contenido.indexOf(palabra) == -1)) { return false; }
         *
         * }
         * }
         *
         * if (blist != null && !blist.isEmpty()) { // System.out.println("Entro
         * blist.isEmpty()"); for (String palabra : blist) { if ((title != null
         * && title.indexOf(palabra) != -1) || (contenido != null &&
         * contenido.indexOf(palabra) != -1)) { return false; } } }
         *
         * Logger.getLogger(this.getClass().getName()).log(Level.INFO,
         * "findWords result: {0}", new Object[]{true}); return true;
         */
    }

    public void Feed2XML(PostsType posts, Writer out) throws Exception {
        JAXBContext context = JAXBContext.newInstance(posts.getClass());
        Marshaller marshaller = context.createMarshaller();
        marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
        marshaller.marshal(posts, out);

        //System.out.println("data: " + out);
    }

    private void completeLinks(PostsType posts) {
        for (PostType post : posts.getPost()) {
            if (post.getLinks() != null) {
                for (LinkType link : post.getLinks().getLink()) {
                    if (!link.getUrl().matches("^http://.*")) {
                        link.setUrl("http://" + post.getSource() + link.getUrl().substring(link.getUrl().matches("^/.*") ? 1 : 0));
                    }
                }
            }
        }
    }
}
