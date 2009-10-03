/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package entities;

import java.io.Serializable;
import javax.persistence.EmbeddedId;
import javax.persistence.Entity;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;

/**
 *
 * @author Nosotros
 */
@Entity
@Table(name = "jos_professionals_has_jos_specialties")
@NamedQueries({@NamedQuery(name = "JosProfessionalsHasJosSpecialties.findAll", query = "SELECT j FROM JosProfessionalsHasJosSpecialties j")})
public class JosProfessionalsHasJosSpecialties extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @EmbeddedId
    protected JosProfessionalsHasJosSpecialtiesPK josProfessionalsHasJosSpecialtiesPK;

    public JosProfessionalsHasJosSpecialties() {
    }

    public JosProfessionalsHasJosSpecialties(JosProfessionalsHasJosSpecialtiesPK josProfessionalsHasJosSpecialtiesPK) {
        this.josProfessionalsHasJosSpecialtiesPK = josProfessionalsHasJosSpecialtiesPK;
    }

    public JosProfessionalsHasJosSpecialties(int professionalId, int idSpecialty) {
        this.josProfessionalsHasJosSpecialtiesPK = new JosProfessionalsHasJosSpecialtiesPK(professionalId, idSpecialty);
    }

    public JosProfessionalsHasJosSpecialtiesPK getJosProfessionalsHasJosSpecialtiesPK() {
        return josProfessionalsHasJosSpecialtiesPK;
    }

    public void setJosProfessionalsHasJosSpecialtiesPK(JosProfessionalsHasJosSpecialtiesPK josProfessionalsHasJosSpecialtiesPK) {
        this.josProfessionalsHasJosSpecialtiesPK = josProfessionalsHasJosSpecialtiesPK;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (josProfessionalsHasJosSpecialtiesPK != null ? josProfessionalsHasJosSpecialtiesPK.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof JosProfessionalsHasJosSpecialties)) {
            return false;
        }
        JosProfessionalsHasJosSpecialties other = (JosProfessionalsHasJosSpecialties) object;
        if ((this.josProfessionalsHasJosSpecialtiesPK == null && other.josProfessionalsHasJosSpecialtiesPK != null) || (this.josProfessionalsHasJosSpecialtiesPK != null && !this.josProfessionalsHasJosSpecialtiesPK.equals(other.josProfessionalsHasJosSpecialtiesPK))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "entities.JosProfessionalsHasJosSpecialties[josProfessionalsHasJosSpecialtiesPK=" + josProfessionalsHasJosSpecialtiesPK + "]";
    }

    @Override
    public Object getPK() {
        return getJosProfessionalsHasJosSpecialtiesPK();
    }

    public int compareTo(BaseEntity o) {
        return getJosProfessionalsHasJosSpecialtiesPK().getIdSpecialty()
                - ((JosProfessionalsHasJosSpecialties)o).getJosProfessionalsHasJosSpecialtiesPK().getIdSpecialty();
    }

}
