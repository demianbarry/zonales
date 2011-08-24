/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package org.apache.nutch.parse.feed;

// JDK imports
import com.google.gson.Gson;
import java.io.File;
import java.net.MalformedURLException;
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.Map.Entry;
import java.io.StringWriter;
import java.io.Writer;
import java.util.ArrayList;
import java.io.PrintStream;

// APACHE imports
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.util.StringUtils;
import org.apache.nutch.metadata.Feed;
import org.apache.nutch.metadata.Metadata;
import org.apache.nutch.net.URLFilters;
import org.apache.nutch.net.URLNormalizers;
import org.apache.nutch.net.protocols.Response;
import org.apache.nutch.parse.Outlink;
import org.apache.nutch.parse.Parse;
import org.apache.nutch.parse.ParseData;
import org.apache.nutch.parse.ParseResult;
import org.apache.nutch.parse.ParseStatus;
import org.apache.nutch.parse.ParseText;
import org.apache.nutch.parse.Parser;
import org.apache.nutch.parse.ParserFactory;
import org.apache.nutch.parse.ParserNotFound;
import org.apache.nutch.protocol.Content;
import org.apache.nutch.util.EncodingDetector;
import org.apache.nutch.util.NutchConfiguration;
import org.xml.sax.InputSource;


// ROME imports
import com.sun.syndication.feed.synd.SyndCategory;
import com.sun.syndication.feed.synd.SyndContent;
import com.sun.syndication.feed.synd.SyndEntry;
import com.sun.syndication.feed.synd.SyndFeed;
import com.sun.syndication.feed.synd.SyndPerson;
import com.sun.syndication.io.SyndFeedInput;
import java.io.BufferedReader;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import org.apache.nutch.parse.generateXZone.*;

import java.io.ByteArrayInputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import javax.swing.text.BadLocationException;
import javax.swing.text.html.*;
import javax.swing.text.html.HTML;
import javax.swing.text.Element;
import javax.swing.text.ElementIterator;
import java.net.URL;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.HttpURLConnection;
import java.net.URLDecoder;
import java.util.StringTokenizer;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.select.Elements;

/**
 *
 * @author dogacan
 * @author mattmann
 * @since NUTCH-444
 *
 * <p>
 * A new RSS/ATOM Feed{@link Parser} that rapidly parses all referenced links
 * and content present in the feed.
 * </p>
 *
 */
public class FeedParser extends HttpServlet implements Parser {

    public static final String CHARSET_UTF8 = "charset=UTF-8";
    public static final String TEXT_PLAIN_CONTENT_TYPE = "text/plain; "
            + CHARSET_UTF8;
    public static final Log LOG = LogFactory.getLog("org.apache.nutch.parse.feed");
    private Configuration conf;
    private ParserFactory parserFactory;
    private URLNormalizers normalizers;
    private URLFilters filters;
    private String defaultEncoding;
    public PostsType news; // Lista de Feeds, Noticias del RSS
    public PostType newEntry;
    StringWriter sw = new StringWriter();
    List<PostType> newsList = new ArrayList<PostType>();
    File archivo = null;
    FileReader fr = null;
    BufferedReader br = null;
    String key_word;
    String key_tags;
    List<String> blacklist = new ArrayList<String>();
    List<String> searchlist = new ArrayList<String>();
    List<String> tagslist = new ArrayList<String>();
    static Boolean json = false;

    /**
     * Parses the given feed and extracts out and parsers all linked items within
     * the feed, using the underlying ROME feed parsing library.
     *
     * @param content
     *          A {@link Content} object representing the feed that is being
     *          parsed by this {@link Parser}.
     *
     * @return A {@link ParseResult} containing all {@link Parse}d feeds that
     *         were present in the feed file that this {@link Parser} dealt with.
     *
     */
    public ParseResult getParse(Content content) {
        SyndFeed feed = null;
        ParseResult parseResult = new ParseResult(content.getUrl());

        EncodingDetector detector = new EncodingDetector(conf);
        detector.autoDetectClues(content, true);
        String encoding = detector.guessEncoding(content, defaultEncoding);
        try {
            InputSource input = new InputSource(new ByteArrayInputStream(content.getContent()));
            input.setEncoding(encoding);
            SyndFeedInput feedInput = new SyndFeedInput();
            feed = feedInput.build(input);
        } catch (Exception e) {
            // return empty parse
            LOG.warn("Parse failed: url: " + content.getUrl() + ", exception: "
                    + StringUtils.stringifyException(e));
            return new ParseStatus(e).getEmptyParseResult(content.getUrl(), getConf());
        }

        List entries = feed.getEntries();
        String feedLink = feed.getLink();
        try {
            feedLink = normalizers.normalize(feedLink, URLNormalizers.SCOPE_OUTLINK);
            if (feedLink != null) {
                feedLink = filters.filter(feedLink);
            }
        } catch (Exception e) {
            feedLink = null;
        }

        String feedDesc = stripTags(feed.getDescriptionEx());
        String feedTitle = stripTags(feed.getTitleEx());

        Gson gson = new Gson();

        for (Iterator i = entries.iterator(); i.hasNext();) {

            SyndEntry entry = (SyndEntry) i.next();

            try {
                if (findWords(entry.getTitle(), entry.getContents().toString(), entry.getLink(), searchlist, blacklist)) {

                    newEntry = new PostType();
                    newEntry.setSource(feedLink.substring(7));
                    // newEntry.setId(entry.getUri());
                    // newEntry.setId(entry.getUri() != null && entry.getUri().length() > 0 ? entry.getUri().trim() : entry.getLink().trim()+entry.getTitle().trim());
                    newEntry.setId(entry.getTitle().trim());
                    newEntry.setFromUser(new User(null, entry.getAuthor(), null, null));
                    newEntry.setTitle(entry.getTitle());
                    newEntry.setText(entry.getDescription().getValue());
                    newEntry.setTags(new TagsType(tagslist));
                    newEntry.setLinks(getLinks(entry.getContents().toString(), entry.getLink()));
                    if (newEntry.getLinks() == null) {
                        newEntry.setLinks(new LinksType(new ArrayList<LinkType>()));
                    }
                    newEntry.getLinks().getLink().add(new LinkType("source", entry.getLink()));
                    newEntry.setCreated(String.valueOf(entry.getPublishedDate().getTime()));
                    newEntry.setModified(String.valueOf(entry.getUpdatedDate() != null ? entry.getUpdatedDate().getTime() : entry.getPublishedDate().getTime()));
                    newEntry.setRelevance(0);
                    if (!json) {
                        newEntry.setVerbatim(gson.toJson(newEntry));
                    }

                    newsList.add(newEntry);

                    // addToMap(parseResult, feed, feedLink, entry, content, newEntry);
                }
            } catch (FileNotFoundException ex) {
                Logger.getLogger(FeedParser.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IOException ex) {
                Logger.getLogger(FeedParser.class.getName()).log(Level.SEVERE, null, ex);
            } catch (BadLocationException ex) {
                Logger.getLogger(FeedParser.class.getName()).log(Level.SEVERE, null, ex);
            }
        }




        try {

            news = new PostsType(newsList);
            if (!json) {
                Feed2XML(news, sw);
            }
        } catch (Exception ex) {
            Logger.getLogger(FeedParser.class.getName()).log(Level.SEVERE, null, ex);
        }

        parseResult.put(content.getUrl(), new ParseText(json ? gson.toJson(news) : sw.toString()), new ParseData(
                new ParseStatus(ParseStatus.SUCCESS), feedTitle, new Outlink[0],
                content.getMetadata()));

        return parseResult;
    }

    public void Feed2XML(PostsType posts, Writer out) throws Exception {
        JAXBContext context = JAXBContext.newInstance(posts.getClass());
        Marshaller marshaller = context.createMarshaller();
        marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
        marshaller.marshal(posts, out);

        //System.out.println("data: " + out);
    }

    /**
     *
     * Sets the {@link Configuration} object for this {@link Parser}. This
     * {@link Parser} expects the following configuration properties to be set:
     *
     * <ul>
     * <li>URLNormalizers - properties in the configuration object to set up the
     * default url normalizers.</li>
     * <li>URLFilters - properties in the configuration object to set up the
     * default url filters.</li>
     * </ul>
     *
     * @param conf
     *          The Hadoop {@link Configuration} object to use to configure this
     *          {@link Parser}.
     *
     */
    public void setConf(Configuration conf) {
        this.conf = conf;
        this.parserFactory = new ParserFactory(conf);
        this.normalizers = new URLNormalizers(conf, URLNormalizers.SCOPE_OUTLINK);
        this.filters = new URLFilters(conf);
        this.defaultEncoding =
                conf.get("parser.character.encoding.default", "UTF-8");
    }

    /**
     *
     * @return The {@link Configuration} object used to configure this
     *         {@link Parser}.
     */
    public Configuration getConf() {
        return this.conf;
    }

    /**
     * Runs a command line version of this {@link Parser}.
     *
     * @param args
     *          A single argument (expected at arg[0]) representing a path on the
     *          local filesystem that points to a feed file.
     *
     * @throws Exception
     *           If any error occurs.
     */
    private void addToMap(ParseResult parseResult, SyndFeed feed,
            String feedLink, SyndEntry entry, Content content, PostType newEntry) {
        String link = entry.getLink(), text = null, title = null;
        Metadata parseMeta = new Metadata(), contentMeta = content.getMetadata();
        Parse parse = null;
        SyndContent description = entry.getDescription();

        try {
            link = normalizers.normalize(link, URLNormalizers.SCOPE_OUTLINK);

            if (link != null) {
                link = filters.filter(link);
            }
        } catch (Exception e) {
            e.printStackTrace();
            return;
        }

        if (link == null) {
            return;
        }

        title = stripTags(entry.getTitleEx());

        if (feedLink != null) {
            parseMeta.set("feed", feedLink);
        }

        addFields(parseMeta, contentMeta, feed, entry, newEntry);

        // some item descriptions contain markup text in them,
        // so we temporarily set their content-type to parse them
        // with another plugin
        String contentType = contentMeta.get(Response.CONTENT_TYPE);

        if (description != null) {
            text = description.getValue();
        }

        if (text == null) {
            List contents = entry.getContents();
            StringBuilder buf = new StringBuilder();
            for (Iterator i = contents.iterator(); i.hasNext();) {
                SyndContent syndContent = (SyndContent) i.next();
                buf.append(syndContent.getValue());
            }
            text = buf.toString();
        }

        try {
            Parser parser = parserFactory.getParsers(contentType, link)[0];
            parse = parser.getParse(
                    new Content(link, link, text.getBytes(), contentType, contentMeta,
                    conf)).get(link);
        } catch (ParserNotFound e) { /* ignore */

        }

        if (parse != null) {
            ParseData data = parse.getData();
            data.getContentMeta().remove(Response.CONTENT_TYPE);
            mergeMetadata(data.getParseMeta(), parseMeta);
            parseResult.put(link, new ParseText(parse.getText()), new ParseData(
                    ParseStatus.STATUS_SUCCESS, title, data.getOutlinks(), data.getContentMeta(), data.getParseMeta()));
        } else {
            contentMeta.remove(Response.CONTENT_TYPE);
            parseResult.put(link, new ParseText(text), new ParseData(
                    ParseStatus.STATUS_FAILURE, title, new Outlink[0], contentMeta,
                    parseMeta));
        }

    }

    private static String stripTags(SyndContent c) {
        if (c == null) {
            return "";
        }

        String value = c.getValue();

        String[] parts = value.split("<[^>]*>");
        StringBuffer buf = new StringBuffer();

        for (String part : parts) {
            buf.append(part);
        }

        return buf.toString().trim();
    }

    private void addFields(Metadata parseMeta, Metadata contentMeta,
            SyndFeed feed, SyndEntry entry, PostType newEntry) {
        List authors = entry.getAuthors(), categories = entry.getCategories();
        Date published = entry.getPublishedDate(), updated = entry.getUpdatedDate();
        String contentType = null;

        if (authors != null) {
            for (Object o : authors) {
                SyndPerson author = (SyndPerson) o;
                String authorName = author.getName();
                if (checkString(authorName)) {
                    parseMeta.add(Feed.FEED_AUTHOR, authorName);
                }
            }
        } else {
            // getAuthors may return null if feed is non-atom
            // if so, call getAuthor to get Dublin Core module creator.
            String authorName = entry.getAuthor();
            if (checkString(authorName)) {
                parseMeta.set(Feed.FEED_AUTHOR, authorName);
            }
        }

        for (Iterator i = categories.iterator(); i.hasNext();) {
            parseMeta.add(Feed.FEED_TAGS, ((SyndCategory) i.next()).getName());
        }

        if (published != null) {
            parseMeta.set(Feed.FEED_PUBLISHED, Long.toString(published.getTime()));
        }
        if (updated != null) {
            parseMeta.set(Feed.FEED_UPDATED, Long.toString(updated.getTime()));
        }
        if (newEntry.getTitle() != null) {
            parseMeta.set(Feed.FEED_TITLE, newEntry.getTitle());
        }
        if (newEntry.getId() != null) {
            parseMeta.set(Feed.FEED_ID, newEntry.getId());
        }
        if (newEntry.getText() != null) {
            parseMeta.set(Feed.FEED_TEXT, newEntry.getText());
        }
        if (String.valueOf(newEntry.getRelevance()) != null) {
            parseMeta.set(Feed.FEED_RELEVANCE, String.valueOf(newEntry.getRelevance()));
        }
        if (newEntry.getVerbatim() != null) {
            parseMeta.set(Feed.FEED_VERBATIM, newEntry.getVerbatim());
        }

        SyndContent description = entry.getDescription();
        if (description != null) {
            contentType = description.getType();
        } else {
            // TODO: What to do if contents.size() > 1?
            List contents = entry.getContents();
            if (contents.size() > 0) {
                contentType = ((SyndContent) contents.get(0)).getType();
            }
        }

        if (checkString(contentType)) {
            // ROME may return content-type as html
            if (contentType.equals("html")) {
                contentType = "text/html";
            } else if (contentType.equals("xhtml")) {
                contentType = "text/xhtml";
            }
            contentMeta.set(Response.CONTENT_TYPE, contentType + "; " + CHARSET_UTF8);
        } else {
            contentMeta.set(Response.CONTENT_TYPE, TEXT_PLAIN_CONTENT_TYPE);
        }

    }

    private void mergeMetadata(Metadata first, Metadata second) {
        for (String name : second.names()) {
            String[] values = second.getValues(name);

            for (String value : values) {
                first.add(name, value);
            }
        }
    }

    private boolean checkString(String s) {
        return s != null && !s.equals("");
    }

    public static boolean findWords(String title, String ConDatos, String url, List<String> slist, List<String> blist) throws FileNotFoundException, IOException, BadLocationException {

        String contenido;
        int find = 0;
        if (ConDatos.indexOf("[]") > -1) {
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

    /*************************************/
    public static LinksType getLinks(String ConDatos, String url) throws FileNotFoundException, IOException, BadLocationException {

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

    private void setParameters(String keyword) {
        this.key_word = keyword;
    }

    public StringBuffer getContent(String url) throws MalformedURLException, IOException {

        HttpURLConnection conn = getURLConnection(URLDecoder.decode(url, "UTF-8"), 60000);

        //InputStreamReader isr = new InputStreamReader(conn.getInputStream());

        //BufferedReader in = new BufferedReader(isr);

        StringBuffer sb = new StringBuffer();
        sb.append(getStringFromInpurStream(conn.getInputStream()));
        //String inputLine;

        /*//Guarda el contenido de la URL
        while ((inputLine = in.readLine()) != null) {
        sb.append(inputLine + "\r\n");
        }*/
        return sb;
    }

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        try {
            response.setCharacterEncoding("UTF-8");
            String query = request.getParameter("url");
            String tags = request.getParameter("tags");
            String filters = request.getParameter("filtros");
            String[] params = {query};
            //main(params, response.getWriter());
            System.setOut(new PrintStream(response.getOutputStream()));
        } catch (Exception ex) {
            Logger.getLogger(FeedParser.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public void doPost(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }

    public void setSearchList(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (Iterator i = listwords.iterator(); i.hasNext();) {
            String words = (String) i.next();
            this.searchlist.add(words);
        }
    }

    public void setBlackList(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (Iterator i = listwords.iterator(); i.hasNext();) {
            String words = (String) i.next();
            this.blacklist.add(words);
        }
    }

    public void setTags(String filters) {

        List<String> listwords = new ArrayList<String>();
        StringTokenizer tokens = new StringTokenizer(filters);

        while (tokens.hasMoreTokens()) {
            listwords.add(tokens.nextToken());
        }

        for (Iterator i = listwords.iterator(); i.hasNext();) {
            String words = (String) i.next();
            this.tagslist.add(words);
        }
    }

    public static void main(String[] args) throws Exception {
        main(args, System.out);

    }

    public static void main(String[] args, PrintStream out) throws Exception {


        String url = args[0];
        String words = "";
        String notwords = "";
        String tags = "";
        //System.out.println("Call Main");


        for (int i = 1; i < args.length; i++) {

            if (args[i].startsWith("-palabras")) {
                //System.out.println("1-Recibio comando" + args[i]);
                i++;
                while (i < args.length) {
                    if (!args[i].startsWith("-")) {
                        words += " " + args[i];
                        i++;
                    } else {
                        --i;
                        break;
                    }
                }
            } else if (args[i].startsWith("-nopalabras")) {
                //  System.out.println("2-Recibio comando" + args[i]);
                i++;
                while (i < args.length) {
                    if (!args[i].startsWith("-")) {
                        notwords += " " + args[i];
                        i++;
                    } else {
                        --i;
                        break;
                    }
                }
            } else if (args[i].startsWith("-tags")) {
                //  System.out.println("3-Recibio comando" + args[i]);
                i++;
                while (i < args.length) {
                    if (!args[i].startsWith("-")) {
                        tags += " " + args[i];
                        i++;
                    } else {
                        --i;
                        break;
                    }
                }
            } else if (args[i].startsWith("-json")) {
                //   System.out.println("4-Recibio comando" + args[i]);
                json = true;
            }

        }
        Configuration conf = NutchConfiguration.create();
        FeedParser parser = new FeedParser();
        parser.setConf(conf);

        if (!"".equals(words)) {
            parser.setSearchList(words);
            // System.out.println("Lista de palabras"+words);

        }
        if (!"".equals(notwords)) {
            parser.setBlackList(notwords);
            //System.out.println("Lista de palabras negras"+notwords);

        }
        if (!"".equals(tags)) {
            parser.setTags(tags);
            //System.out.println("Tags"+tags);

        }

        StringBuffer sb = parser.getContent(url);
        byte[] bytes = new byte[sb.length()];
        bytes = sb.toString().getBytes();

        ParseResult parseResult = parser.getParse(new Content(url, url,
                bytes, "application/rss+xml", new Metadata(), conf));

        for (Entry<Text, Parse> entry : parseResult) {
            //    System.out.println("key: " + entry.getKey());
            Parse parse = entry.getValue();
            //    System.out.println("data: " + parse.getData());
            out.println(parse.getText());
        }

    }

    private HttpURLConnection getURLConnection(String url, int timeout) throws MalformedURLException, IOException {
        HttpURLConnection connection = (HttpURLConnection) (new URL(url.replace(" ", "+"))).openConnection();
        connection.setRequestMethod("GET");
        connection.setConnectTimeout(timeout);
        connection.setReadTimeout(timeout);
        connection.setRequestProperty("Content-type", "application/rss+xml;charset=iso-8859-1");
        connection.setRequestProperty("Accept-Charset", "ISO-8859-1");
        connection.connect();
        return connection;
    }

    private String getStringFromInpurStream(InputStream is) {
        StringBuilder resultado = new StringBuilder();
        int character = 0;

        try {
            while ((character = is.read()) != -1) {
                resultado.append(Character.toString((char) character));
            }

        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, null, ex);
        }
        return resultado.toString();
    }
}
