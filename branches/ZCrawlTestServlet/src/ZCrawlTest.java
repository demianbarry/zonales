/*
 * Copyright 2004 The Apache Software Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/* $Id$
 *
 */

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;

/**
 * Example servlet showing request headers
 *
 * @author 
 */
public class ZCrawlTest extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {

        response.setCharacterEncoding("UTF-8");
        PrintWriter out = null;

        try {
            String query = request.getParameter("q");
            String testUrl = "";
            String metadata = "";
            String respuesta = "";
            String error = "";

            //String url = "http://localhost:38080/ZCrawlParserServlet/servlet/ZCrawlParser?q=" + query;//args[0];
            //URL urll = new URL("http://localhost:38080/ZCrawlParserServlet/servlet/ZCrawlParser?q=" + query);
            //urll.openConnection().
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Ejecutando llamada al ZParser con la siguiente consulta: {0}", query);

            InputStream stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
            Properties props = new Properties();
            props.load(stream);

            URL url = new URL(("http://localhost:38080/ZCrawlParserServlet/servlet/ZCrawlParser?q=" + query).replace(" ", "+"));
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setRequestMethod("GET");
            Integer timeout = Integer.valueOf(props.getProperty("timeout"));
            connection.setConnectTimeout(timeout);
            connection.setReadTimeout(timeout);
            connection.connect();

            int code = connection.getResponseCode();

            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Código de respuesta: {0}", code);

            if (code == 200) {
                metadata = getStringFromInpurStream(connection.getInputStream());
                connection.disconnect();
                Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Buscando URL de recuperación para la siguiente metadata: {0}", metadata);
                url = new URL(("http://localhost:38080/ZCrawlMetaServlet/zcc?action=getTestService&q=" + metadata).replace(" ", "+"));
                connection = (HttpURLConnection) url.openConnection();
                connection.setRequestMethod("GET");
                connection.setConnectTimeout(timeout);
                connection.setReadTimeout(timeout);
                connection.connect();
                code = connection.getResponseCode();

                if (code == 200) {
                    testUrl = getStringFromInpurStream(connection.getInputStream());
                    connection.disconnect();
                    url = new URL(testUrl.replace(" ", "+"));
                    connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("GET");                    
                    connection.setConnectTimeout(timeout);
                    connection.setReadTimeout(timeout);
                    
                    Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Solicitando con timeout {1} la siguiente URL de recuperación: {0}", new Object[]{url, connection.getReadTimeout()});
                    connection.connect();
                    code = connection.getResponseCode();
                    Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Código de respuesta: {0}", code);

                    if (code == 200) {
                        respuesta = getStringFromInpurStream(connection.getInputStream());
                        connection.disconnect();
                        Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Resultado de consulta: {0}", respuesta);
                        response.setContentType("text/javascript");
                        out = response.getWriter();
                        out.println(respuesta.replace("\"", "'"));
                    } else {
                        Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la llamada a la URL: {1}", new Object[]{code, testUrl});
                        error = getStringFromInpurStream(connection.getErrorStream());
                        connection.disconnect();
                        throw new Exception(error);
                    }
                } else {
                    Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la construcci\u00f3n de la URL con la metadata: {1}", new Object[]{code, metadata});
                    error = getStringFromInpurStream(connection.getErrorStream());
                    connection.disconnect();
                    throw new Exception(error);
                }
            } else {
                Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la llamada al ZParser con la consulta: {1}", new Object[]{code, query});
                error = getStringFromInpurStream(connection.getErrorStream());
                connection.disconnect();
                throw new Exception(error);
            }
        } catch (Exception ex) {
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.SEVERE, "Excepcion grave: {0}", ex.getMessage());

            response.sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR, ex.getMessage().indexOf("<u>") != -1 ? ex.getMessage().substring(ex.getMessage().indexOf("<u>") + 3, ex.getMessage().indexOf("</u>")) : ex.getMessage());
        }
    }

    @Override
    public void doPost(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }

    private String getStringFromInpurStream(InputStream is) {
        String resultado = "";
        int character = 0;
        
        try {            
            while ((character = is.read()) != -1) {
                resultado += Character.toString((char) character);
                System.out.print(resultado);
            }
        } catch (Exception ex) {
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.SEVERE, null, ex);
        }
        return resultado;
    }
}
