/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.tagsAndZones.objects;

import org.zonales.crawlConfig.objets.State;

/**
 *
 * @author nacho
 */
public class Tag {
    private int id;
    private String name;
    private Tag parent;
    private Type type;
    private String state;

    public Tag() {
    }

    public Tag(String name) {
        this.name = name;
    }

    public Tag(int id, String name, Tag parent, Type type, String state) {
        this.id = id;
        this.name = name;
        this.parent = parent;
        this.type = type;
        this.state = state;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Tag getParent() {
        return parent;
    }

    public void setParent(Tag parent) {
        this.parent = parent;
    }

    public String getState() {
        return state;
    }

    public void setState(String state) {
        this.state = state;
    }

    public Type getType() {
        return type;
    }

    public void setType(Type type) {
        this.type = type;
    }

    
}
