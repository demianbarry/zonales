/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import entities.BaseEntity;
import entities.JosSlots;
import java.util.Calendar;
import java.util.List;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ResourceGroupModel;

/**
 *
 * @author Nosotros
 */
public class GetConfigService {

    ResourceGroupModel resourceGroup = new ResourceGroupModel();

    public HttpServletResponse serve(HttpServletRequest request, HttpServletResponse response) {
        

        int idGroup = Integer.valueOf(request.getParameter("resourceGruop"));
        Calendar from = Calendar.getInstance();
        Calendar to = Calendar.getInstance();

        from.set(Integer.valueOf(request.getParameter("fromYear")),
                 Integer.valueOf(request.getParameter("fromMonth")),
                 Integer.valueOf(request.getParameter("fromDay"))
                 );

        to.set(Integer.valueOf(request.getParameter("toYear")),
                 Integer.valueOf(request.getParameter("toMonth")),
                 Integer.valueOf(request.getParameter("toDay"))
                 );

        resourceGroup.setSelected(idGroup);

        List<BaseEntity> slots = resourceGroup.getSlots(from.getTime(), to.getTime());
        

        return response;
    }

}
