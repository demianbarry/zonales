/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author nacho
 */
public class ConnHelper {

    public static String getStringFromInpurStream(InputStream is) {
        int character = 0;
        StringBuilder resultado = new StringBuilder();
        
        try {
            InputStreamReader reader = new InputStreamReader(is, "UTF-8");

            while ((character = reader.read()) != -1) {
                resultado.append(Character.toString((char) character));
                //System.out.print(Character.toString((char) character));
            }

        } catch (UnsupportedEncodingException ex) {
            Logger.getLogger(ConnHelper.class.getName()).log(Level.SEVERE, null, ex);
        } catch (Exception ex) {
            Logger.getLogger("ConnHelper").log(Level.SEVERE, null, ex);
        }
        return resultado.toString();

    }
    
        public static String getStringFromReader(Reader is) {
        
        StringBuilder resultado = new StringBuilder();
        int character = 0;

        try {
            //System.out.println("-------------");
            while ((character = is.read()) != -1) {
                resultado.append(Character.toString((char) character));
                //System.out.print(Character.toString((char) character));
            }
            //System.out.println("-------------");
        } catch (Exception ex) {
            Logger.getLogger("ConnHelper").log(Level.SEVERE, null, ex);
        }
        return resultado.toString();
    }

    public static HttpURLConnection getURLConnection(String url, int timeout) throws MalformedURLException, IOException {
        return getURLConnection(url, timeout, "GET");
    }

    public static HttpURLConnection getURLConnection(String url, int timeout, String method) throws MalformedURLException, IOException {
        HttpURLConnection connection = (HttpURLConnection) (new URL(url.replace(" ", "+"))).openConnection();
        connection.setRequestProperty("Accept-Charset", "UTF-8");
        connection.setRequestMethod(method);
        connection.setConnectTimeout(timeout);
        connection.setReadTimeout(timeout);        
        connection.connect();
        return connection;
    }

}
