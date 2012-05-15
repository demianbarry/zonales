/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.entities;

/**
 *
 * @author rodrigo
 */
public class FeedValidator {

      protected String type;

    /**
     * Get the value of type
     *
     * @return the value of type
     */
    public String getType() {
        return type;
    }

    /**
     * Set the value of type
     *
     * @param type new value of type
     */
    public void setType(String type) {
        this.type = type;
    }
    protected String validator;

    /**
     * Get the value of validator
     *
     * @return the value of validator
     */
    public String getSelector() {
        return validator;
    }

    /**
     * Set the value of validator
     *
     * @param validator new value of validator
     */
    public void setSelector(String selector) {
        this.validator = validator;
    }

    public FeedValidator() {
    }

    public FeedValidator(String type, String validator) {
        this.type = type;
        this.validator = validator;
    }
}
