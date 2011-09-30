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


    protected List<String> palabras;

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
    protected List<String> deLosUsuarios;

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
        
    protected List<Double> deLosUsuariosLatitudes;

    /**
     * Get the value of deLosUsuariosLatitudes
     *
     * @return the value of deLosUsuariosLatitudes
     */
    public List<Double> getDeLosUsuariosLatitudes() {
        return deLosUsuariosLatitudes;
    }

    /**
     * Set the value of deLosUsuariosLatitudes
     *
     * @param deLosUsuariosLatitudes new value of deLosUsuariosLatitudes
     */
    public void setDeLosUsuariosLatitudes(List<Double> deLosUsuariosLatitudes) {
        this.deLosUsuariosLatitudes = deLosUsuariosLatitudes;
    }

    protected List<Double> deLosUsuariosLongitudes;

    /**
     * Get the value of deLosUsuariosLongitudes
     *
     * @return the value of deLosUsuariosLongitudes
     */
    public List<Double> getDeLosUsuariosLongitudes() {
        return deLosUsuariosLongitudes;
    }

    /**
     * Set the value of deLosUsuariosLongitudes
     *
     * @param deLosUsuariosLongitudes new value of deLosUsuariosLongitudes
     */
    public void setDeLosUsuariosLongitudes(List<Double> deLosUsuariosLongitudes) {
        this.deLosUsuariosLongitudes = deLosUsuariosLongitudes;
    }

    
    protected String amigosDe;

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
    protected Boolean deTodo;

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
        return "Criterio{" + "siosi=" + siosi + " - palabras=" + palabras + " - deLosUsuarios=" + deLosUsuarios + " - deLosUsuariosLatitudes=" + deLosUsuariosLatitudes + " - deLosUsuariosLongitudes=" + deLosUsuariosLongitudes + " - amigosDe=" + amigosDe + " - deTodo=" + deTodo + '}';
    }



}
