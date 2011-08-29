
import com.google.gson.Gson;
import com.sun.syndication.feed.rss.Content;
import it.sauronsoftware.feed4j.FeedParser;
import it.sauronsoftware.feed4j.bean.Feed;
import it.sauronsoftware.feed4j.bean.FeedItem;
import java.io.ByteArrayInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.io.StringWriter;
import java.io.Writer;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.StringTokenizer;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.swing.text.BadLocationException;
import javax.swing.text.Element;
import javax.swing.text.ElementIterator;

import javax.swing.text.html.HTML;
import javax.swing.text.html.HTMLDocument;
import javax.swing.text.html.HTMLEditorKit;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.parser.Parser;
import org.jsoup.select.Elements;
import org.zonales.entities.LinkType;
import org.zonales.entities.LinksType;
import org.zonales.entities.PostType;
import org.zonales.entities.PostsType;
import org.zonales.entities.TagsType;
import org.zonales.entities.User;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author juanma
 */
public class ZCrawlFeedsServlet extends HttpServlet {

    List<String> blacklist = new ArrayList<String>();
    List<String> searchlist = new ArrayList<String>();
    List<String> tagslist = new ArrayList<String>();
    StringWriter sw = new StringWriter();

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
        response.setCharacterEncoding("ISO-8859-1");
        String url = request.getParameter("url") != null ? request.getParameter("url") : "";
        String words = request.getParameter("palabras") != null ? request.getParameter("palabras") : "";
        String nowords = request.getParameter("nopalabras") != null ? request.getParameter("nopalabras") : "";
        String tags = request.getParameter("tags") != null ? request.getParameter("tags") : "";
        String formato = request.getParameter("formato") != null ? request.getParameter("formato") : "";

        if (!"".equals(words)) {
            for (String palabra : words.split(",")) {
                searchlist.add(palabra);
            }
        }
        if (!"".equals(nowords)) {
            for (String nopalabra : nowords.split(",")) {
                blacklist.add(nopalabra);
            }
        }
        if (!"".equals(tags)) {
            for (String tag : tags.split(",")) {
                tagslist.add(tag);
            }
        }

        response.getWriter().println(getParse(java.net.URLEncoder.encode(url.toString(), "UTF-8"), "json".equalsIgnoreCase(formato)));
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doPost(request, response);
    }

    private HttpURLConnection getURLConnection(String url, int timeout) throws MalformedURLException, IOException {
        HttpURLConnection connection = (HttpURLConnection) (new URL(url.replace(" ", "+"))).openConnection();
        connection.setRequestMethod("GET");
        connection.setConnectTimeout(timeout);
        connection.setReadTimeout(timeout);
        connection.setRequestProperty("Content-type", "application/rss+xml;charset=utf-8");
        connection.setRequestProperty("Accept-Charset", "UTF-8");
        connection.connect();
        return connection;
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
    public String getParse(String url, boolean json) {
        try {
            URL feedURL = new URL(URLDecoder.decode(url, "UTF-8"));
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, null, feedURL.openConnection().getContentEncoding());
            Feed feed = FeedParser.parse(feedURL);
            
            

            List<PostType> newsList = new ArrayList<PostType>();
            PostType newEntry;
            //SyndFeed feed = null;           

            Gson gson = new Gson();

            for (int i = 0; i < feed.getItemCount(); i++) {
                FeedItem entry = feed.getItem(i);

                if (findWords(entry.getTitle(), entry.getDescriptionAsText() != null ? entry.getDescriptionAsText().toString() : entry.getElement("http://purl.org/rss/1.0/modules/content/", "content") != null ? entry.getElement("http://purl.org/rss/1.0/modules/content/", "content").getValue() : "", entry.getLink().toString(), searchlist, blacklist)) {
                    newEntry = new PostType();
                    newEntry.setSource(feed.getHeader().getLink().toString().substring(7));
                    // newEntry.setId(entry.getUri());
                    // newEntry.setId(entry.getUri() != null && entry.getUri().length() > 0 ? entry.getUri().trim() : entry.getLink().trim()+entry.getTitle().trim());
                    newEntry.setId(entry.getGUID());
                    newEntry.setFromUser(new User(null, feed.getHeader().getLink().toString().substring(7), null, null));
                    newEntry.setTitle(entry.getTitle());
                    newEntry.setText(entry.getDescriptionAsText());
                    newEntry.setTags(new TagsType(tagslist));
                    newEntry.setLinks(getLinks(null, entry.getLink().toString()));
                    if (newEntry.getLinks() == null) {
                        newEntry.setLinks(new LinksType(new ArrayList<LinkType>()));
                    }
                    newEntry.getLinks().getLink().add(new LinkType("source", entry.getLink().toString()));
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
            if (!json) {
                Feed2XML(news, sw);
            }

            return json ? gson.toJson(news) : sw.toString();
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, null, ex);
        }
        return null;

    }

    public LinksType getLinks(String ConDatos, String url) throws FileNotFoundException, IOException, BadLocationException {

        InputStream datos = null;
        HTMLDocument doc;
        URL urltemp;
        String test = null;
        List<LinkType> list = new ArrayList<LinkType>();

        //FileInputStream datos= new FileInputStream (ConDatos);
        /*************/
        if (ConDatos != null) {
            datos = new ByteArrayInputStream(ConDatos.getBytes());
            HTMLEditorKit kit = new HTMLEditorKit();
            doc = (HTMLDocument) kit.createDefaultDocument();
            doc.putProperty("IgnoreCharsetDirective", Boolean.TRUE);
            Reader HTMLReader = new InputStreamReader(datos);
            //Reader HTMLReader = new ImputStreamReader (datos);
            kit.read(HTMLReader, doc, 0);

        } else {

            urltemp = new URL(url);
            //   urltemp = url;
            //    urltemp = new URL( "http://www.pagina12.com.ar/diario/suplementos/rosario/11-29219-2011-06-22.html" );
            HTMLEditorKit kit = new HTMLEditorKit();
            doc = (HTMLDocument) kit.createDefaultDocument();
            doc.putProperty("IgnoreCharsetDirective", Boolean.TRUE);
            Reader HTMLReader = new InputStreamReader(urltemp.openConnection().getInputStream());
            kit.read(HTMLReader, doc, 0);

        }
        /*************/
        ElementIterator it = new ElementIterator(doc);
        Element elem = null;


        while ((elem = it.next()) != null) {

            if ((elem.getName().equals("img"))) {
                String img = "picture";
                String s = (String) elem.getAttributes().getAttribute(HTML.Attribute.SRC);
                /***************/
                if ((s.indexOf("http://www")) == 0) {
                    list.add(new LinkType(img, s));
                }
            }

        }

        if (list.isEmpty()) {
            return null;
        } else {
            return new LinksType(list);
        }

    }

    /*************************************/
    public void getActions(URL url) throws IOException, BadLocationException {

        InputStream datos = null;
        HTMLDocument doc;
        URL urltemp;
        //FileInputStream datos= new FileInputStream (ConDatos);
        /*************/
        //urltemp = new URL(url, ConDatos);
        //urltemp = url;
        //URL url = new URL( "http://www.pagina12.com.ar/diario/suplementos/rosario/11-29219-2011-06-22.html" );
        HTMLEditorKit kit = new HTMLEditorKit();
        doc = (HTMLDocument) kit.createDefaultDocument();
        doc.putProperty("IgnoreCharsetDirective", Boolean.TRUE);
        Reader HTMLReader = new InputStreamReader(url.openConnection().getInputStream());
        kit.read(HTMLReader, doc, 0);

        /*************/
        ElementIterator it = new ElementIterator(doc);
        Element elem;


        while ((elem = it.next()) != null) {

            if ((elem.getName().equals("comment"))) {

                String s = (String) elem.getAttributes().getAttribute(HTML.Attribute.SRC);

                if (s != null) {
                    System.out.println(s);
                }
            }
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

    public static boolean findWords(String title, String ConDatos, String url, List<String> slist, List<String> blist) throws FileNotFoundException, IOException, BadLocationException {

        String contenido;
        int find = 0;
        if ("".equals(ConDatos)) {
            Document doc = Jsoup.connect(url).get();
            Elements noticia = doc.select("p:not([class])");//.not("[class"); // a with href
            // System.out.println(noticias.text());
            contenido = noticia.text();
        } else {
            contenido = ConDatos;
        }
        if (!slist.isEmpty()) {
            // System.out.println("Entro slist.isEmpty()");
            for (String palabra : slist) {

                if (contenido.indexOf(palabra) >= 0 || title.indexOf(palabra) >= 0) {
                    find++;
                }

            }
        }
        if (!blist.isEmpty()) {
            // System.out.println("Entro blist.isEmpty()");
            for (String palabra : blist) {

                if (contenido.indexOf(palabra) > 0 || title.indexOf(palabra) > 0) {
                    return false;
                }
            }
        }
        if (!slist.isEmpty()) {
            if (find >= slist.size()) {
                //  System.out.println("find >= slist.size() return true");
                return true;
            } else {
                //   System.out.println("find >= slist.size() return false");
                return false;
            }
        }
        if (slist.isEmpty() && !blist.isEmpty()) {
            return true;
        }
        if (slist.isEmpty() && blist.isEmpty()) {
            return true;
        }
        return false;
    }

    public void Feed2XML(PostsType posts, Writer out) throws Exception {
        JAXBContext context = JAXBContext.newInstance(posts.getClass());
        Marshaller marshaller = context.createMarshaller();
        marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
        marshaller.marshal(posts, out);

        //System.out.println("data: " + out);
    }
}
