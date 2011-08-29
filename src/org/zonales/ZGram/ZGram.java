/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram;

import org.zonales.errors.ZMessage;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class ZGram extends ZCrawling {
    private ZMessage zmessage;    
    private String verbatim;
    private String estado;
    private Long creado;
    private Long modificado;

    public ZGram(ZMessage zmessage, String verbatim, String estado, Long creado, Long modificado) {
        this.zmessage = zmessage;
        this.verbatim = verbatim;
        this.estado = estado;
        this.creado = creado;
        this.modificado = modificado;
    }
    
    public ZGram() {
    }

    public ZMessage getZmessage() {
        return zmessage;
    }

    public void setZmessage(ZMessage zmessage) {
        this.zmessage = zmessage;
    }

    public String getVerbatim() {
        return verbatim;
    }

    public void setVerbatim(String verbatim) {
        this.verbatim = verbatim;
    }

    public Long getCreado() {
        return creado;
    }

    public void setCreado(Long creado) {
        this.creado = creado;
    }

    public String getEstado() {
        return estado;
    }

    public void setEstado(String estado) {
        this.estado = estado;
    }

    public Long getModificado() {
        return modificado;
    }

    public void setModificado(Long modificado) {
        this.modificado = modificado;
    }

}
