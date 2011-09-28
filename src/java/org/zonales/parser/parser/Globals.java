package org.zonales.parser.parser;

import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.logging.Level;
import java.util.logging.Logger;

//import nl.msd.jdots.JD_Object;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author juanma
 */
public class Globals {

    //public static ZCrawling zcrawling;
    //public static PrintWriter out;
    //public static Boolean siosi = false;
    public static Integer port;
    public static String host;
    public static String db;

    public static HttpURLConnection getURLConnection(String url, int timeout) {
        try {
            HttpURLConnection connection = (HttpURLConnection) (new URL(url.replace(" ", "+"))).openConnection();
            connection.setRequestMethod("GET");
            connection.setConnectTimeout(timeout);
            connection.setReadTimeout(timeout);
            connection.connect();
            return connection;
        } catch (IOException ex) {
            Logger.getLogger(Globals.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    public static String getStringFromInpurStream(InputStream is) throws IOException {
        StringBuilder resultado = new StringBuilder();
        int character = 0;

        //System.out.println("-------------");
        while ((character = is.read()) != -1) {
            resultado.append(Character.toString((char) character));
            //  System.out.print(Character.toString((char) character));
        }
        //System.out.println("-------------");

        return resultado.toString();
    }
}
