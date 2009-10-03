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
@Table(name = "jos_locations")
@NamedQueries({@NamedQuery(name = "JosLocations.findAll", query = "SELECT j FROM JosLocations j")})
public class JosLocations extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "location_id")
    private Integer locationId;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Basic(optional = false)
    @Column(name = "street")
    private String street;
    @Column(name = "number")
    private Integer number;
    @Basic(optional = false)
    @Column(name = "supplier_id")
    private int supplierId;

    public JosLocations() {
    }

    public JosLocations(Integer locationId) {
        this.locationId = locationId;
    }

    public JosLocations(Integer locationId, String name, String street, int supplierId) {
        this.locationId = locationId;
        this.name = name;
        this.street = street;
        this.supplierId = supplierId;
    }

    public Integer getLocationId() {
        return locationId;
    }

    public void setLocationId(Integer locationId) {
        this.locationId = locationId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getStreet() {
        return street;
    }

    public void setStreet(String street) {
        this.street = street;
    }

    public Integer getNumber() {
        return number;
    }

    public void setNumber(Integer number) {
        this.number = number;
    }

    public int getSupplierId() {
        return supplierId;
    }

    public void setSupplierId(int supplierId) {
        this.supplierId = supplierId;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (locationId != null ? locationId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosLocations)) {
            return false;
        }
        JosLocations other = (JosLocations) object;
        if ((this.locationId == null && other.locationId != null) || (this.locationId != null && !this.locationId.equals(other.locationId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosLocations[locationId=" + locationId + "]";
    }

    @Override
    public Object getPK() {
        return getLocationId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosLocations)o).getName());
    }



}
