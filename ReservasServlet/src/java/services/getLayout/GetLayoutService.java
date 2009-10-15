/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getLayout;

import entities.JosResourcesGroup;
import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ResourceGroupModel;

/**
 *
 * @author Nosotros
 */
public class GetLayoutService {

    ResourceGroupModel resourceGroupModel = new ResourceGroupModel();

    public void serve(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        PrintWriter out = response.getWriter();
        response.setContentType("text/html");
        String group = request.getParameter("resourceGroup");
        int idGroup = Integer.valueOf(group);

        resourceGroupModel.setSelected(idGroup);

        out.print(((JosResourcesGroup)resourceGroupModel.getSelected()).getVisualConfig());
    }

}
