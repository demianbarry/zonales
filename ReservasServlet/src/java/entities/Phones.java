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
@Table(name = "phones")
@NamedQueries({@NamedQuery(name = "Phones.findAll", query = "SELECT p FROM Phones p")})
public class Phones extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "phone_id")
    private Integer phoneId;
    @Basic(optional = false)
    @Column(name = "supplier_id")
    private int supplierId;
    @Basic(optional = false)
    @Column(name = "phone")
    private String phone;

    public Phones() {
    }

    public Phones(Integer phoneId) {
        this.phoneId = phoneId;
    }

    public Phones(Integer phoneId, int supplierId, String phone) {
        this.phoneId = phoneId;
        this.supplierId = supplierId;
        this.phone = phone;
    }

    public Integer getPhoneId() {
        return phoneId;
    }

    public void setPhoneId(Integer phoneId) {
        this.phoneId = phoneId;
    }

    public int getSupplierId() {
        return supplierId;
    }

    public void setSupplierId(int supplierId) {
        this.supplierId = supplierId;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (phoneId != null ? phoneId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof Phones)) {
            return false;
        }
        Phones other = (Phones) object;
        if ((this.phoneId == null && other.phoneId != null) || (this.phoneId != null && !this.phoneId.equals(other.phoneId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.Phones[phoneId=" + phoneId + "]";
    }

    @Override
    public Object getPK() {
        return getPhoneId();
    }

    public int compareTo(BaseEntity o) {
        return getPhone().compareTo(((Phones)o).getPhone());
    }



}
