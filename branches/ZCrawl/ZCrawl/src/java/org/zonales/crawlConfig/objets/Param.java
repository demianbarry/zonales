/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

/**
 *
 * @author nacho
 */
public class Param {
    private String name;
    private Boolean required;

    public Param() {
    }

    public Param(String name) {
        this.name = name;
    }

    public Param(String name, Boolean required) {
        this.name = name;
        this.required = required;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Boolean getRequired() {
        return required;
    }

    public void setRequired(Boolean required) {
        this.required = required;
    }

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Param other = (Param) obj;
        if ((this.name == null) ? (other.name != null) : !this.name.equals(other.name)) {
            return false;
        }
        /*if (this.required != other.required && (this.required == null || !this.required.equals(other.required))) {
            return false;
        }*/
        return true;
    }

    @Override
    public int hashCode() {
        int hash = 3;
        hash = 37 * hash + (this.name != null ? this.name.hashCode() : 0);
        hash = 37 * hash + (this.required != null ? this.required.hashCode() : 0);
        return hash;
    }

    @Override
    public String toString() {
        return "{\"name\": \"" + name + "\", \"required\": " + required + "}";
    }

}
