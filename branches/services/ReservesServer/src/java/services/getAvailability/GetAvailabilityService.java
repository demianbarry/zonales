/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getAvailability;

import services.*;
import com.google.gson.Gson;
import entities.BaseEntity;
import entities.JosReserveHasJosResources;
import entities.JosReserveHasJosResourcesPK;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Time;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ReserveModel;
import model.ResourceGroupModel;
import model.ResourcesModel;

/**
 *
 * @author Nosotros
 */
public class GetAvailabilityService {

    ResourceGroupModel resourceGroupModel = new ResourceGroupModel(false);
    ResourcesModel resourcesModel = new ResourcesModel(false);
    ReserveModel reserveModel = new ReserveModel(false);
    GetAvailabilityStruct retorno;
    ResourceGroupInDate resourceGroupInDate;
    Gson gson = new Gson();

    public void serve(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        PrintWriter out = response.getWriter();
        response.setContentType("text/html");
        String group = request.getParameter("resourceGroup");
        int idGroup = Integer.valueOf(group);
        Calendar date = Calendar.getInstance();

        String dateYear = request.getParameter("dateYear");
        String dateMonth = request.getParameter("dateMonth");
        String dateDay = request.getParameter("dateDay");

        String sHour = request.getParameter("hour");
        Date hourd = Time.valueOf(sHour);
        Calendar hour = Calendar.getInstance();
        hour.setTime(hourd);

        date.set(Integer.valueOf(dateYear),
                 Integer.valueOf(dateMonth) - 1,
                 Integer.valueOf(dateDay),
                 hour.get(Calendar.HOUR_OF_DAY),
                 hour.get(Calendar.MINUTE),
                 hour.get(Calendar.SECOND)
                 );


        String sDuration = request.getParameter("duration");
        Date durationd = Time.valueOf(sDuration);
        Calendar duration = Calendar.getInstance();
        duration.setTime(durationd);
        
        
        resourceGroupModel.setSelected(idGroup);
        List<BaseEntity> resourcesList = resourceGroupModel.getResources();
        
        resourceGroupInDate = new ResourceGroupInDate(idGroup, date, hourd, duration);
        retorno = new GetAvailabilityStruct(resourceGroupInDate);

        for (int i = 0; i < resourcesList.size(); i++) {
            int resourceId = (Integer)resourcesList.get(i).getPK();
            resourcesModel.setSelected(resourceId);

            List<BaseEntity> reservesList = resourcesModel.getReserves(date);

            for (int j = 0; j < reservesList.size(); j++) {
                reserveModel.setSelected(  ((JosReserveHasJosResourcesPK)((JosReserveHasJosResources)reservesList.get(0)).getPK()).getReserveId() );
                Date raaux = reserveModel.getSelected().getDatetimeRealization();
                Calendar ra = Calendar.getInstance();
                ra.setTime(raaux);
                Date daaux = reserveModel.getSelected().getDuration();
                Calendar da = Calendar.getInstance();
                da.setTime(daaux);
                Boolean available = Boolean.FALSE;

                available = Helper.checkReserve(ra, da, date, duration);

                if (!available) {
                    ResourceIdAndName resource = new ResourceIdAndName(
                            resourcesModel.getSelected().getResourceId(),
                            resourcesModel.getSelected().getName()
                    );
                    retorno.add(resource);
                }

            }

        }

        out.print(gson.toJson(retorno));

    }

}

