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
    @Basic(optional = false)
    @Column(name = "city_id")
    private int cityId;

    public JosSuppliers() {
    }

    public JosSuppliers(Integer supplierId) {
        this.supplierId = supplierId;
    }

    public JosSuppliers(Integer supplierId, String name, String street, int cityId) {
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

    public int getCityId() {
        return cityId;
    }

    public void setCityId(int cityId) {
        this.cityId = cityId;
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
