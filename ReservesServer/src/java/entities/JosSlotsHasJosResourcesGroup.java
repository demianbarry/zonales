/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import java.util.Date;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_slots_has_jos_resources_group")
@NamedQueries({
    @NamedQuery(name = "JosSlotsHasJosResourcesGroup.findAll", query = "SELECT j FROM JosSlotsHasJosResourcesGroup j"),
    @NamedQuery(name = "JosSlotsHasJosResourcesGroup.findByGroupId", query = "SELECT j FROM JosSlotsHasJosResourcesGroup j WHERE j.josSlotsHasJosResourcesGroupPK.groupId = :groupId")
})
public class JosSlotsHasJosResourcesGroup extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @EmbeddedId
    protected JosSlotsHasJosResourcesGroupPK josSlotsHasJosResourcesGroupPK;
    @Basic(optional = false)
    @Column(name = "date_from")
    @Temporal(TemporalType.DATE)
    private Date dateFrom;
    @Basic(optional = false)
    @Column(name = "date_to")
    @Temporal(TemporalType.DATE)
    private Date dateTo;
    @JoinColumn(name = "event_id", referencedColumnName = "event_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosEvents eventId;
    @JoinColumn(name = "slot_id", referencedColumnName = "slot_id", insertable = false, updatable = false)
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosSlots slots;
    @JoinColumn(name = "group_id", referencedColumnName = "group_id", insertable = false, updatable = false)
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosResourcesGroup resourcesGroup;

    public JosSlotsHasJosResourcesGroup() {
    }

    public JosSlotsHasJosResourcesGroup(JosSlotsHasJosResourcesGroupPK josSlotsHasJosResourcesGroupPK) {
        this.josSlotsHasJosResourcesGroupPK = josSlotsHasJosResourcesGroupPK;
    }

    public JosSlotsHasJosResourcesGroup(JosSlotsHasJosResourcesGroupPK josSlotsHasJosResourcesGroupPK, Date dateFrom, Date dateTo, JosEvents eventId) {
        this.josSlotsHasJosResourcesGroupPK = josSlotsHasJosResourcesGroupPK;
        this.dateFrom = dateFrom;
        this.dateTo = dateTo;
        this.eventId = eventId;
    }

    public JosSlotsHasJosResourcesGroup(int slotId, int groupId) {
        this.josSlotsHasJosResourcesGroupPK = new JosSlotsHasJosResourcesGroupPK(slotId, groupId);
    }

    public JosSlotsHasJosResourcesGroupPK getJosSlotsHasJosResourcesGroupPK() {
        return josSlotsHasJosResourcesGroupPK;
    }

    public void setJosSlotsHasJosResourcesGroupPK(JosSlotsHasJosResourcesGroupPK josSlotsHasJosResourcesGroupPK) {
        this.josSlotsHasJosResourcesGroupPK = josSlotsHasJosResourcesGroupPK;
    }

    public Date getDateFrom() {
        return dateFrom;
    }

    public void setDateFrom(Date dateFrom) {
        this.dateFrom = dateFrom;
    }

    public Date getDateTo() {
        return dateTo;
    }

    public void setDateTo(Date dateTo) {
        this.dateTo = dateTo;
    }

    public JosEvents getEventId() {
        return eventId;
    }

    public void setEventId(JosEvents eventId) {
        this.eventId = eventId;
    }

    public JosResourcesGroup getResourcesGroup() {
        return resourcesGroup;
    }

    public void setResourcesGroup(JosResourcesGroup resourcesGroup) {
        this.resourcesGroup = resourcesGroup;
    }

    public JosSlots getSlots() {
        return slots;
    }

    public void setSlots(JosSlots slots) {
        this.slots = slots;
    }

    

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (josSlotsHasJosResourcesGroupPK != null ? josSlotsHasJosResourcesGroupPK.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosSlotsHasJosResourcesGroup)) {
            return false;
        }
        JosSlotsHasJosResourcesGroup other = (JosSlotsHasJosResourcesGroup) object;
        if ((this.josSlotsHasJosResourcesGroupPK == null && other.josSlotsHasJosResourcesGroupPK != null) || (this.josSlotsHasJosResourcesGroupPK != null && !this.josSlotsHasJosResourcesGroupPK.equals(other.josSlotsHasJosResourcesGroupPK))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosSlotsHasJosResourcesGroup[josSlotsHasJosResourcesGroupPK=" + josSlotsHasJosResourcesGroupPK + "]";
    }

    @Override
    public Object getPK() {
        return getJosSlotsHasJosResourcesGroupPK();
    }

    public int compareTo(BaseEntity o) {
        return getJosSlotsHasJosResourcesGroupPK().getSlotId()
                - ((JosSlotsHasJosResourcesGroup)o).getJosSlotsHasJosResourcesGroupPK().getSlotId();
    }



}
