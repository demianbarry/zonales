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
 * @author Nos
 */
@Entity
@Table(name = "jos_slots")
@NamedQueries({
    @NamedQuery(name = "JosSlots.findAll", query = "SELECT j FROM JosSlots j"),
    @NamedQuery(name = "JosSlots.findBySlotId", query = "SELECT j FROM JosSlots j WHERE j.slotId = :slotId")
})
public class JosSlots extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "slot_id")
    private Integer slotId;
    @Basic(optional = false)
    @Column(name = "day")
    private String day;
    @Basic(optional = false)
    @Column(name = "hour_from")
    @Temporal(TemporalType.TIME)
    private Date hourFrom;
    @Basic(optional = false)
    @Column(name = "hour_to")
    @Temporal(TemporalType.TIME)
    private Date hourTo;
    @Basic(optional = false)
    @Column(name = "max_duration")
    @Temporal(TemporalType.TIME)
    private Date maxDuration;
    @Basic(optional = false)
    @Column(name = "min_duration")
    @Temporal(TemporalType.TIME)
    private Date minDuration;
    @Basic(optional = false)
    @Column(name = "steep")
    @Temporal(TemporalType.TIME)
    private Date steep;
    @Basic(optional = false)
    @Column(name = "tolerance")
    @Temporal(TemporalType.TIME)
    private Date tolerance;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "slotId", fetch = FetchType.LAZY)
    private Set<JosSlotsHasJosResourcesGroup> josSlotsHasJosResourcesGroupCollection;

    public JosSlots() {
    }

    public JosSlots(Integer slotId) {
        this.slotId = slotId;
    }

    public JosSlots(Integer slotId, String day, Date hourFrom, Date hourTo, Date maxDuration, Date minDuration, Date steep, Date tolerance) {
        this.slotId = slotId;
        this.day = day;
        this.hourFrom = hourFrom;
        this.hourTo = hourTo;
        this.maxDuration = maxDuration;
        this.minDuration = minDuration;
        this.steep = steep;
        this.tolerance = tolerance;
    }

    public Integer getSlotId() {
        return slotId;
    }

    public void setSlotId(Integer slotId) {
        this.slotId = slotId;
    }

    public String getDay() {
        return day;
    }

    public void setDay(String day) {
        this.day = day;
    }

    public Date getHourFrom() {
        return hourFrom;
    }

    public void setHourFrom(Date hourFrom) {
        this.hourFrom = hourFrom;
    }

    public Date getHourTo() {
        return hourTo;
    }

    public void setHourTo(Date hourTo) {
        this.hourTo = hourTo;
    }

    public Date getMaxDuration() {
        return maxDuration;
    }

    public void setMaxDuration(Date maxDuration) {
        this.maxDuration = maxDuration;
    }

    public Date getMinDuration() {
        return minDuration;
    }

    public void setMinDuration(Date minDuration) {
        this.minDuration = minDuration;
    }

    public Date getSteep() {
        return steep;
    }

    public void setSteep(Date steep) {
        this.steep = steep;
    }

    public Date getTolerance() {
        return tolerance;
    }

    public void setTolerance(Date tolerance) {
        this.tolerance = tolerance;
    }

    public Set<JosSlotsHasJosResourcesGroup> getJosSlotsHasJosResourcesGroupCollection() {
        return josSlotsHasJosResourcesGroupCollection;
    }

    public void setJosSlotsHasJosResourcesGroupCollection(Set<JosSlotsHasJosResourcesGroup> josSlotsHasJosResourcesGroupCollection) {
        this.josSlotsHasJosResourcesGroupCollection = josSlotsHasJosResourcesGroupCollection;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (slotId != null ? slotId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosSlots)) {
            return false;
        }
        JosSlots other = (JosSlots) object;
        if ((this.slotId == null && other.slotId != null) || (this.slotId != null && !this.slotId.equals(other.slotId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosSlots[slotId=" + slotId + "]";
    }

    @Override
    public Object getPK() {
        return getSlotId();
    }

    public int compareTo(BaseEntity o) {
        return getDay().compareTo(((JosSlots)o).getDay());
    }



}
