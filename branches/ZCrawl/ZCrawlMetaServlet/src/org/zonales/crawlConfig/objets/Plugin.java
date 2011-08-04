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

}
