package org;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * @author Ben Souther; ben@javaranch.com
 */
public class FeedServlet extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setCharacterEncoding("UTF-8");
        String url = request.getParameter("url") != null ? request.getParameter("url") : "";
	String words = request.getParameter("palabras") != null ? request.getParameter("palabras") : "";
	String nowords = request.getParameter("nopalabras") != null ? request.getParameter("nopalabras") : "";
	String tags = request.getParameter("tags") != null ? request.getParameter("tags") : "";
	String formato = request.getParameter("formato") != null ? request.getParameter("formato") : "";

        List<String> args = new ArrayList<String>();
       // args.add("/home/rodrigo/Escritorio/nutch-1.2/bin/nutch");
        args.add("/opt/nutch-1.2/bin/nutch");
        args.add("plugin");
        args.add("feed");
        args.add("org.apache.nutch.parse.feed.FeedParser");
        args.add(java.net.URLEncoder.encode(url.toString(), "ISO-8859-1"));
      
        if (!"".equals(words)){
            args.add("-palabras");
            for(String palabra : words.split(",")) {
                args.add(palabra);
            }
        }
        if (!"".equals(nowords)){
            args.add("-nopalabras");
            for(String nopalabra : nowords.split(",")) {
                args.add(nopalabra);
            }
        }
        if (!"".equals(tags)){
            args.add("-tags");
            for(String tag : tags.split(",")) {
                args.add(tag);
            }
        }
        if (!"".equals(formato)){
            args.add("-"+formato);
        }

        ProcessBuilder pb = new ProcessBuilder(args);

      //  pb.environment().put("JAVA_HOME", "/usr/lib/jvm/java-6-sun-1.6.0.24");
        pb.environment().put("JAVA_HOME", "/usr/lib/jvm/jre");
        Process p = pb.start();
        InputStream is = p.getInputStream();
        BufferedReader br = new BufferedReader(new InputStreamReader(is));
        String resultado = "";
        String line = null;
        while ((line = br.readLine()) != null) {
            resultado += line;
        }
         /*for(String param : pb.command()){
            System.out.println(param);
        }*/
        response.getWriter().println(resultado);

    }
}
