/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package metadata;

/**
 *
 * @author juanma
 */
public class Filtro {

    protected Integer minShuldMatch;

    /**
     * Get the value of minShuldMatch
     *
     * @return the value of minShuldMatch
     */
    public Integer getMinShuldMatch() {
        return minShuldMatch;
    }

    /**
     * Set the value of minShuldMatch
     *
     * @param minShuldMatch new value of minShuldMatch
     */
    public void setMinShuldMatch(Integer minShuldMatch) {
        this.minShuldMatch = minShuldMatch;
    }

    protected Integer dispersion;

    /**
     * Get the value of dispersion
     *
     * @return the value of dispersion
     */
    public Integer getDispersion() {
        return dispersion;
    }

    /**
     * Set the value of dispersion
     *
     * @param dispersion new value of dispersion
     */
    public void setDispersion(Integer dispersion) {
        this.dispersion = dispersion;
    }

    protected Boolean listaNegraDeUsuarios;

    /**
     * Get the value of listaNegraDeUsuarios
     *
     * @return the value of listaNegraDeUsuarios
     */
    public Boolean getListaNegraDeUsuarios() {
        return listaNegraDeUsuarios;
    }

    /**
     * Set the value of listaNegraDeUsuarios
     *
     * @param listaNegraDeUsuarios new value of listaNegraDeUsuarios
     */
    public void setListaNegraDeUsuarios(Boolean listaNegraDeUsuarios) {
        this.listaNegraDeUsuarios = listaNegraDeUsuarios;
    }

    protected Boolean listaNegraDePalabras;

    /**
     * Get the value of listaNegraDePalabras
     *
     * @return the value of listaNegraDePalabras
     */
    public Boolean getListaNegraDePalabras() {
        return listaNegraDePalabras;
    }

    /**
     * Set the value of listaNegraDePalabras
     *
     * @param listaNegraDePalabras new value of listaNegraDePalabras
     */
    public void setListaNegraDePalabras(Boolean listaNegraDePalabras) {
        this.listaNegraDePalabras = listaNegraDePalabras;
    }

    protected Integer minActions;

    /**
     * Get the value of minActions
     *
     * @return the value of minActions
     */
    public Integer getMinActions() {
        return minActions;
    }

    /**
     * Set the value of minActions
     *
     * @param minActions new value of minActions
     */
    public void setMinActions(Integer minActions) {
        this.minActions = minActions;
    }
}
