package services;

import java.io.IOException;
import java.util.Enumeration;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Comienza el proceso de autentificacion
 *
 * Una vez que el usuario ha elegido el proveedor, este controlador
 * se encargará de realizar la conexión con el mismo y enviará al usuario
 * al sitio del proveedor para que se autentifique
 *
 * @author Cristian Pacheco
 */
public class Main extends HttpServlet {

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code> methods.
     * @locacion request servlet request
     * @locacion response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */

    /*Resto resto = new Resto(8);
    Teatro teatro = new Teatro(18, 12);
    String[] restoOptions = {"Desayuno", "Almuerzo", "Merienda", "Cena"};
    String[] teatroOptions = {"20:30hs", "22:30hs"};*/
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        //PrintWriter out = response.getWriter();
        //response.setContentType("text/html")

        String service = request.getParameter("name");

        if ("getConfig".equals(service)) {
            GetConfigService getConfig = new GetConfigService();
            getConfig.serve(request, response);
        }

        if ("getLayout".equals(service)) {
            GetLayoutService getLayout = new GetLayoutService();
            getLayout.serve(request, response);
        }

        if ("getAvailability".equals(service)) {
            GetAvailabilityService getAvailability = new GetAvailabilityService();
            getAvailability.serve(request, response);
        }

        if ("confirmReserve".equals(service)) {
            ConfirmReserveService confirmReserve = new ConfirmReserveService();
            confirmReserve.serve(request, response);
        }

        if ("getReserve".equals(service)) {
            GetReserveService getReserve = new GetReserveService();
            getReserve.serve(request, response);
        }

        }

// <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
/**
 * Handles the HTTP <code>GET</code> method.
 * @locacion request servlet request
 * @locacion response servlet response
 * @throws ServletException if a servlet-specific error occurs
 * @throws IOException if an I/O error occurs
 */
@Override
    protected void

doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        processRequest(request, response);

    }

/**
 * Handles the HTTP <code>POST</code> method.
 * @locacion request servlet request
 * @locacion response servlet response
 * @throws ServletException if a servlet-specific error occurs
 * @throws IOException if an I/O error occurs
 */
@Override
    protected void

doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        processRequest(request, response);

    }

/**
 * Returns a short description of the servlet.
 * @return a String containing servlet description
 */
@Override
    public String getServletInfo(

) {
        return "Short description";
    }// </editor-fold>
}
