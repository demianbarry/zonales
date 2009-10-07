/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import java.util.Date;
import java.util.Set;
import javax.persistence.Basic;
import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_reserve")
@NamedQueries({@NamedQuery(name = "JosReserve.findAll", query = "SELECT j FROM JosReserve j")})
public class JosReserve extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "reserve_id")
    private Integer reserveId;
    @Basic(optional = false)
    @Column(name = "jos_users_id")
    private int josUsersId;
    @Basic(optional = false)
    @Column(name = "datetime_reserve")
    @Temporal(TemporalType.TIMESTAMP)
    private Date datetimeReserve;
    @Basic(optional = false)
    @Column(name = "datetime_realization")
    @Temporal(TemporalType.TIMESTAMP)
    private Date datetimeRealization;
    @Basic(optional = false)
    @Column(name = "duration")
    @Temporal(TemporalType.TIMESTAMP)
    private Date duration;
    @Basic(optional = false)
    @Column(name = "expiry")
    @Temporal(TemporalType.TIMESTAMP)
    private Date expiry;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "reserveId", fetch = FetchType.LAZY)
    private Set<JosReserveHasJosResources> josReserveHasJosResourcesCollection;

    public JosReserve() {
    }

    public JosReserve(Integer reserveId) {
        this.reserveId = reserveId;
    }

    public JosReserve(Integer reserveId, int josUsersId, Date datetimeReserve, Date datetimeRealization, Date duration, Date expiry) {
        this.reserveId = reserveId;
        this.josUsersId = josUsersId;
        this.datetimeReserve = datetimeReserve;
        this.datetimeRealization = datetimeRealization;
        this.duration = duration;
        this.expiry = expiry;
    }

    public Integer getReserveId() {
        return reserveId;
    }

    public void setReserveId(Integer reserveId) {
        this.reserveId = reserveId;
    }

    public int getJosUsersId() {
        return josUsersId;
    }

    public void setJosUsersId(int josUsersId) {
        this.josUsersId = josUsersId;
    }

    public Date getDatetimeReserve() {
        return datetimeReserve;
    }

    public void setDatetimeReserve(Date datetimeReserve) {
        this.datetimeReserve = datetimeReserve;
    }

    public Date getDatetimeRealization() {
        return datetimeRealization;
    }

    public void setDatetimeRealization(Date datetimeRealization) {
        this.datetimeRealization = datetimeRealization;
    }

    public Date getDuration() {
        return duration;
    }

    public void setDuration(Date duration) {
        this.duration = duration;
    }

    public Date getExpiry() {
        return expiry;
    }

    public void setExpiry(Date expiry) {
        this.expiry = expiry;
    }

    public Set<JosReserveHasJosResources> getJosReserveHasJosResourcesCollection() {
        return josReserveHasJosResourcesCollection;
    }

    public void setJosReserveHasJosResourcesCollection(Set<JosReserveHasJosResources> josReserveHasJosResourcesCollection) {
        this.josReserveHasJosResourcesCollection = josReserveHasJosResourcesCollection;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (reserveId != null ? reserveId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosReserve)) {
            return false;
        }
        JosReserve other = (JosReserve) object;
        if ((this.reserveId == null && other.reserveId != null) || (this.reserveId != null && !this.reserveId.equals(other.reserveId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosReserve[reserveId=" + reserveId + "]";
    }

    @Override
    public Object getPK() {
        return getReserveId();
    }

    public int compareTo(BaseEntity o) {
        return getReserveId().compareTo(((JosReserve)o).getReserveId());
    }



}
