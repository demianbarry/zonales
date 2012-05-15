/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.entities;

import java.util.List;

/**
 *
 * @author rodrigo
 */
public class FeedValidators {


    protected List<FeedValidator> validators;

    /**
     * Get the value of selectors
     *
     * @return the value of selectors
     */
    public List<FeedValidator> getSelectors() {
        return validators;
    }

    /**
     * Set the value of selectors
     *
     * @param selectors new value of selectors
     */
    public void setSelectors(List<FeedValidator> validators) {
        this.validators = validators;
    }
}
