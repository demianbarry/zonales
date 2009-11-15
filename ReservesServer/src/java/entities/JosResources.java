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
import javax.persistence.ManyToOne;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.OneToOne;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_resources")
@NamedQueries({
    @NamedQuery(name = "JosResources.findAll", query = "SELECT j FROM JosResources j"),
    @NamedQuery(name = "JosResources.findByResourceId", query = "SELECT j FROM JosResources j WHERE j.resourceId = :resourceId"),
    @NamedQuery(name = "JosResources.findByGroupId", query = "SELECT j FROM JosResources j WHERE j.groupId = :group")
})
public class JosResources extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "resource_id")
    private Integer resourceId;
    @Column(name = "capacidad")
    private Short capacidad;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Column(name = "description")
    private String description;
    @JoinColumn(name = "group_id", referencedColumnName = "group_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosResourcesGroup groupId;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "resources", fetch = FetchType.LAZY)
    private Set<JosReserveHasJosResources> josReserveHasJosResourcesCollection;
    @OneToOne(cascade = CascadeType.ALL, mappedBy = "resourceId", fetch = FetchType.LAZY)
    private JosProfessionals josProfessionalsCollection;


    public JosResources() {
    }

    public JosResources(Integer resourceId) {
        this.resourceId = resourceId;
    }

    public JosResources(Integer resourceId, String name, JosResourcesGroup groupId) {
        this.resourceId = resourceId;
        this.name = name;
        this.groupId = groupId;
    }

    public Integer getResourceId() {
        return resourceId;
    }

    public void setResourceId(Integer resourceId) {
        this.resourceId = resourceId;
    }

    public Short getCapacidad() {
        return capacidad;
    }

    public void setCapacidad(Short capacidad) {
        this.capacidad = capacidad;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public JosResourcesGroup getGroupId() {
        return groupId;
    }

    public void setGroupId(JosResourcesGroup groupId) {
        this.groupId = groupId;
    }

    public JosProfessionals getJosProfessionalsCollection() {
        return josProfessionalsCollection;
    }

    public void setJosProfessionalsCollection(JosProfessionals josProfessionalsCollection) {
        this.josProfessionalsCollection = josProfessionalsCollection;
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
        hash += (resourceId != null ? resourceId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosResources)) {
            return false;
        }
        JosResources other = (JosResources) object;
        if ((this.resourceId == null && other.resourceId != null) || (this.resourceId != null && !this.resourceId.equals(other.resourceId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosResources[resourceId=" + resourceId + "]";
    }

    @Override
    public Object getPK() {
        return getResourceId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosResources)o).getName());
    }



}
