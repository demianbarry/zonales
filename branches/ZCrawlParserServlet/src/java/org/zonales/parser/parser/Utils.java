package org.zonales.parser.parser;


import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author juanma
 */
public class Utils {    
    public static HttpURLConnection getURLConnection(String url, int timeout) throws MalformedURLException, IOException {
        HttpURLConnection connection = (HttpURLConnection) (new URL(url.replace(" ", "+"))).openConnection();
        connection.setRequestMethod("GET");
        connection.setConnectTimeout(timeout);
        connection.setReadTimeout(timeout);
        connection.setRequestProperty("Content-type", "application/rss+xml;charset=utf-8");
        connection.setRequestProperty("Accept-Charset", "UTF-8");
        connection.connect();
        return connection;
    }

    public static String getStringFromInpurStream(InputStream is) throws IOException {
        StringBuilder resultado = new StringBuilder();
        int character = 0;
        while ((character = is.read()) != -1) {
            resultado.append(Character.toString((char) character));
        }
        return resultado.toString();
    }
}
