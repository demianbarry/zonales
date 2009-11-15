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
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_suppliers")
@NamedQueries({@NamedQuery(name = "JosSuppliers.findAll", query = "SELECT j FROM JosSuppliers j")})
public class JosSuppliers extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "supplier_id")
    private Integer supplierId;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Basic(optional = false)
    @Column(name = "street")
    private String street;
    @Column(name = "number")
    private Integer number;
    @JoinColumn(name = "city_id", referencedColumnName = "city_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosCities cityId;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "supplierId", fetch = FetchType.LAZY)
    private Set<JosPhones> josPhonesCollection;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "locationId", fetch = FetchType.LAZY)
    private Set<JosLocations> josLocationsCollection;

    public JosSuppliers() {
    }

    public JosSuppliers(Integer supplierId) {
        this.supplierId = supplierId;
    }

    public JosSuppliers(Integer supplierId, String name, String street, JosCities cityId) {
        this.supplierId = supplierId;
        this.name = name;
        this.street = street;
        this.cityId = cityId;
    }

    public Integer getSupplierId() {
        return supplierId;
    }

    public void setSupplierId(Integer supplierId) {
        this.supplierId = supplierId;
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

    public JosCities getCityId() {
        return cityId;
    }

    public void setCityId(JosCities cityId) {
        this.cityId = cityId;
    }

    public Set<JosLocations> getJosLocationsCollection() {
        return josLocationsCollection;
    }

    public void setJosLocationsCollection(Set<JosLocations> josLocationsCollection) {
        this.josLocationsCollection = josLocationsCollection;
    }

    public Set<JosPhones> getJosPhonesCollection() {
        return josPhonesCollection;
    }

    public void setJosPhonesCollection(Set<JosPhones> josPhonesCollection) {
        this.josPhonesCollection = josPhonesCollection;
    }

    

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (supplierId != null ? supplierId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosSuppliers)) {
            return false;
        }
        JosSuppliers other = (JosSuppliers) object;
        if ((this.supplierId == null && other.supplierId != null) || (this.supplierId != null && !this.supplierId.equals(other.supplierId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosSuppliers[supplierId=" + supplierId + "]";
    }

    @Override
    public Object getPK() {
        return getSupplierId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosSuppliers)o).getName());
    }

}
