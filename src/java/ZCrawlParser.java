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

import com.google.gson.Gson;
import java.io.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;
import org.zonales.errors.ZMessages;
import org.zonales.metadata.ZCrawling;
import parser.Parser;
import parser.ParserException;

/**
 * Example servlet showing request headers
 *
 * @author James Duncan Davidson <duncan@eng.sun.com>
 */
public class ZCrawlParser extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();

        ZCrawling zcrawling = new ZCrawling();
        String extraction = request.getParameter("q");
        try {
            String[] args = {"-visitor", "parser.ZMetaDisplayer", "-string", extraction};
            Parser.main(zcrawling, out, args);
            out.print("{\"cod\": \"" + ZMessages.SUCCESS.getCod() + "\", \"msg\": \"" + ZMessages.SUCCESS.getMsg() + "\", meta: \"" + (new Gson()).toJson(zcrawling).replace("\\\"", "").replace("\"", "\\\"") + "\"}");
        } catch (ParserException ex) {

            if (!"".equals(ex.getZcrawling().getLocalidad()) && !"".equals(ex.getZcrawling().getFuente()) && ex.getZcrawling().getTags() != null && !ex.getZcrawling().getTags().isEmpty()) {
                out.print(ZMessages.appendMessage(ZMessages.ZPARSER_ZGRAM_PARTIALLY_PARSED, " - Error al parsear la configuración de extracción (el * marca el comienzo del error): " + ex.getMessage().replace("\\\"", "").replace("\"", "\\\"").replace("\n", "\\n") + "\", \"meta\": \"" + (new Gson()).toJson(zcrawling).replace("\\\"", "").replace("\"", "\\\"")));
            } else {
                out.print(ZMessages.appendMessage(ZMessages.ZPARSER_CANNOT_PARSE, " - Error al parsear la configuración de extracción (el * marca el comienzo del error): " + ex.getMessage().replace("'", "\\'").replace("\"", "\\\"").replace("\n", "\\n")));
            }
        } catch (Exception ex) {
            Logger.getLogger(ZCrawlParser.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public void doPost(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }
}
