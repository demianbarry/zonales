/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
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

    public Posts extract(String metadata, String zCralwSourcesURL, int timeout) throws MalformedURLException, IOException, ExtractException {
        Posts posts = new Posts();

        HttpURLConnection connection;
        Gson postsGson = new Gson();

        //Obtengo la URL para realizar la extracción
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo URL para la metadata: {0}", metadata);
        connection = ConnHelper.getURLConnection(zCralwSourcesURL + "getServiceURL?q=" + metadata, timeout);
        int code = connection.getResponseCode();
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
                //Realizo la extracción
                connection = ConnHelper.getURLConnection(urlService.getUrl(), timeout);
                code = connection.getResponseCode();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Código de respuesta: {0}", code);
                if (code == 200) {
                    String postsJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Respuesta {0}s", postsJson);
                    connection.disconnect();
                    try {
                        posts = postsGson.fromJson(postsJson, Posts.class);
                        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción - Se obtuvieron {0} posts", posts.getPost().size());
                    } catch (Exception e) {
                        throw new ExtractException(ZMessages.GSON_CONVERTION_ERROR);
                    }
                } else {
                    throw new ExtractException(ZMessages.ZSERVLETS_CONN_ERROR);
                }
            } else {
                throw new ExtractException(ZMessages.ZSOURCES_GETURL_ERROR);
            }
        } else {
            throw new ExtractException(ZMessages.ZSOURCES_CONN_ERROR);
        }

        return posts;
    }


}
