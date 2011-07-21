package org.zonales.crawlConfig;

import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.services.GetConfig;
import org.zonales.crawlConfig.services.SetConfig;

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
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        //PrintWriter out = response.getWriter();
        //response.setContentType("text/html")

        String service = request.getParameter("action");

        InputStream stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
        Properties props = new Properties();
        props.load(stream);


        if ("setConfig".equals(service)) {
            SetConfig setConfig = new SetConfig();
            setConfig.serve(request, response, props);
        }

        if ("getConfig".equals(service)) {
            GetConfig getConfig = new GetConfig();
            getConfig.serve(request, response, props);
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
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
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
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        processRequest(request, response);

    }

    /**
     * Returns a short description of the servlet.
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>
}
