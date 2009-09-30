/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.entities;

import java.io.Serializable;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.Entity;
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
 * @author fep
 */
@Entity
@Table(name = "eq_atributos", catalog = "services", schema = "")
@NamedQueries({@NamedQuery(name = "EqAtributos.findAll", query = "SELECT e FROM EqAtributos e"), @NamedQuery(name = "EqAtributos.findById", query = "SELECT e FROM EqAtributos e WHERE e.id = :id"), @NamedQuery(name = "EqAtributos.findByValor", query = "SELECT e FROM EqAtributos e WHERE e.valor = :valor"), @NamedQuery(name = "EqAtributos.findByPeso", query = "SELECT e FROM EqAtributos e WHERE e.peso = :peso")})
public class EqAtributos extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "id")
    private Integer id;
    @Basic(optional = false)
    @Column(name = "valor")
    private String valor;
    @Basic(optional = false)
    @Column(name = "peso")
    private int peso;
    @JoinColumn(name = "atributo_componente_id", referencedColumnName = "id")
    @ManyToOne(optional = false)
    private ClaseAtributo atributoComponenteId;
    @JoinColumn(name = "eq_id", referencedColumnName = "id")
    @ManyToOne(optional = false)
    private Eq eqId;

    public EqAtributos() {
    }

    public EqAtributos(Integer id) {
        this.id = id;
    }

    public EqAtributos(Integer id, String valor, int peso) {
        this.id = id;
        this.valor = valor;
        this.peso = peso;
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getValor() {
        return valor;
    }

    public void setValor(String valor) {
        this.valor = valor;
    }

    public int getPeso() {
        return peso;
    }

    public void setPeso(int peso) {
        this.peso = peso;
    }

    public ClaseAtributo getAtributoComponenteId() {
        return atributoComponenteId;
    }

    public void setAtributoComponenteId(ClaseAtributo atributoComponenteId) {
        this.atributoComponenteId = atributoComponenteId;
    }

    public Eq getEqId() {
        return eqId;
    }

    public void setEqId(Eq eqId) {
        this.eqId = eqId;
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (id != null ? id.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof EqAtributos)) {
            return false;
        }
        EqAtributos other = (EqAtributos) object;
        if ((this.id == null && other.id != null) || (this.id != null && !this.id.equals(other.id))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "com.zonales.persistence.entities.EqAtributos[id=" + id + "]";
    }

    @Override
    public Object getPK() {
        return id;
    }

}
