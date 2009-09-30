/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.entities;

import java.io.Serializable;
import java.util.List;
import javax.persistence.Basic;
import javax.persistence.CascadeType;
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
@Table(name = "clase_atributo", catalog = "services", schema = "")
@NamedQueries({@NamedQuery(name = "ClaseAtributo.findAll", query = "SELECT c FROM ClaseAtributo c"), @NamedQuery(name = "ClaseAtributo.findById", query = "SELECT c FROM ClaseAtributo c WHERE c.id = :id"), @NamedQuery(name = "ClaseAtributo.findByNombre", query = "SELECT c FROM ClaseAtributo c WHERE c.nombre = :nombre"), @NamedQuery(name = "ClaseAtributo.findByDescripcion", query = "SELECT c FROM ClaseAtributo c WHERE c.descripcion = :descripcion"), @NamedQuery(name = "ClaseAtributo.findByObservaciones", query = "SELECT c FROM ClaseAtributo c WHERE c.observaciones = :observaciones"), @NamedQuery(name = "ClaseAtributo.findByTipo", query = "SELECT c FROM ClaseAtributo c WHERE c.tipo = :tipo"), @NamedQuery(name = "ClaseAtributo.findByObligatorio", query = "SELECT c FROM ClaseAtributo c WHERE c.obligatorio = :obligatorio"), @NamedQuery(name = "ClaseAtributo.findByQryLovExterna", query = "SELECT c FROM ClaseAtributo c WHERE c.qryLovExterna = :qryLovExterna"), @NamedQuery(name = "ClaseAtributo.findByFiltraXPadre", query = "SELECT c FROM ClaseAtributo c WHERE c.filtraXPadre = :filtraXPadre"), @NamedQuery(name = "ClaseAtributo.findByQryFiltraXPadre", query = "SELECT c FROM ClaseAtributo c WHERE c.qryFiltraXPadre = :qryFiltraXPadre"), @NamedQuery(name = "ClaseAtributo.findByEcualizable", query = "SELECT c FROM ClaseAtributo c WHERE c.ecualizable = :ecualizable")})
public class ClaseAtributo extends BaseEntity implements Serializable {
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "id")
    private Integer id;
    @Basic(optional = false)
    @Column(name = "nombre")
    private String nombre;
    @Column(name = "descripcion")
    private String descripcion;
    @Column(name = "observaciones")
    private String observaciones;
    @Basic(optional = false)
    @Column(name = "tipo")
    private String tipo;
    @Basic(optional = false)
    @Column(name = "obligatorio")
    private boolean obligatorio;
    @Column(name = "qry_lov_externa")
    private String qryLovExterna;
    @Basic(optional = false)
    @Column(name = "filtra_x_padre")
    private boolean filtraXPadre;
    @Column(name = "qry_filtra_x_padre")
    private String qryFiltraXPadre;
    @Basic(optional = false)
    @Column(name = "ecualizable")
    private boolean ecualizable;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "atributoComponenteId")
    private List<EqAtributos> eqAtributosList;
    @OneToMany(mappedBy = "claseAtributoId")
    private List<ClaseAtributo> claseAtributoList;
    @JoinColumn(name = "clase_atributo_id", referencedColumnName = "id")
    @ManyToOne
    private ClaseAtributo claseAtributoId;
    @JoinColumn(name = "clase_componente_id", referencedColumnName = "id")
    @ManyToOne(optional = false)
    private ClaseComponente claseComponenteId;
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "atributoComponenteId")
    private List<ValorPermitidoAtrcomp> valorPermitidoAtrcompList;

    public ClaseAtributo() {
    }

    public ClaseAtributo(Integer id) {
        this.id = id;
    }

    public ClaseAtributo(Integer id, String nombre, String tipo, boolean obligatorio, boolean filtraXPadre, boolean ecualizable) {
        this.id = id;
        this.nombre = nombre;
        this.tipo = tipo;
        this.obligatorio = obligatorio;
        this.filtraXPadre = filtraXPadre;
        this.ecualizable = ecualizable;
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
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

    public String getTipo() {
        return tipo;
    }

    public void setTipo(String tipo) {
        this.tipo = tipo;
    }

    public boolean getObligatorio() {
        return obligatorio;
    }

    public void setObligatorio(boolean obligatorio) {
        this.obligatorio = obligatorio;
    }

    public String getQryLovExterna() {
        return qryLovExterna;
    }

    public void setQryLovExterna(String qryLovExterna) {
        this.qryLovExterna = qryLovExterna;
    }

    public boolean getFiltraXPadre() {
        return filtraXPadre;
    }

    public void setFiltraXPadre(boolean filtraXPadre) {
        this.filtraXPadre = filtraXPadre;
    }

    public String getQryFiltraXPadre() {
        return qryFiltraXPadre;
    }

    public void setQryFiltraXPadre(String qryFiltraXPadre) {
        this.qryFiltraXPadre = qryFiltraXPadre;
    }

    public boolean getEcualizable() {
        return ecualizable;
    }

    public void setEcualizable(boolean ecualizable) {
        this.ecualizable = ecualizable;
    }

    public List<EqAtributos> getEqAtributosList() {
        return eqAtributosList;
    }

    public void setEqAtributosList(List<EqAtributos> eqAtributosList) {
        this.eqAtributosList = eqAtributosList;
    }

    public List<ClaseAtributo> getClaseAtributoList() {
        return claseAtributoList;
    }

    public void setClaseAtributoList(List<ClaseAtributo> claseAtributoList) {
        this.claseAtributoList = claseAtributoList;
    }

    public ClaseAtributo getClaseAtributoId() {
        return claseAtributoId;
    }

    public void setClaseAtributoId(ClaseAtributo claseAtributoId) {
        this.claseAtributoId = claseAtributoId;
    }

    public ClaseComponente getClaseComponenteId() {
        return claseComponenteId;
    }

    public void setClaseComponenteId(ClaseComponente claseComponenteId) {
        this.claseComponenteId = claseComponenteId;
    }

    public List<ValorPermitidoAtrcomp> getValorPermitidoAtrcompList() {
        return valorPermitidoAtrcompList;
    }

    public void setValorPermitidoAtrcompList(List<ValorPermitidoAtrcomp> valorPermitidoAtrcompList) {
        this.valorPermitidoAtrcompList = valorPermitidoAtrcompList;
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
        if (!(object instanceof ClaseAtributo)) {
            return false;
        }
        ClaseAtributo other = (ClaseAtributo) object;
        if ((this.id == null && other.id != null) || (this.id != null && !this.id.equals(other.id))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "com.zonales.persistence.entities.ClaseAtributo[id=" + id + "]";
    }

    @Override
    public Object getPK() {
        return id;
    }

}
