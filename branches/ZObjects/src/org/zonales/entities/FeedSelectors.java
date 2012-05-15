/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.entities;

import java.util.List;

/**
 *
 * @author juanma
 */
public class FeedSelectors {

    protected String url;

    /**
     * Get the value of url
     *
     * @return the value of url
     */
    public String getUrl() {
        return url;
    }

    /**
     * Set the value of url
     *
     * @param url new value of url
     */
    public void setUrl(String url) {
        this.url = url;
    }

    protected String urlLogo;

    /**
     * Get the value of url
     *
     * @return the value of url
     */
    public String getUrlLogo() {
        return urlLogo;
    }

    /**
     * Set the value of url
     *
     * @param url new value of url
     */
    public void setUrlLogo(String urlLogo) {
        this.urlLogo = urlLogo;
    }

    protected List<FeedSelector> selectors;

    protected List<FeedValidator> validators;
    /**
     * Get the value of selectors
     *
     * @return the value of selectors
     */
    public List<FeedSelector> getSelectors() {
        return selectors;
    }

    /**
     * Set the value of validators
     *
     * @param selectors new value of validators
     */
    public void setSelectors(List<FeedSelector> selectors) {
        this.selectors = selectors;
    }

    public List<FeedValidator> getValidators() {
        return validators;
    }

    /**
     * Set the value of validators
     *
     * @param validators new value of validators
     */
    public void setValidators(List<FeedValidator> validators) {
        this.validators = validators;
    }
}
