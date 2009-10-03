/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.Lob;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_resources_group")
@NamedQueries({
    @NamedQuery(name = "JosResourcesGroup.findAll", query = "SELECT j FROM JosResourcesGroup j"),
    @NamedQuery(name = "JosResourcesGroup.findByGroupId", query = "SELECT j FROM JosResourcesGroup j WHERE j.groupId = :groupID")
})
public class JosResourcesGroup extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "group_id")
    private Integer groupId;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Lob
    @Column(name = "visual_config")
    private String visualConfig;
    @Basic(optional = false)
    @Column(name = "location_id")
    private int locationId;
    @Basic(optional = false)
    @Column(name = "resource_type_id")
    private int resourceTypeId;

    public JosResourcesGroup() {
    }

    public JosResourcesGroup(Integer groupId) {
        this.groupId = groupId;
    }

    public JosResourcesGroup(Integer groupId, String name, int locationId, int resourceTypeId) {
        this.groupId = groupId;
        this.name = name;
        this.locationId = locationId;
        this.resourceTypeId = resourceTypeId;
    }

    public Integer getGroupId() {
        return groupId;
    }

    public void setGroupId(Integer groupId) {
        this.groupId = groupId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getVisualConfig() {
        return visualConfig;
    }

    public void setVisualConfig(String visualConfig) {
        this.visualConfig = visualConfig;
    }

    public int getLocationId() {
        return locationId;
    }

    public void setLocationId(int locationId) {
        this.locationId = locationId;
    }

    public int getResourceTypeId() {
        return resourceTypeId;
    }

    public void setResourceTypeId(int resourceTypeId) {
        this.resourceTypeId = resourceTypeId;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (groupId != null ? groupId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosResourcesGroup)) {
            return false;
        }
        JosResourcesGroup other = (JosResourcesGroup) object;
        if ((this.groupId == null && other.groupId != null) || (this.groupId != null && !this.groupId.equals(other.groupId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosResourcesGroup[groupId=" + groupId + "]";
    }

    @Override
    public Object getPK() {
        return getResourceTypeId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosResourcesGroup)o).getName());
    }



}
