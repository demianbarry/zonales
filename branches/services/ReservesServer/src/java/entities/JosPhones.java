/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.Basic;
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
import javax.persistence.Table;

/**
 *
 * @author Nos
 */
@Entity
@Table(name = "jos_phones")
@NamedQueries({
    @NamedQuery(name = "JosPhones.findAll", query = "SELECT j FROM JosPhones j"),
    @NamedQuery(name = "JosPhones.findByPhoneId", query = "SELECT j FROM JosPhones j WHERE j.phoneId = :phoneId"),
    @NamedQuery(name = "JosPhones.findBySupplierId", query = "SELECT j FROM JosPhones j WHERE j.supplierId.supplierId = :supplierId")
})
public class JosPhones extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "phone_id")
    private Integer phoneId;
    @JoinColumn(name = "supplier_id", referencedColumnName = "supplier_id")
    @ManyToOne(optional = false, fetch = FetchType.LAZY)
    private JosSuppliers supplierId;
    @Basic(optional = false)
    @Column(name = "phone")
    private String phone;

    public JosPhones() {
    }

    public JosPhones(Integer phoneId) {
        this.phoneId = phoneId;
    }

    public JosPhones(Integer phoneId, JosSuppliers supplierId, String phone) {
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

    public JosSuppliers getSupplierId() {
        return supplierId;
    }

    public void setSupplierId(JosSuppliers supplierId) {
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
        if (!(object instanceof JosPhones)) {
            return false;
        }
        JosPhones other = (JosPhones) object;
        if ((this.phoneId == null && other.phoneId != null) || (this.phoneId != null && !this.phoneId.equals(other.phoneId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosPhones[phoneId=" + phoneId + "]";
    }

    @Override
    public Object getPK() {
        return getPhoneId();
    }

    public int compareTo(BaseEntity o) {
        return getPhone().compareTo(((JosPhones)o).getPhone());
    }


}
