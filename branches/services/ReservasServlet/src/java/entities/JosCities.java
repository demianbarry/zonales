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
import javax.persistence.Id;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_cities")
@NamedQueries({@NamedQuery(name = "JosCities.findAll", query = "SELECT j FROM JosCities j")})
public class JosCities extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @Basic(optional = false)
    @Column(name = "city_id")
    private Integer cityId;
    @Basic(optional = false)
    @Column(name = "city")
    private String city;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "cityId", fetch = FetchType.LAZY)
    private Set<JosSuppliers> josSuppliersCollection;

    public JosCities() {
    }

    public JosCities(Integer cityId) {
        this.cityId = cityId;
    }

    public JosCities(Integer cityId, String city) {
        this.cityId = cityId;
        this.city = city;
    }

    public Integer getCityId() {
        return cityId;
    }

    public void setCityId(Integer cityId) {
        this.cityId = cityId;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public Set<JosSuppliers> getJosSuppliersCollection() {
        return josSuppliersCollection;
    }

    public void setJosSuppliersCollection(Set<JosSuppliers> josSuppliersCollection) {
        this.josSuppliersCollection = josSuppliersCollection;
    }
    

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (cityId != null ? cityId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosCities)) {
            return false;
        }
        JosCities other = (JosCities) object;
        if ((this.cityId == null && other.cityId != null) || (this.cityId != null && !this.cityId.equals(other.cityId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosCities[cityId=" + cityId + "]";
    }

    @Override
    public Object getPK() {
        return getCityId();
    }

    public int compareTo(BaseEntity o) {
        return getCity().compareTo(((JosCities)o).getCity());
    }



}
