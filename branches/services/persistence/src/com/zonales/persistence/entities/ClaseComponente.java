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
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.OneToMany;
import javax.persistence.Table;

/**
 *
 * @author fep
 */
@Entity
@Table(name = "clase_componente", catalog = "services", schema = "")
@NamedQueries({@NamedQuery(name = "ClaseComponente.findAll", query = "SELECT c FROM ClaseComponente c"), @NamedQuery(name = "ClaseComponente.findById", query = "SELECT c FROM ClaseComponente c WHERE c.id = :id"), @NamedQuery(name = "ClaseComponente.findByNombre", query = "SELECT c FROM ClaseComponente c WHERE c.nombre = :nombre"), @NamedQuery(name = "ClaseComponente.findByDescripcion", query = "SELECT c FROM ClaseComponente c WHERE c.descripcion = :descripcion"), @NamedQuery(name = "ClaseComponente.findByObservaciones", query = "SELECT c FROM ClaseComponente c WHERE c.observaciones = :observaciones")})
public class ClaseComponente implements Serializable {
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
    @OneToMany(cascade = CascadeType.ALL, mappedBy = "claseComponenteId")
    private List<ClaseAtributo> claseAtributoList;

    public ClaseComponente() {
    }

    public ClaseComponente(Integer id) {
        this.id = id;
    }

    public ClaseComponente(Integer id, String nombre) {
        this.id = id;
        this.nombre = nombre;
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

    public List<ClaseAtributo> getClaseAtributoList() {
        return claseAtributoList;
    }

    public void setClaseAtributoList(List<ClaseAtributo> claseAtributoList) {
        this.claseAtributoList = claseAtributoList;
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
        if (!(object instanceof ClaseComponente)) {
            return false;
        }
        ClaseComponente other = (ClaseComponente) object;
        if ((this.id == null && other.id != null) || (this.id != null && !this.id.equals(other.id))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "com.zonales.persistence.entities.ClaseComponente[id=" + id + "]";
    }

}
