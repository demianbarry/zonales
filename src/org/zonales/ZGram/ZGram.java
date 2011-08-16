/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram;

import java.util.Date;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessage;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class ZGram {
    private ZMessage zmessage;
    private ZCrawling metadata;
    private String verbatim;
    private String estado;
    private Date creado;
    private Date modificado;

    public ZGram() {
    }

    public ZGram(ZMessage zmessage, ZCrawling metadata, String verbatim, String estado) {
        this.zmessage = zmessage;
        this.metadata = metadata;
        this.verbatim = verbatim;
        this.estado = estado;
    }

    public ZMessage getZmessage() {
        return zmessage;
    }

    public void setZmessage(ZMessage zmessage) {
        this.zmessage = zmessage;
    }

    public ZCrawling getMetadata() {
        return metadata;
    }

    public void setMetadata(ZCrawling metadata) {
        this.metadata = metadata;
    }

    public String getVerbatim() {
        return verbatim;
    }

    public void setVerbatim(String verbatim) {
        this.verbatim = verbatim;
    }

    public Date getCreado() {
        return creado;
    }

    public void setCreado(Date creado) {
        this.creado = creado;
    }

    public String getEstado() {
        return estado;
    }

    public void setEstado(String estado) {
        this.estado = estado;
    }

    public Date getModificado() {
        return modificado;
    }

    public void setModificado(Date modificado) {
        this.modificado = modificado;
    }

}
