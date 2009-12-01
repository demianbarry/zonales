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
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.Table;

/**
 *
 * @author nacho
 */
@Entity
@Table(name = "jos_cities")
@NamedQueries({
    @NamedQuery(name = "JosCities.findAll", query = "SELECT j FROM JosCities j"),
    @NamedQuery(name = "JosCities.findByCityId", query = "SELECT j FROM JosCities j WHERE j.cityId = :cityId")
})
public class JosCities extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "city_id")
    private Integer cityId;
    @Basic(optional = false)
    @Column(name = "city")
    private String city;
    @Basic(optional = false)
    @Column(name = "zip_code")
    private String zipCode;
    @Basic(optional = false)
    @Column(name = "province")
    private String province;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "cityId", fetch = FetchType.LAZY)
    private Set<JosSuppliers> josSuppliersCollection;

    public JosCities() {
    }

    public JosCities(Integer cityId) {
        this.cityId = cityId;
    }

    public JosCities(Integer cityId, String city, String zipCode, String province) {
        this.cityId = cityId;
        this.city = city;
        this.zipCode = zipCode;
        this.province = province;
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

    public String getZipCode() {
        return zipCode;
    }

    public void setZipCode(String zipCode) {
        this.zipCode = zipCode;
    }

    public String getProvince() {
        return province;
    }

    public void setProvince(String province) {
        this.province = province;
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
