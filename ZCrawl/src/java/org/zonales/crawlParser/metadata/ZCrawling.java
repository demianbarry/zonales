/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlParser.metadata;

import java.util.List;

/**
 *
 * @author juanma
 */
public class ZCrawling {

    public ZCrawling() {
    }

    protected String localidad;
    protected List<String> tags = null;
    protected String fuente = null;
    protected String uriFuente = null;
    protected List<Criterio> criterios = null;
    protected List<Criterio> noCriterios = null;
    protected List<Filtro> filtros = null;
    protected String tagsFuente = null;
    protected List<String> comentarios;

    /**
     * Get the value of localidad
     *
     * @return the value of localidad
     */
    public String getLocalidad() {
        return localidad;
    }

    /**
     * Set the value of localidad
     *
     * @param localidad new value of localidad
     */
    public void setLocalidad(String localidad) {
        this.localidad = localidad;
    }

    /**
     * Get the value of tags
     *
     * @return the value of tags
     */
    public List<String> getTags() {
        return tags;
    }

    /**
     * Set the value of tags
     *
     * @param tags new value of tags
     */
    public void setTags(List<String> tags) {
        this.tags = tags;
    }

    /**
     * Get the value of fuente
     *
     * @return the value of fuente
     */
    public String getFuente() {
        return fuente;
    }

    /**
     * Set the value of fuente
     *
     * @param fuente new value of fuente
     */
    public void setFuente(String fuente) {
        this.fuente = fuente;
    }

    /**
     * Get the value of uriFuente
     *
     * @return the value of uriFuente
     */
    public String getUriFuente() {
        return uriFuente;
    }

    /**
     * Set the value of uriFuente
     *
     * @param uriFuente new value of uriFuente
     */
    public void setUriFuente(String uriFuente) {
        this.uriFuente = uriFuente;
    }

    /**
     * Get the value of criterios
     *
     * @return the value of criterios
     */
    public List<Criterio> getCriterios() {
        return criterios;
    }

    /**
     * Set the value of criterios
     *
     * @param criterios new value of criterios
     */
    public void setCriterios(List<Criterio> criterios) {
        this.criterios = criterios;
    }

    /**
     * Get the value of noCriterios
     *
     * @return the value of noCriterios
     */
    public List<Criterio> getNoCriterios() {
        return noCriterios;
    }

    /**
     * Set the value of noCriterios
     *
     * @param noCriterios new value of noCriterios
     */
    public void setNoCriterios(List<Criterio> noCriterios) {
        this.noCriterios = noCriterios;
    }
    
    /**
     * Get the value of filtros
     *
     * @return the value of filtros
     */
    public List<Filtro> getFiltros() {
        return filtros;
    }

    /**
     * Set the value of filtros
     *
     * @param filtros new value of filtros
     */
    public void setFiltros(List<Filtro> filtros) {
        this.filtros = filtros;
    }

    /**
     * Get the value of tagsFuente
     *
     * @return the value of tagsFuente
     */
    public String getTagsFuente() {
        return tagsFuente;
    }

    /**
     * Set the value of tagsFuente
     *
     * @param tagsFuente new value of tagsFuente
     */
    public void setTagsFuente(String tagsFuente) {
        this.tagsFuente = tagsFuente;
    }

    /**
     * Get the value of comentarios
     *
     * @return the value of comentarios
     */
    public List<String> getComentarios() {
        return comentarios;
    }

    /**
     * Set the value of comentarios
     *
     * @param comentarios new value of comentarios
     */
    public void setComentarios(List<String> comentarios) {
        this.comentarios = comentarios;
    }

    @Override
    public String toString() {
        return "ZCrawling{" + "localidad=" + localidad + " tags=" + tags + " fuente=" + fuente + " uriFuente=" + uriFuente + " criterios=" + criterios + " noCriterios=" + noCriterios + " filtros=" + filtros + " tagsFuente=" + tagsFuente + '}';
    }

}
