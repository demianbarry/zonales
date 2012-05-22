package org.zonales;


/*
 * To change this template, choose Tools | Templates and open the template in
 * the editor.
 */
/**
 *
 * @author emmanuel
 */
//import com.crawljax.core.configuration.CrawlSpecification;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

import java.io.IOException;
import java.lang.String;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 *
 */
public class test2 {

    public static void main(String[] args) throws IOException {
//        String url = "http://www.sur54.com/recorte-a-municipios-el-gobierno-avanza-sobre-el-superior-tribunal-de-justicia";//args[0];
//        //file:///home/emmanuel/Escritorio/prueba.html
//        print("Fetching %s...", url);
//        //div#contenedorComentariosRecientes
//        Pattern patron;
//        //html.js body form#f1 div#container div.main div#colizq section.news_detail article div#modComments.module-contents section.section_comments div.comentarios div#comments_contents div#comments_listado div#comment_310544.comments_list div.comment_msg div#comment_310544_text h4
//        Document doc = Jsoup.connect(url).get();
//        //html.js body form#f1 div#container div.main div#colizq section.news_detail article div#modComments.module-contents section.section_comments div.comentarios div#comments_contents div#comments_listado div#comment_310483.comments_list div.comment_top span.XXnot_com a
//        Elements posteos = doc.select("ul.comment li p.comment"); //obtengo los post.
//        Elements author;
//        patron = Pattern.compile("^[^<].*");
//        Matcher matcher;
//        for (Element posteo : posteos) {
////            author = posteo.select("span.XXnot_com");
//            matcher = patron.matcher(posteo.html());
//            if (matcher.find()) {
//                System.out.println("Matchea: " + posteo.html());
//            } else {
//                System.out.println("No matchea: " + posteo.html());
//            }
//            //System.out.println(matcher.group(1));
//            
//        }
        
        Pattern patron = Pattern.compile("(- )");
        Matcher matcher = patron.matcher("- 19/05/2012 19:25:47");
        if (matcher.find()) {
            System.out.println("- 19/05/2012 19:25:47".replaceAll(matcher.group(1), ""));
        } else {
            System.out.println("No matchea");
        }

         
        SimpleDateFormat formatoDelTexto = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
        String strFecha = "19/05/2012 19:25:47";
        Date fecha = null;
        try {

            fecha = formatoDelTexto.parse(strFecha);
             System.out.println(fecha.getTime());
        } catch (ParseException ex) {
            ex.printStackTrace();
        }

       

    }

    private static void print(String msg, Object... args) {
        System.out.println(String.format(msg, args));
    }

    private static String trim(String s, int width) {
        if (s.length() > width) {
            return s.substring(0, width - 1) + ".";
        } else {
            return s;
        }
    }
}
