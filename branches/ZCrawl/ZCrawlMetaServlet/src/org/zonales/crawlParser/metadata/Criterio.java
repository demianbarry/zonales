/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlParser.metadata;

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
    protected String delUsuario;

    /**
     * Get the value of usuarios
     *
     * @return the value of usuarios
     */
    public String getDelUsuario() {
        return delUsuario;
    }

    /**
     * Set the value of usuarios
     *
     * @param usuarios new value of usuarios
     */
    public void setDelUsuario(String usuario) {
        this.delUsuario = usuario;
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
}
