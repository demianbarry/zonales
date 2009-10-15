/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import entities.BaseEntity;
import entities.JosReserve;
import entities.JosReserveHasJosResources;
import entities.JosReserveHasJosResourcesPK;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Time;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.StringTokenizer;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ReserveModel;
import model.ReservesHasResourcesModel;
import model.ResourcesModel;

/**
 *
 * @author Nosotros
 */
public class ConfirmReservationService {

    public void serve(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        ResourcesModel resourcesModel = new ResourcesModel();
        ReservesHasResourcesModel reservesHasResourcesModel = new ReservesHasResourcesModel();
        ReserveModel reserveModel = new ReserveModel();

        String sResponse = "";
        PrintWriter out = response.getWriter();

        String sResources = request.getParameter("resources");
        String sDateYear = request.getParameter("dateYear");
        String sDateMonth = request.getParameter("dateMonth");
        String sDateDay = request.getParameter("dateDay");
        String sHour = request.getParameter("hour");
        String sDuration = request.getParameter("duration");
        String sUser = request.getParameter("user");

        List<Integer> resources = new ArrayList<Integer>();
        StringTokenizer tokenizer = new StringTokenizer(sResources, ",");
        while (tokenizer.hasMoreElements()) {
            resources.add(Integer.valueOf(tokenizer.nextElement().toString()));
        }

        Calendar date = Calendar.getInstance();
        date.set(Integer.valueOf(sDateYear),
                 Integer.valueOf(sDateMonth),
                 Integer.valueOf(sDateDay), 0, 0, 0);

        Calendar hour = Calendar.getInstance();
        hour.setTime(Time.valueOf(sHour));

        Calendar duration = Calendar.getInstance();
        hour.setTime(Time.valueOf(sDuration));

        Integer userId = Integer.valueOf(sUser);

        for (int i = 0; i < resources.size(); i++) {
            resourcesModel.setSelected(resources.get(i));
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
                    sResponse = sResponse + "Error: ResourceId " + resources.get(i) + " no disponible; ";
                }
            }
        }

        if ("".compareTo(sResponse) == 0) {
            JosReserve josReserve = new JosReserve(null, userId, null, null, null, null)
            reserveModel.setSelected(null)
            reservesHasResourcesModel.setSelected(new JosReserveHasJosResources());

        } else {
            out.print(sResponse);
        }

    }

}
