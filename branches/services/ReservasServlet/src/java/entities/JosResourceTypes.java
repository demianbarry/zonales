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
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_resource_types")
@NamedQueries({@NamedQuery(name = "JosResourceTypes.findAll", query = "SELECT j FROM JosResourceTypes j")})
public class JosResourceTypes extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "resource_type_id")
    private Integer resourceTypeId;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Column(name = "description")
    private String description;

    public JosResourceTypes() {
    }

    public JosResourceTypes(Integer resourceTypeId) {
        this.resourceTypeId = resourceTypeId;
    }

    public JosResourceTypes(Integer resourceTypeId, String name) {
        this.resourceTypeId = resourceTypeId;
        this.name = name;
    }

    public Integer getResourceTypeId() {
        return resourceTypeId;
    }

    public void setResourceTypeId(Integer resourceTypeId) {
        this.resourceTypeId = resourceTypeId;
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

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (resourceTypeId != null ? resourceTypeId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosResourceTypes)) {
            return false;
        }
        JosResourceTypes other = (JosResourceTypes) object;
        if ((this.resourceTypeId == null && other.resourceTypeId != null) || (this.resourceTypeId != null && !this.resourceTypeId.equals(other.resourceTypeId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosResourceTypes[resourceTypeId=" + resourceTypeId + "]";
    }

    @Override
    public Object getPK() {
        return getResourceTypeId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosResourceTypes)o).getName());
    }

}
