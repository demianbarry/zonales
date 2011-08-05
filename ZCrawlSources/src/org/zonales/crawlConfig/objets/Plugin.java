/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

/**
 *
 * @author nacho
 */
public class Plugin {
    private String className;
    private String type;

    public Plugin() {
    }

    public Plugin(String className) {
        this.className = className;
    }

    public Plugin(String className, String type) {
        this.className = className;
        this.type = type;
    }

    public String getClassName() {
        return className;
    }

    public void setClassName(String className) {
        this.className = className;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Plugin other = (Plugin) obj;
        if ((this.className == null) ? (other.className != null) : !this.className.equals(other.className)) {
            return false;
        }
        return true;
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 23 * hash + (this.className != null ? this.className.hashCode() : 0);
        return hash;
    }

    @Override
    public String toString() {
        return "Plugin{" + "className=" + className + "type=" + type + '}';
    }

}
