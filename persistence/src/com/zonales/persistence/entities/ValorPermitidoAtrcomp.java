/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.entities;

import java.io.Serializable;
import java.util.List;
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
import javax.persistence.OneToMany;
import javax.persistence.Table;

/**
 *
 * @author fep
 */
@Entity
@Table(name = "valor_permitido_atrcomp", catalog = "services", schema = "")
@NamedQueries({@NamedQuery(name = "ValorPermitidoAtrcomp.findAll", query = "SELECT v FROM ValorPermitidoAtrcomp v"), @NamedQuery(name = "ValorPermitidoAtrcomp.findById", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.id = :id"), @NamedQuery(name = "ValorPermitidoAtrcomp.findByValor", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.valor = :valor"), @NamedQuery(name = "ValorPermitidoAtrcomp.findByValorHasta", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.valorHasta = :valorHasta"), @NamedQuery(name = "ValorPermitidoAtrcomp.findByDescripcion", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.descripcion = :descripcion"), @NamedQuery(name = "ValorPermitidoAtrcomp.findByObservaciones", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.observaciones = :observaciones"), @NamedQuery(name = "ValorPermitidoAtrcomp.findByValorPermitidoFiltroPadre", query = "SELECT v FROM ValorPermitidoAtrcomp v WHERE v.valorPermitidoFiltroPadre = :valorPermitidoFiltroPadre")})
public class ValorPermitidoAtrcomp implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "id")
    private Integer id;
    @Basic(optional = false)
    @Column(name = "valor")
    private String valor;
    @Column(name = "valor_hasta")
    private String valorHasta;
    @Column(name = "descripcion")
    private String descripcion;
    @Column(name = "observaciones")
    private String observaciones;
    @Column(name = "valor_permitido_filtro_padre")
    private String valorPermitidoFiltroPadre;
    @JoinColumn(name = "atributo_componente_id", referencedColumnName = "id")
    @ManyToOne(optional = false)
    private ClaseAtributo atributoComponenteId;
    @OneToMany(mappedBy = "valorPermitidoAtrcompId")
    private List<ValorPermitidoAtrcomp> valorPermitidoAtrcompList;
    @JoinColumn(name = "valor_permitido_atrcomp_id", referencedColumnName = "id")
    @ManyToOne
    private ValorPermitidoAtrcomp valorPermitidoAtrcompId;

    public ValorPermitidoAtrcomp() {
    }

    public ValorPermitidoAtrcomp(Integer id) {
        this.id = id;
    }

    public ValorPermitidoAtrcomp(Integer id, String valor) {
        this.id = id;
        this.valor = valor;
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

    public String getValorHasta() {
        return valorHasta;
    }

    public void setValorHasta(String valorHasta) {
        this.valorHasta = valorHasta;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public String getObservaciones() {
        return observaciones;
    }

    public void setObservaciones(String observaciones) {
        this.observaciones = observaciones;
    }

    public String getValorPermitidoFiltroPadre() {
        return valorPermitidoFiltroPadre;
    }

    public void setValorPermitidoFiltroPadre(String valorPermitidoFiltroPadre) {
        this.valorPermitidoFiltroPadre = valorPermitidoFiltroPadre;
    }

    public ClaseAtributo getAtributoComponenteId() {
        return atributoComponenteId;
    }

    public void setAtributoComponenteId(ClaseAtributo atributoComponenteId) {
        this.atributoComponenteId = atributoComponenteId;
    }

    public List<ValorPermitidoAtrcomp> getValorPermitidoAtrcompList() {
        return valorPermitidoAtrcompList;
    }

    public void setValorPermitidoAtrcompList(List<ValorPermitidoAtrcomp> valorPermitidoAtrcompList) {
        this.valorPermitidoAtrcompList = valorPermitidoAtrcompList;
    }

    public ValorPermitidoAtrcomp getValorPermitidoAtrcompId() {
        return valorPermitidoAtrcompId;
    }

    public void setValorPermitidoAtrcompId(ValorPermitidoAtrcomp valorPermitidoAtrcompId) {
        this.valorPermitidoAtrcompId = valorPermitidoAtrcompId;
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
        if (!(object instanceof ValorPermitidoAtrcomp)) {
            return false;
        }
        ValorPermitidoAtrcomp other = (ValorPermitidoAtrcomp) object;
        if ((this.id == null && other.id != null) || (this.id != null && !this.id.equals(other.id))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "com.zonales.persistence.entities.ValorPermitidoAtrcomp[id=" + id + "]";
    }

}
