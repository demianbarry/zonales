/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram;

import java.util.ArrayList;
import org.zonales.crawlConfig.objets.State;

/**
 *
 * @author nacho
 */
public class ZGramFilter {
    private String localidad;
    private String fuente;
    private String periodo;
    private ArrayList<String> tags;
    private String estado;

    public ZGramFilter() {
    }

    public String getEstado() {
        return estado;
    }

    public void setEstado(String estado) {
        ArrayList<String> possiblesStates = State.getCrawlConfigStates();

        if (possiblesStates.indexOf(estado) < 0) {
            throw new TypeNotPresentException(estado, null);
        }

        this.estado = estado;
    }

    public String getFuente() {
        return fuente;
    }

    public void setFuente(String fuente) {
        this.fuente = fuente;
    }

    public String getLocalidad() {
        return localidad;
    }

    public void setLocalidad(String localidad) {
        this.localidad = localidad;
    }

    public String getPeriodo() {
        return periodo;
    }

    public void setPeriodo(String periodo) {
        ArrayList<String> possiblesPeriodos = Periodo.getPeriodos();

        if (possiblesPeriodos.indexOf(periodo) < 0) {
            throw new TypeNotPresentException(periodo, null);
        }

        this.periodo = periodo;
    }

    public ArrayList<String> getTags() {
        return tags;
    }

    public void setTags(ArrayList<String> tags) {
        this.tags = tags;
    }
    
}
