/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import com.google.gson.Gson;
import entities.BaseEntity;
import entities.JosSlotsHasJosResourcesGroupPK;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Calendar;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ResourceGroupModel;
import model.SlotsModel;

/**
 *
 * @author Nosotros
 */
public class GetConfigService {

    ResourceGroupModel resourceGroupModel = new ResourceGroupModel();
    SlotsModel slotsModel = new SlotsModel();
    GetConfigStruct retorno;
    Range range;
    Gson gson = new Gson();

    public void serve(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        PrintWriter out = response.getWriter();
        response.setContentType("text/html");
        String group = request.getParameter("resourceGroup");
        int idGroup = Integer.valueOf(group);
        Calendar from = Calendar.getInstance();
        Calendar to = Calendar.getInstance();

        String fromYear = request.getParameter("fromYear");
        String fromMonth = request.getParameter("fromMonth");
        String fromDate = request.getParameter("fromDate");
        String toYear = request.getParameter("toYear");
        String toMonth = request.getParameter("toMonth");
        String toDate = request.getParameter("toDate");

        from.set(Integer.valueOf(fromYear),
                 Integer.valueOf(fromMonth) - 1,
                 Integer.valueOf(fromDate)
                 );

        to.set(Integer.valueOf(toYear),
                 Integer.valueOf(toMonth) - 1,
                 Integer.valueOf(toDate)
                 );

        resourceGroupModel.setSelected(idGroup);
        List<BaseEntity> slotsList = resourceGroupModel.getSlots(from.getTime(), to.getTime());
        range = new Range(idGroup, from, to);
        retorno = new GetConfigStruct(range);

        for (int i = 0; i < slotsList.size(); i++) {
            Object slote = slotsList.get(i).getPK();
            int slotId = ((JosSlotsHasJosResourcesGroupPK)slote).getSlotId();
            slotsModel.setSelected(slotId);
            Slots slot = new Slots(
                    slotsModel.getSelected().getSlotId(),
                    slotsModel.getSelected().getDay(),
                    slotsModel.getSelected().getHourFrom(),
                    slotsModel.getSelected().getHourTo(),
                    slotsModel.getSelected().getMinDuration(),
                    slotsModel.getSelected().getMaxDuration(),
                    slotsModel.getSelected().getSteep(),
                    slotsModel.getSelected().getTolerance()
            );
            retorno.add(slot);
        }

        out.print(gson.toJson(retorno));
        
    }

}
