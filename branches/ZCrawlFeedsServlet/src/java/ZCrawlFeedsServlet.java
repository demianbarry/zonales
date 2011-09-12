
import com.google.gson.Gson;
import com.sun.syndication.feed.rss.Content;
import it.sauronsoftware.feed4j.FeedParser;
import it.sauronsoftware.feed4j.bean.Feed;
import it.sauronsoftware.feed4j.bean.FeedItem;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.StringWriter;
import java.io.Writer;
import java.net.URL;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Properties;
import java.util.StringTokenizer;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.swing.text.BadLocationException;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
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
import org.zonales.entities.PostType;
import org.zonales.entities.PostsType;
import org.zonales.entities.TagsType;
import org.zonales.entities.User;
import org.zonales.errors.ZMessages;
import org.zonales.feedSelector.daos.FeedSelectorDao;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author juanma
 */
public class ZCrawlFeedsServlet extends HttpServlet {

    List<String> blacklist = null;
    List<String> searchlist = null;
    List<String> tagslist = null;
    StringWriter sw = new StringWriter();
    FeedSelectorDao dao;
    String zone = null;

    /** 
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code> methods.
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setCharacterEncoding("UTF-8");
        String url = request.getParameter("url") != null ? request.getParameter("url") : "";
        zone = request.getParameter("zone") != null ? request.getParameter("zone") : "";
        String words = request.getParameter("palabras") != null ? request.getParameter("palabras") : "";
        String nowords = request.getParameter("nopalabras") != null ? request.getParameter("nopalabras") : "";
        String tags = request.getParameter("tags") != null ? request.getParameter("tags") : "";
        String formato = request.getParameter("formato") != null ? request.getParameter("formato") : "";
        InputStream stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
        Properties props = new Properties();
        props.load(stream);

        dao = new FeedSelectorDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        if (!"".equals(words)) {
            searchlist = new ArrayList<String>();
            for (String palabra : words.split(",")) {
                searchlist.add(palabra);
            }
        }
        if (!"".equals(nowords)) {
            blacklist = new ArrayList<String>();
            for (String nopalabra : nowords.split(",")) {
                blacklist.add(nopalabra);
            }
        }
        if (!"".equals(tags)) {
            tagslist = new ArrayList<String>();
            for (String tag : tags.split(",")) {
                tagslist.add(tag);
            }
        }

        try {
            response.getWriter().println(getParse(java.net.URLEncoder.encode(url.toString(), "UTF-8"), "json".equalsIgnoreCase(formato)));
        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                    "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});

            response.getWriter().print(ZMessages.NO_DB_FAILED);
        }
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doGet(request, response);
    }

    /**
     * Parses the given feed and extracts out and parsers all linked items within
     * the feed, using the underlying ROME feed parsing library.
     *
     * @param rss
     *          A {@link Content} object representing the feed that is being
     *          parsed by this {@link Parser}.
     *
     * @return A {@link ParseResult} containing all {@link Parse}d feeds that
     *         were present in the feed file that this {@link Parser} dealt with.
     *
     */
    public String getParse(String url, boolean json) throws Exception {

        url = URLDecoder.decode(url, "UTF-8");
        URL feedURL = new URL(url);
        //Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Encoding del Feed: {0}", new Object[]{feedURL.openConnection().getContentEncoding()});
        Feed feed = FeedParser.parse(feedURL);



        List<PostType> newsList = new ArrayList<PostType>();
        PostType newEntry;
        //SyndFeed feed = null;           

        Gson gson = new Gson();

        List<LinkType> links;

        Document doc;

        FeedSelectors feedSelectors;

        for (int i = 0; i < feed.getItemCount(); i++) {
            FeedItem entry = feed.getItem(i);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Intentando conectar a {0}", new Object[]{entry.getLink().toString()});

            doc = Jsoup.connect(entry.getLink().toString()).timeout(60000).get();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Parseando la URL: {0}", new Object[]{entry.getLink().toString()});
            feedSelectors = dao.retrieve(url);
            if (findWords(entry.getTitle(), doc, searchlist, blacklist, feedSelectors)) {
                newEntry = new PostType();
                String source = feed.getHeader().getLink().toString().substring(7);
                if (source.indexOf("/") != -1) {
                    source = source.substring(0, source.indexOf("/") + 1);
                }
                newEntry.setSource(source);
                newEntry.setZone(zone);
                // newEntry.setId(entry.getUri());
                // newEntry.setId(entry.getUri() != null && entry.getUri().length() > 0 ? entry.getUri().trim() : entry.getLink().trim()+entry.getTitle().trim());
                newEntry.setId(entry.getGUID());
                newEntry.setFromUser(new User(null, feed.getHeader().getLink().toString().substring(7), null, null));
                newEntry.setTitle(entry.getTitle());
                newEntry.setText(entry.getDescriptionAsText());
                newEntry.setTags(new TagsType(tagslist));

                if (newEntry.getLinks() == null) {
                    newEntry.setLinks(new LinksType(new ArrayList<LinkType>()));
                }
                if ((links = getLinks(feedSelectors, doc)) != null) {
                    newEntry.getLinks().getLink().addAll(links);
                }
                newEntry.getLinks().getLink().add(new LinkType("source", entry.getLink().toString()));


                if (newEntry.getActions() == null) {
                    newEntry.setActions(new ActionsType(new ArrayList<ActionType>()));
                }
                newEntry.setActions(getActions(feedSelectors, doc));

                newEntry.setCreated(String.valueOf(entry.getPubDate() != null ? entry.getPubDate().getTime() : (new Date()).getTime()));
                newEntry.setModified(String.valueOf(entry.getModDate() != null ? entry.getModDate().getTime() : newEntry.getCreated()));
                newEntry.setRelevance(0);
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

        if (!json) {
            Feed2XML(news, sw);
        }

        return json ? gson.toJson(news) : sw.toString();


    }

    public List<LinkType> getLinks(FeedSelectors feedSelectors, Document doc) throws FileNotFoundException, IOException, BadLocationException {

        List<LinkType> list = new ArrayList<LinkType>();

        //FileInputStream datos= new FileInputStream (ConDatos);
        /*************/
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
                    list.add(new LinkType("picture", elmt.attr("src")));
                }
            } else if ("link".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                for (Element elmt : elmts) {
                    list.add(new LinkType("link", elmt.attr("href")));
                }
            }
        }

        if (list.isEmpty()) {
            return null;
        } else {
            return list;
        }

    }

    /*************************************/
    public ActionsType getActions(FeedSelectors feedSelectors, Document doc) throws IOException, BadLocationException {
        List<ActionType> list = new ArrayList<ActionType>();

        //FileInputStream datos= new FileInputStream (ConDatos);
        /*************/
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            feedSelectors = dao.retrieve("default");
        }
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            return null;
        }

        Elements elmts;
        for (FeedSelector feedSelector : feedSelectors.getSelectors()) {
            if ("comments".equals(feedSelector.getType())) {
                elmts = doc.select(feedSelector.getSelector());
                list.add(new ActionType("comments", elmts.size()));

            }
        }

        if (list.isEmpty()) {
            return null;
        } else {
            return new ActionsType(list);
        }

    }

    public void setSearchList(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (String word : listwords) {
            this.searchlist.add(word);
        }
    }

    public void setBlackList(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (String word : listwords) {
            this.blacklist.add(word);
        }
    }

    public void setTags(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (String word : listwords) {
            this.tagslist.add(word);
        }
    }

    public boolean findWords(String title, Document doc, List<String> slist, List<String> blist, FeedSelectors feedSelectors) throws FileNotFoundException, IOException, BadLocationException {
        String contenido = null;

        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            feedSelectors = dao.retrieve("default");
        }
        if (feedSelectors == null || feedSelectors.getSelectors() == null || feedSelectors.getSelectors().isEmpty()) {
            return false;
        }

        Elements noticia = null;
        for (FeedSelector feedSelector : feedSelectors.getSelectors()) {
            if ("content".equals(feedSelector.getType())) {
                noticia = doc.select(feedSelector.getSelector());
            }
        }

        if (noticia != null) {
            contenido = noticia.text();
        }

        if (slist != null && !slist.isEmpty()) {
            // System.out.println("Entro slist.isEmpty()");
            for (String palabra : slist) {
                if ((title == null || title.indexOf(palabra) == -1) && (contenido == null || contenido.indexOf(palabra) == -1)) {
                    return false;
                }

            }
        }

        if (blist != null && !blist.isEmpty()) {
            // System.out.println("Entro blist.isEmpty()");
            for (String palabra : blist) {
                if ((title != null && title.indexOf(palabra) != -1) || (contenido != null && contenido.indexOf(palabra) != -1)) {
                    return false;
                }
            }
        }

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "findWords result: {0}", new Object[]{true});
        return true;
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
