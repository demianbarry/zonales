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
 * @author Nosotros
 */
@Entity
@Table(name = "jos_specialties")
@NamedQueries({@NamedQuery(name = "JosSpecialties.findAll", query = "SELECT j FROM JosSpecialties j")})
public class JosSpecialties extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "specialty_id")
    private Integer specialtyId;
    @Basic(optional = false)
    @Column(name = "name")
    private String name;
    @Column(name = "description")
    private String description;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "specialties", fetch = FetchType.LAZY)
    private Set<JosProfessionalsHasJosSpecialties> josProfessionalsHasJosSpecialtiesCollection;

    public JosSpecialties() {
    }

    public JosSpecialties(Integer specialtyId) {
        this.specialtyId = specialtyId;
    }

    public JosSpecialties(Integer specialtyId, String name) {
        this.specialtyId = specialtyId;
        this.name = name;
    }

    public Integer getSpecialtyId() {
        return specialtyId;
    }

    public void setSpecialtyId(Integer specialtyId) {
        this.specialtyId = specialtyId;
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

    public Set<JosProfessionalsHasJosSpecialties> getJosProfessionalsHasJosSpecialtiesCollection() {
        return josProfessionalsHasJosSpecialtiesCollection;
    }

    public void setJosProfessionalsHasJosSpecialtiesCollection(Set<JosProfessionalsHasJosSpecialties> josProfessionalsHasJosSpecialtiesCollection) {
        this.josProfessionalsHasJosSpecialtiesCollection = josProfessionalsHasJosSpecialtiesCollection;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (specialtyId != null ? specialtyId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosSpecialties)) {
            return false;
        }
        JosSpecialties other = (JosSpecialties) object;
        if ((this.specialtyId == null && other.specialtyId != null) || (this.specialtyId != null && !this.specialtyId.equals(other.specialtyId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosSpecialties[specialtyId=" + specialtyId + "]";
    }

    @Override
    public Object getPK() {
        return getSpecialtyId();
    }

    public int compareTo(BaseEntity o) {
        return getName().compareTo(((JosSpecialties)o).getName());
    }



}
