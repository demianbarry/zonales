/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.metadata;

import java.util.List;

/**
 *
 * @author juanma
 */
public class Criterio {

    protected Boolean siosi;
    protected List<String> palabras;
    protected List<String> deLosUsuarios;
    /*protected List<Double> deLosUsuariosLatitudes;
    protected List<Double> deLosUsuariosLongitudes;*/
    protected List<String> deLosUsuariosPlaces;
    protected String amigosDe;
    protected Boolean deTodo;

    /**
     * Get the value of siosi
     *
     * @return the value of siosi
     */
    public Boolean getSiosi() {
        return siosi;
    }

    /**
     * Set the value of siosi
     *
     * @param siosi new value of siosi
     */
    public void setSiosi(Boolean siosi) {
        this.siosi = siosi;
    }

    /**
     * Get the value of palabras
     *
     * @return the value of palabras
     */
    public List<String> getPalabras() {
        return palabras;
    }

    /**
     * Set the value of palabras
     *
     * @param palabras new value of palabras
     */
    public void setPalabras(List<String> palabras) {
        this.palabras = palabras;
    }

    /**
     * Get the value of usuarios
     *
     * @return the value of usuarios
     */
    public List<String> getDeLosUsuarios() {
        return deLosUsuarios;
    }

    /**
     * Set the value of usuarios
     *
     * @param usuarios new value of usuarios
     */
    public void setDeLosUsuarios(List<String> usuarios) {
        this.deLosUsuarios = usuarios;
    }

    public List<String> getDeLosUsuariosPlaces() {
        return deLosUsuariosPlaces;
    }

    public void setDeLosUsuariosPlaces(List<String> deLosUsuariosPlaces) {
        this.deLosUsuariosPlaces = deLosUsuariosPlaces;
    }

    /**
     * Get the value of amigosDe
     *
     * @return the value of amigosDe
     */
    public String getAmigosDe() {
        return amigosDe;
    }

    /**
     * Set the value of amigosDe
     *
     * @param amigosDe new value of amigosDe
     */
    public void setAmigosDe(String amigosDe) {
        this.amigosDe = amigosDe;
    }

    /**
     * Get the value of deTodo
     *
     * @return the value of deTodo
     */
    public Boolean getDeTodo() {
        return deTodo;
    }

    /**
     * Set the value of deTodo
     *
     * @param amigosDe new value of deTodo
     */
    public void setDeTodo(Boolean deTodo) {
        this.deTodo = deTodo;
    }

    @Override
    public String toString() {
        return super.toString();
    }

    
}
