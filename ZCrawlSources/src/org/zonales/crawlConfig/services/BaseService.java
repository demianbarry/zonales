/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author nacho
 */
public abstract class BaseService extends HttpServlet {

    InputStream stream;
    static Properties props = null;

    public BaseService() {
    }

    public abstract void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception;

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
        PrintWriter out = response.getWriter();
        stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
        if (props == null) {
            props = new Properties();
            props.load(stream);
        }

        try {
            serve(request, response, props);
        } catch (Exception ex) {
            out.print("Request Error: " + ex.getMessage());
            //response.sendError(javax.servlet.http.HttpServletResponse.SC_INTERNAL_SERVER_ERROR, ex.getMessage());
        }

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
        PrintWriter out = response.getWriter();
        stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
        props = new Properties();
        props.load(stream);

        try {
            serve(request, response, props);
        } catch (Exception ex) {
            out.print("Request Error: " + ex.getMessage());
            //response.sendError(javax.servlet.http.HttpServletResponse.SC_INTERNAL_SERVER_ERROR, ex.getMessage());
        }

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
