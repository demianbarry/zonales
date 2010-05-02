package analyzer;

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
import java.util.HashMap;
import java.util.Set;
import java.util.TreeMap;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;

/**
 * Servlet que captura los requerimientos al motor y realiza las tareas necesarias.
 *
 * @author Juan Manuel Cortez <juanmanuelcortez@gmail.com>
 */
public class TagsAnalizer extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        try {
            // Obtengo la lista de tags, separados con coma y con su respectivo id
            String tags = request.getParameter("tags");
            // Obtengo la lista de fields a analizar, separados por coma
            String field = request.getParameter("fields");
            // Obtengo el id del contenido a analizar
            int docId = Integer.parseInt(request.getParameter("id"));
            // Obtengo el tipo de contenido a analizar
            String contentType = request.getParameter("type");

            // Recupero el mapa de frecuencias ponderadas de los términos
            RankingTermsFrequencyConstructor s = new RankingTermsFrequencyConstructor();
            TreeMap<String, Integer> termsMap = s.getTermFreq(field.split(","), docId);

            // lematizo los tags recibidos como parámetro
            HashMap<String, Set<Integer>> steemedTags = TagsFilter.getSteemedTags(tags.split(","));

            response.setContentType("text/html");
            PrintWriter out = response.getWriter();

            // por cada tag lematizado
            boolean first = true;
            for (String tag : steemedTags.keySet()) {
                // si el tag lematizado está en el mapa de frecuencias ponderadas y su frecuencia es igual o mayor a 3 (arbitrario por ahora)
                if (termsMap.get(tag) != null && termsMap.get(tag) >= 2) {
                    // por cada id de tag
                    for (Integer id : steemedTags.get(tag)) {
                        if (!first) {
                            out.print(",");
                        } else {
                            first = false;
                        }
                        // imprimo el id
                        out.print(id);
                    }
                }
            }
            out.close();

        } catch (Throwable ex) {
            Logger.getLogger(TagsAnalizer.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }
}
