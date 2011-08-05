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

    public Tag(int id, String name, Tag parent, Type type, String state) {
        this.id = id;
        this.name = name;
        this.parent = parent;
        this.type = type;
        this.state = state;
    }

}
