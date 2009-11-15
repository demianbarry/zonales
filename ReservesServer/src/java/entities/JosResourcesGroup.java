/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import java.util.Set;
import javax.persistence.Basic;
import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.Lob;
import javax.persistence.ManyToOne;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_resources_group")
@NamedQueries({
    @NamedQuery(name = "JosResourcesGroup.findAll", query = "SELECT j FROM JosResourcesGroup j"),
    @NamedQuery(name = "JosResourcesGroup.findByGroupId", query = "SELECT j FROM JosResourcesGroup j WHERE j.groupId = :groupId")
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
    @JoinColumn(name = "location_id", referencedColumnName = "location_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosLocations locationId;
    @JoinColumn(name = "resource_type_id", referencedColumnName = "resource_type_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosResourceTypes resourceTypeId;
    @Column(name = "expiration")
    private int expiration;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "resourcesGroup", fetch = FetchType.LAZY)
    private Set<JosSlotsHasJosResourcesGroup> josSlotsHasJosResourcesGroupCollection;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "groupId", fetch = FetchType.LAZY)
    private Set<JosResources> josResourcesCollection;

    
    public JosResourcesGroup() {
    }

    public JosResourcesGroup(Integer groupId) {
        this.groupId = groupId;
    }

    public JosResourcesGroup(Integer groupId, String name, JosLocations locationId, JosResourceTypes resourceTypeId, int expiration) {
        this.groupId = groupId;
        this.name = name;
        this.locationId = locationId;
        this.resourceTypeId = resourceTypeId;
        this.expiration = expiration;
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

    public Set<JosResources> getJosResourcesCollection() {
        return josResourcesCollection;
    }

    public void setJosResourcesCollection(Set<JosResources> josResourcesCollection) {
        this.josResourcesCollection = josResourcesCollection;
    }

    public Set<JosSlotsHasJosResourcesGroup> getJosSlotsHasJosResourcesGroupCollection() {
        return josSlotsHasJosResourcesGroupCollection;
    }

    public void setJosSlotsHasJosResourcesGroupCollection(Set<JosSlotsHasJosResourcesGroup> josSlotsHasJosResourcesGroupCollection) {
        this.josSlotsHasJosResourcesGroupCollection = josSlotsHasJosResourcesGroupCollection;
    }

    public JosLocations getLocationId() {
        return locationId;
    }

    public void setLocationId(JosLocations locationId) {
        this.locationId = locationId;
    }

    public JosResourceTypes getResourceTypeId() {
        return resourceTypeId;
    }

    public void setResourceTypeId(JosResourceTypes resourceTypeId) {
        this.resourceTypeId = resourceTypeId;
    }

    

    public int getExpiration() {
        return expiration;
    }

    public void setExpiration(int expiration) {
        this.expiration = expiration;
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
        return getGroupId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosResourcesGroup)o).getName());
    }

}
