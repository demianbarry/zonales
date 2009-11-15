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
@Table(name = "jos_professionals")
@NamedQueries({@NamedQuery(name = "JosProfessionals.findAll", query = "SELECT j FROM JosProfessionals j")})
public class JosProfessionals extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "professional_id")
    private Integer professionalId;
    @Column(name = "enrollment")
    private String enrollment;
    @Basic(optional = false)
    @Column(name = "jos_users_id")
    private int josUsersId;
    @JoinColumn(name = "resource_id", referencedColumnName = "resource_id")
    @OneToOne(optional = false, fetch = FetchType.LAZY)
    private JosResources resourceId;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "professionals", fetch = FetchType.LAZY)
    private Set<JosProfessionalsHasJosSpecialties> josProfessionalsHasJosSpecialtiesCollection;


    public JosProfessionals() {
    }

    public JosProfessionals(Integer professionalId) {
        this.professionalId = professionalId;
    }

    public JosProfessionals(Integer professionalId, int josUsersId, JosResources resourceId) {
        this.professionalId = professionalId;
        this.josUsersId = josUsersId;
        this.resourceId = resourceId;
    }

    public Integer getProfessionalId() {
        return professionalId;
    }

    public void setProfessionalId(Integer professionalId) {
        this.professionalId = professionalId;
    }

    public String getEnrollment() {
        return enrollment;
    }

    public void setEnrollment(String enrollment) {
        this.enrollment = enrollment;
    }

    public int getJosUsersId() {
        return josUsersId;
    }

    public void setJosUsersId(int josUsersId) {
        this.josUsersId = josUsersId;
    }

    public JosResources getResourceId() {
        return resourceId;
    }

    public void setResourceId(JosResources resourceId) {
        this.resourceId = resourceId;
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
        hash += (professionalId != null ? professionalId.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosProfessionals)) {
            return false;
        }
        JosProfessionals other = (JosProfessionals) object;
        if ((this.professionalId == null && other.professionalId != null) || (this.professionalId != null && !this.professionalId.equals(other.professionalId))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosProfessionals[professionalId=" + professionalId + "]";
    }

    @Override
    public Object getPK() {
        return getProfessionalId();
    }

    public int compareTo(BaseEntity o) {
        return getProfessionalId().compareTo(((JosProfessionals)o).getProfessionalId());
    }



}
