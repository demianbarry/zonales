/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.objects;

import java.util.ArrayList;
import org.zonales.crawlConfig.objets.State;

/**
 *
 * @author nacho
 */
public class Type {

    private String name;
    private ArrayList<String> parents;
    private String state;

    public Type() {
    }

    public Type(String name) {
        this.name = name;
    }

    public Type(String name, ArrayList<String> parents, String state) {
        this.name = name;
        this.parents = parents;
        this.state = state;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public ArrayList<String> getParents() {
        return parents;
    }

    public void setParents(ArrayList<String> parents) {
        this.parents = parents;
    }

    public String getState() {
        return state;
    }

    public void setState(String state) throws TypeNotPresentException {
        ArrayList<String> possiblesStates = State.getCrawlConfigStates();

        if (possiblesStates.indexOf(state) < 0) {
            throw new TypeNotPresentException(state, null);
        }

        this.state = state;
    }

    public void addParent(String parent){

        this.parents.add(parent);
    }
}
