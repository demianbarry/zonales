package pruebas;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;
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

    Resto resto = new Resto(8);
    Teatro teatro = new Teatro(18, 12);
    String[] restoOptions = {"Desayuno", "Almuerzo", "Merienda", "Cena"};
    String[] teatroOptions = {"20:30hs", "22:30hs"};

    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        PrintWriter out = response.getWriter();
        response.setContentType("text/html");

        if (request.getParameterMap().isEmpty()) {
            out.println("Hola: soy tu servlet favorito!!! Ingresa algun parámetro, gil");
        } else {

            String accion = request.getParameter("accion");
            String locacion = request.getParameter("locacion");

            if (accion.compareTo("consulta") == 0) {

                if (locacion.compareTo("resto") == 0) {
                    int nroMesa = Integer.valueOf(request.getParameter("nroMesa"));
                    List<Integer> libres = resto.getLibres(nroMesa);
                    for (int i = 0; i < libres.size(); i++) {
                        if (libres.get(i) == 0)
                            out.print(restoOptions[i] + ";");
                    }
                }

                if (locacion.compareTo("teatro") == 0) {
                    int fila = Integer.valueOf(request.getParameter("fila"));
                    int columna = Integer.valueOf(request.getParameter("columna"));
                    Integer[] libres = teatro.getLibres(fila, columna);
                    for (int i = 0; i < libres.length; i++) {
                        if (libres[i] == 0)
                            out.print(teatroOptions[i] + ";");
                    }
                }

            } else {
            if (accion.compareTo("confirma") == 0) {

                if (locacion.compareTo("resto") == 0) {
                    int nroMesa = Integer.valueOf(request.getParameter("nroMesa"));
                    String horario = request.getParameter("horario");
                    List<Integer> libres = resto.getLibres(nroMesa);

                    for (int i = 0; i < restoOptions.length; i++) {
                        if (horario.compareTo(restoOptions[i]) == 0) {
                            libres.set(i,1);
                        }
                    }
                    System.out.println("nromesa: " + nroMesa);
                    System.out.println("libres: " + libres);
                    resto.setLibres(nroMesa, libres);
                    resto.mostrar();
                    out.println("Reserva Confirmada");
                }

                if (locacion.compareTo("teatro") == 0) {
                    int fila = Integer.valueOf(request.getParameter("fila"));
                    int columna = Integer.valueOf(request.getParameter("columna"));
                    String horario = request.getParameter("horario");
                    Integer[] libres = teatro.getLibres(fila, columna);
                    for (int i = 0; i < teatroOptions.length; i++) {
                        if (horario.compareTo(teatroOptions[i]) == 0) {
                            libres[i] = 1;
                        }
                    }
                    teatro.setLibres(fila, columna, libres);
                    out.println("Reserva Confirmada");
                }

            } else {
                out.println("Parámetros incorrectos");
            }
            }
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
