/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.objects;

/**
 *
 * @author juanma
 */
public class SimpleTag {
    private int id;
    private String name;
    private int parent;
    private String type;
    private String state;

    public SimpleTag() {
    }

    public SimpleTag(String name) {
        this.name = name;
    }

    public SimpleTag(int id, String name) {
        this.id = id;
        this.name = name;
    }

    public SimpleTag(int id, String name, int parent, String type, String state) {
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

    public int getParent() {
        return parent;
    }

    public void setParent(int parent) {
        this.parent = parent;
    }

    public String getState() {
        return state;
    }

    public void setState(String state) {
        this.state = state;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }
}
