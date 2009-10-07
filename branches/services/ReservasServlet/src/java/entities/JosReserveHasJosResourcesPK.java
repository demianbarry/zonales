/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 *
 * @author Nosotros
 */
@Embeddable
public class JosReserveHasJosResourcesPK implements Serializable {
    @Basic(optional = false)
    @Column(name = "reserve_id")
    private int reserveId;
    @Basic(optional = false)
    @Column(name = "resource_id")
    private int resourceId;

    public JosReserveHasJosResourcesPK() {
    }

    public JosReserveHasJosResourcesPK(int reserveId, int resourceId) {
        this.reserveId = reserveId;
        this.resourceId = resourceId;
    }

    public int getReserveId() {
        return reserveId;
    }

    public void setReserveId(int reserveId) {
        this.reserveId = reserveId;
    }

    public int getResourceId() {
        return resourceId;
    }

    public void setResourceId(int resourceId) {
        this.resourceId = resourceId;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (int) reserveId;
        hash += (int) resourceId;
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosReserveHasJosResourcesPK)) {
            return false;
        }
        JosReserveHasJosResourcesPK other = (JosReserveHasJosResourcesPK) object;
        if (this.reserveId != other.reserveId) {
            return false;
        }
        if (this.resourceId != other.resourceId) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosReserveHasJosResourcesPK[reserveId=" + reserveId + ", resourceId=" + resourceId + "]";
    }

}
