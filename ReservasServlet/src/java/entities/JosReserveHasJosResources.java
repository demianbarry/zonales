/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_reserve_has_jos_resources")
@NamedQueries({
    @NamedQuery(name = "JosReserveHasJosResources.findAll", query = "SELECT j FROM JosReserveHasJosResources j"),
    @NamedQuery(name = "JosReserveHasJosResources.findByResourceIdInDate", query = "SELECT j FROM JosReserveHasJosResources j WHERE j.resources = :resources AND j.reserves.datetimeRealization > :from AND j.reserves.datetimeRealization < :to"),
    @NamedQuery(name = "JosReserveHasJosResources.findByResourceId", query = "SELECT j FROM JosReserveHasJosResources j WHERE j.resources = :resources")
})
public class JosReserveHasJosResources extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @EmbeddedId
    protected JosReserveHasJosResourcesPK josReserveHasJosResourcesPK;
    @JoinColumn(name = "resource_id", referencedColumnName = "resource_id", insertable = false, updatable = false)
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosResources resources;
    @JoinColumn(name = "reserve_id", referencedColumnName = "reserve_id", insertable = false, updatable = false)
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosReserve reserves;

    public JosReserveHasJosResources() {
    }

    public JosReserveHasJosResources(JosReserveHasJosResourcesPK josReserveHasJosResourcesPK) {
        this.josReserveHasJosResourcesPK = josReserveHasJosResourcesPK;
    }

    public JosReserveHasJosResources(int reserveId, int resourceId) {
        this.josReserveHasJosResourcesPK = new JosReserveHasJosResourcesPK(reserveId, resourceId);
    }

    public JosReserveHasJosResourcesPK getJosReserveHasJosResourcesPK() {
        return josReserveHasJosResourcesPK;
    }

    public void setJosReserveHasJosResourcesPK(JosReserveHasJosResourcesPK josReserveHasJosResourcesPK) {
        this.josReserveHasJosResourcesPK = josReserveHasJosResourcesPK;
    }

    public JosReserve getReserves() {
        return reserves;
    }

    public void setReserves(JosReserve reserves) {
        this.reserves = reserves;
    }

    public JosResources getResources() {
        return resources;
    }

    public void setResources(JosResources resources) {
        this.resources = resources;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (josReserveHasJosResourcesPK != null ? josReserveHasJosResourcesPK.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosReserveHasJosResources)) {
            return false;
        }
        JosReserveHasJosResources other = (JosReserveHasJosResources) object;
        if ((this.josReserveHasJosResourcesPK == null && other.josReserveHasJosResourcesPK != null) || (this.josReserveHasJosResourcesPK != null && !this.josReserveHasJosResourcesPK.equals(other.josReserveHasJosResourcesPK))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosReserveHasJosResources[josReserveHasJosResourcesPK=" + josReserveHasJosResourcesPK + "]";
    }

    @Override
    public Object getPK() {
        return getJosReserveHasJosResourcesPK();
    }

    public int compareTo(BaseEntity o) {
        return getJosReserveHasJosResourcesPK().getResourceId()
                - ((JosReserveHasJosResources)o).getJosReserveHasJosResourcesPK().getResourceId();
    }



}
