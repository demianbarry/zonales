/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getReserve;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Time;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.ReserveModel;
import model.ReservesHasResourcesModel;
import model.ResourceGroupModel;
import model.ResourcesModel;

/**
 *
 * @author Nosotros
 */
public class GetReserveService {

    public void serve(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        ResourcesModel resourcesModel = new ResourcesModel(false);
        ReservesHasResourcesModel reservesHasResourcesModel = new ReservesHasResourcesModel(false);
        ReserveModel reserveModel = new ReserveModel(false);
        ResourceGroupModel resourceGroupModel = new ResourceGroupModel(false);

        Data data;
        ResourceIdNameDescription resource;

        String sResponse = "";
        PrintWriter out = response.getWriter();

        String sReserveId = request.getParameter("reserve_id");
        String sUser = request.getParameter("user");

        int reserveId = Integer.valueOf(sReserveId);

        reserveModel.setSelected(reserveId);
        data = new Data(reserveId,
                        reserveModel.getSelected().getDatetimeReserve(),
                        reserveModel.getSelected().getDatetimeRealization(),
                        (Time) reserveModel.getSelected().getDuration(),
                        reserveModel.getSelected().getExpiry());

        

    }

}
