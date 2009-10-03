/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.Embeddable;

/**
 *
 * @author Nosotros
 */
@Embeddable
public class JosProfessionalsHasJosSpecialtiesPK implements Serializable {
    @Basic(optional = false)
    @Column(name = "professional_id")
    private int professionalId;
    @Basic(optional = false)
    @Column(name = "id_specialty")
    private int idSpecialty;

    public JosProfessionalsHasJosSpecialtiesPK(int professionalId, int idSpecialty) {
        this.professionalId = professionalId;
        this.idSpecialty = idSpecialty;
    }

    public int getProfessionalId() {
        return professionalId;
    }

    public void setProfessionalId(int professionalId) {
        this.professionalId = professionalId;
    }

    public int getIdSpecialty() {
        return idSpecialty;
    }

    public void setIdSpecialty(int idSpecialty) {
        this.idSpecialty = idSpecialty;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (int) professionalId;
        hash += (int) idSpecialty;
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosProfessionalsHasJosSpecialtiesPK)) {
            return false;
        }
        JosProfessionalsHasJosSpecialtiesPK other = (JosProfessionalsHasJosSpecialtiesPK) object;
        if (this.professionalId != other.professionalId) {
            return false;
        }
        if (this.idSpecialty != other.idSpecialty) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosProfessionalsHasJosSpecialtiesPK[professionalId=" + professionalId + ", idSpecialty=" + idSpecialty + "]";
    }

}
