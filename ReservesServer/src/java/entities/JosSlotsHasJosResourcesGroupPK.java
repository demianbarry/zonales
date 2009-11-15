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
public class JosSlotsHasJosResourcesGroupPK implements Serializable {
    @Basic(optional = false)
    @Column(name = "slot_id")
    private int slotId;
    @Basic(optional = false)
    @Column(name = "group_id")
    private int groupId;

    public JosSlotsHasJosResourcesGroupPK() {
    }

    public JosSlotsHasJosResourcesGroupPK(int slotId, int groupId) {
        this.slotId = slotId;
        this.groupId = groupId;
    }

    public int getSlotId() {
        return slotId;
    }

    public void setSlotId(int slotId) {
        this.slotId = slotId;
    }

    public int getGroupId() {
        return groupId;
    }

    public void setGroupId(int groupId) {
        this.groupId = groupId;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (int) slotId;
        hash += (int) groupId;
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosSlotsHasJosResourcesGroupPK)) {
            return false;
        }
        JosSlotsHasJosResourcesGroupPK other = (JosSlotsHasJosResourcesGroupPK) object;
        if (this.slotId != other.slotId) {
            return false;
        }
        if (this.groupId != other.groupId) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosSlotsHasJosResourcesGroupPK[slotId=" + slotId + ", groupId=" + groupId + "]";
    }

}
