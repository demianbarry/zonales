/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.services;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.objets.PluginType;

/**
 *
 * @author nacho
 */
public class GetPluginTypes  extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        ArrayList<String> pluginTypes = PluginType.getPluginTypes();
        Gson pluginTypesGson = new Gson();

        Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Obteniendo tipos de plugins");
        out.print(pluginTypesGson.toJson(pluginTypes));

    }

}
