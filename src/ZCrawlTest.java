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
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;
import org.jsoup.Connection.Response;
import org.jsoup.helper.HttpConnection;

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
        PrintWriter out = response.getWriter();
        Response resp = null;
        InputStreamReader is = null;

        try {
            String query = request.getParameter("q");
            String testUrl = "";
            String metadata = "";

            String url = "http://localhost:38080/ZCrawlParserServlet/servlet/ZCrawlParser?q=" + query;//args[0];
            //URL urll = new URL("http://localhost:38080/ZCrawlParserServlet/servlet/ZCrawlParser?q=" + query);
            //urll.openConnection().
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Ejecutando llamada al ZParser con la siguiente consulta: {0}", query);

            URL url2 = new URL(url.replace(" ", "+"));
            HttpURLConnection connection = (HttpURLConnection) url2.openConnection();
            connection.setRequestMethod("GET");
            connection.connect();

            int code = connection.getResponseCode();

            //resp = HttpConnection.connect(url.replace(" ", "+")).execute();
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Código de respuesta: {0}", code);
            
            if (code == 200) {
                is = new InputStreamReader(connection.getInputStream());
                while (is.ready()) {
                    metadata += Character.toString((char) is.read());
                }

                //metadata = resp.body();
                url = "http://localhost:38080/ZCrawl/zcc?action=getTestService&q=" + metadata;
                Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Buscando URL de recuperación para la siguiente metadata: {0}", metadata);
                resp = HttpConnection.connect(url.replace(" ", "+")).execute();
                if (resp.statusCode() == 200) {
                    testUrl = resp.body();
                    Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Solicitando la siguiente URL de recuperación: {0}", testUrl);
                    resp = HttpConnection.connect(testUrl.replace(" ", "+")).timeout(30000).execute();
                    if (resp.statusCode() == 200) {
                        out = response.getWriter();
                        out.print(resp.body());
                    } else {
                        Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la llamada a la URL: {1}", new Object[]{resp.statusCode(), testUrl});
                        response.sendError(resp.statusCode(), resp.statusMessage());
                    }
                } else {
                    Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la construcci\u00f3n de la URL con la metadata: {1}", new Object[]{resp.statusCode(), metadata});
                    response.sendError(resp.statusCode(), resp.statusMessage());
                }
            } else {
                //Logger.getLogger(ZCrawlTest.class.getName()).log(Level.INFO, "Fall\u00f3 con el c\u00f3digo {0} la llamada al ZParser con la consulta: {1}", new Object[]{code, query});
                String error = "";
                is = new InputStreamReader(connection.getErrorStream());
                while (is.ready()) {
                    error += Character.toString((char) is.read());
                }
                //response.setStatus(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
                response.sendError(500, error);
            }
        } catch (IOException ex) {
            Logger.getLogger(ZCrawlTest.class.getName()).log(Level.SEVERE, "Excepcion grave: {0}", ex.getMessage());
        }
    }

    @Override
    public void doPost(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }
}
