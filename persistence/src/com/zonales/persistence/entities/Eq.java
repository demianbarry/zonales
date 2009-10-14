/**
 * @version	$Id$
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
package com.zonales.persistence.entities;

import java.io.Serializable;
import java.util.List;
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
 * @author fep
 */
@Entity
@Table(name = "eq", catalog = "services", schema = "")
@NamedQueries({@NamedQuery(name = "Eq.findAll", query = "SELECT e FROM Eq e"), @NamedQuery(name = "Eq.findById", query = "SELECT e FROM Eq e WHERE e.id = :id"), @NamedQuery(name = "Eq.findByNombre", query = "SELECT e FROM Eq e WHERE e.nombre = :nombre"), @NamedQuery(name = "Eq.findByDescripcion", query = "SELECT e FROM Eq e WHERE e.descripcion = :descripcion"), @NamedQuery(name = "Eq.findByObservaciones", query = "SELECT e FROM Eq e WHERE e.observaciones = :observaciones"), @NamedQuery(name = "Eq.findByUserId", query = "SELECT e FROM Eq e WHERE e.userId = :userId"), @NamedQuery(name = "Eq.findBySolrqueryBq", query = "SELECT e FROM Eq e WHERE e.solrqueryBq = :solrqueryBq")})
public class Eq extends BaseEntity implements Serializable {
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
    @Column(name = "user_id")
    private Integer userId;
    @Column(name = "solrquery_bq")
    private String solrqueryBq;
    @OneToMany(cascade = CascadeType.ALL, fetch=FetchType.LAZY, mappedBy = "eqId")
    private List<EqAtributos> eqAtributosList;

    public Eq() {
    }

    public Eq(Integer id) {
        this.id = id;
    }

    public Eq(Integer id, String nombre) {
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

    public Integer getUserId() {
        return userId;
    }

    public void setUserId(Integer userId) {
        this.userId = userId;
    }

    public String getSolrqueryBq() {
        return solrqueryBq;
    }

    public void setSolrqueryBq(String solrqueryBq) {
        this.solrqueryBq = solrqueryBq;
    }

    public List<EqAtributos> getEqAtributosList() {
        return eqAtributosList;
    }

    public void setEqAtributosList(List<EqAtributos> eqAtributosList) {
        this.eqAtributosList = eqAtributosList;
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
        if (!(object instanceof Eq)) {
            return false;
        }
        Eq other = (Eq) object;
        if ((this.id == null && other.id != null) || (this.id != null && !this.id.equals(other.id))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "com.zonales.persistence.entities.Eq[id=" + id + "]";
    }

    @Override
    public Object getPK() {
        return id;
    }

}
