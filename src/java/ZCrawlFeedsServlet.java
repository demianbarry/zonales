
//import com.sun.syndication.feed.rss.Content;
import java.io.IOException;
import java.io.InputStream;
import java.io.StringWriter;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.errors.ZMessages;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author juanma
 */
public class ZCrawlFeedsServlet extends HttpServlet {

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
        response.setCharacterEncoding("UTF-8");

        HashMap<String, Object> params = new HashMap<String, Object>();

        String url = request.getParameter("url") != null ? request.getParameter("url") : "";

        String zone = request.getParameter("zone") != null ? request.getParameter("zone") : "";
        params.put("zone", zone);

        String words = request.getParameter("palabras") != null ? request.getParameter("palabras") : "";
        String nowords = request.getParameter("nopalabras") != null ? request.getParameter("nopalabras") : "";
        String tags = request.getParameter("tags") != null ? request.getParameter("tags") : "";
        String formato = request.getParameter("formato") != null ? request.getParameter("formato") : "";

        String latitud = request.getParameter("latitud") != null ? request.getParameter("latitud") : "0.0";
        params.put("latitud", latitud);

        String longitud = request.getParameter("longitud") != null ? request.getParameter("longitud") : "0.0";
        params.put("longitud", longitud);

        List<String> searchlist = new ArrayList<String>();
        List<String> blacklist = new ArrayList<String>();
        List<String> tagslist = new ArrayList<String>();

        InputStream stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
        Properties props = new Properties();
        props.load(stream);

        if (!"".equals(words)) {
            searchlist = new ArrayList<String>();
            for (String palabra : words.split(",")) {
                searchlist.add(palabra);
            }
        }
        params.put("searchlist", searchlist);

        if (!"".equals(nowords)) {
            blacklist = new ArrayList<String>();
            for (String nopalabra : nowords.split(",")) {
                blacklist.add(nopalabra);
            }
        }
        params.put("blacklist", blacklist);

        if (!"".equals(tags)) {
            tagslist = new ArrayList<String>();
            for (String tag : tags.split(",")) {
                tagslist.add(tag);
            }
        }
        params.put("tagslist", tagslist);

        ZCrawlFeedsHelper helper = new ZCrawlFeedsHelper(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        try {
            response.getWriter().println(helper.getParse(java.net.URLEncoder.encode(url.toString(), "UTF-8"), "json".equalsIgnoreCase(formato), params));
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
}
