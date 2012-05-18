/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.entities;

/**
 *
 * @author juanma
 */
public class FeedSelector {

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
    protected String selector;

    protected String validator;

    protected String format;

    public String getFormat() {
        return format;
    }

    public void setFormat(String format) {
        this.format = format;
    }


    public String getValidator() {
        return validator;
    }

    public void setValidator(String validator) {
        this.validator = validator;
    }

    /**
     * Get the value of selector
     *
     * @return the value of selector
     */
    public String getSelector() {
        return selector;
    }

    /**
     * Set the value of selector
     *
     * @param selector new value of selector
     */
    public void setSelector(String selector) {
        this.selector = selector;
    }

    public FeedSelector() {
    }

    public FeedSelector(String type, String selector, String validator, String format) {
        this.type = type;
        this.selector = selector;
        this.validator = validator;
        this.format = format;
    }

}
