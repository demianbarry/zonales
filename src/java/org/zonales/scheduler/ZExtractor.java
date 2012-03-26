/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URLEncoder;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.entities.Posts;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;
import org.zonales.scheduler.exceptions.ExtractException;

/**
 *
 * @author nacho
 */
public class ZExtractor {

    public ZExtractor() {
    }

    public Posts extract(String zGramId, String metadata, String zCralwSourcesURL, int timeout) throws MalformedURLException, IOException, ExtractException {
        Posts posts = new Posts();

        HttpURLConnection connection;
        Gson postsGson = new Gson();

        //Obtengo ultimo Hit de extraccion
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo Ultimo Hit de extracción para Id: {0}", zGramId);
        connection = ConnHelper.getURLConnection(zCralwSourcesURL + "getLastHit?id=" + zGramId, timeout);
        int code = connection.getResponseCode();

        if (code == 200) {
            Long lastHit = Long.valueOf(ConnHelper.getStringFromInpurStream(connection.getInputStream()));

            //Obtengo la URL para realizar la extracción
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo URL para la metadata: {0}", metadata);
            String url = zCralwSourcesURL + "getServiceURL?q=" + URLEncoder.encode(metadata, "UTF-8");
            if (lastHit != 0) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Since: {0}", lastHit);
                url += "&since=" + lastHit;
            }

            connection = ConnHelper.getURLConnection(url, timeout);
            code = connection.getResponseCode();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Recuperación URL extracción - Código de respuesta: {0}", code);
            if (code == 200) {                
                String urlServiceJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Recuperación URL extracción - Respuesta: {0}", urlServiceJson);
                Gson urlServiceGson = new Gson();
                UrlService urlService;
                try {
                    urlService = urlServiceGson.fromJson(urlServiceJson, UrlService.class);
                } catch (Exception e) {
                    throw new ExtractException(ZMessages.GSON_CONVERTION_ERROR);
                }
                connection.disconnect();

                if (urlService.getCod() == 100) {

                    //Capturo en este try si hay excepciones vinculadas con la URL del servlet obtenida, en caso afirmativo lanzo una ExtractException con el código correspondiente
                    try {
                        //Realizo la extracción
                        connection = ConnHelper.getURLConnection(urlService.getUrl(), timeout);
                        code = connection.getResponseCode();
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Código de respuesta: {0}", code);
                        if (code == 200) {
                            InputStream in = connection.getInputStream();
                            Reader reader = new InputStreamReader(in, connection.getContentEncoding() != null ? connection.getContentEncoding() : "UTF-8");
                            String postsJson = ConnHelper.getStringFromReader(reader);
                            //String postsJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Respuesta {0}s", postsJson);
                            connection.disconnect();
                            try {
                                posts = postsGson.fromJson(postsJson, Posts.class);
                                if (posts.getMessage() != null) {
                                    Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error en servlet de extracción: {0}", posts.getMessage());
                                        throw new ExtractException(posts.getMessage());
                                } else {
                                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Se obtuvieron {0} posts", posts.getPost().size());
                                }
                            } catch (Exception ex) {
                                StringBuilder stacktrace = new StringBuilder();
                                for (StackTraceElement line : ex.getStackTrace()) {
                                    stacktrace.append(line.toString());
                                    stacktrace.append("\n");
                                }
                                Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                                                "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});
                                //Logger.getLogger(this.getClass().getName()).log(Level.INFO, "", posts.getPost().size());
                                throw new ExtractException(ZMessages.GSON_CONVERTION_ERROR);
                            }
                        } else {
                            throw new ExtractException(ZMessages.ZSERVLETS_CONN_ERROR);
                        }
                    } catch (MalformedURLException ex) {
                        throw new ExtractException(ZMessages.ZSOURCES_GETURL_ERROR);
                    } catch (IOException ex) {
                        throw new ExtractException(ZMessages.ZSOURCES_GETURL_ERROR);
                    }
                } else {
                    throw new ExtractException(ZMessages.ZSOURCES_GETURL_ERROR);
                }
            } else {
                throw new ExtractException(ZMessages.ZSOURCES_CONN_ERROR);
            }
        } else {
            throw new ExtractException(ZMessages.ZSOURCES_CONN_ERROR);
        }

        return posts;
    }


}
