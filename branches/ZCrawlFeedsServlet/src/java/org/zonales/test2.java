package org.zonales;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
        /*  String url = "http://www.tiemposur.com.ar/nota/38716-unas-mil-personas-se-concentraron-en-repudio-a-las-pol%C3%ADticas-del-gobierno";//args[0];
        //file:///home/emmanuel/Escritorio/prueba.html
        print("Fetching %s...", url);
        //div#contenedorComentariosRecientes
        Pattern patron;
        //html.js body form#f1 div#container div.main div#colizq section.news_detail article div#modComments.module-contents section.section_comments div.comentarios div#comments_contents div#comments_listado div#comment_310544.comments_list div.comment_msg div#comment_310544_text h4
        Document doc = Jsoup.connect(url).get();
        //html.js body form#f1 div#container div.main div#colizq section.news_detail article div#modComments.module-contents section.section_comments div.comentarios div#comments_contents div#comments_listado div#comment_310483.comments_list div.comment_top span.XXnot_com a
        Elements posteos = doc.select("div.container_comentarios_row"); //obtengo los post.
        Elements author;
        patron = Pattern.compile("( Votar:)");
        Matcher matcher;
        for (Element posteo : posteos) {

        author = posteo.select("span.XXnot_com");
        matcher = patron.matcher(author.text());
        matcher.find();
        //System.out.println(matcher.group(1));
        System.out.println(author.text().replaceAll(matcher.group(1), ""));
        }*/
        // CrawlSpecification crawler = new CrawlSpecification("http://canchallena.lanacion.com.ar/1473399-la-pelea-de-caruso-lombardi-exploto-en-twitter");
        //   System.out.println(crawler.toString());
        /*
        patron = Pattern.compile("( Votar:)");
        Matcher matcher;
        for (Element posteo : posteos) {

        author  = posteo.select("span.XXnot_com");
        matcher = patron.matcher(author.text());
        matcher.find();
        //System.out.println(matcher.group(1));
        System.out.println(author.text().replaceAll(matcher.group(1), ""));
        }
        JUE 19 ABR 2012
         */
        SimpleDateFormat formatoDelTexto = new SimpleDateFormat("EEE dd MMM yyyy");
        String strFecha = "JUE 19 ABR 2012";
        Date fecha = null;
        try {

            fecha = formatoDelTexto.parse(strFecha);
             System.out.println(fecha.toString());
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
