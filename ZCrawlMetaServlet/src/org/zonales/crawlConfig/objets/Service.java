/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

import java.util.ArrayList;

/**
 *
 * @author nacho
 */
public class Service {
    private String name;
    private String uri;
    private ArrayList<Param> params;

    public Service() {
    }

    public Service(String name, String uri) {
        this.name = name;
        this.uri = uri;
    }

    public Service(String name, String uri, ArrayList<Param> params) {
        this.name = name;
        this.uri = uri;
        this.params = params;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public ArrayList<Param> getParams() {
        return params;
    }

    public void setParams(ArrayList<Param> params) {
        this.params = params;
    }

    public String getUri() {
        return uri;
    }

    public void setUri(String uri) {
        this.uri = uri;
    }

    public void addParam(String name, Boolean required) {
        if (this.params == null) {
            this.params = new ArrayList<Param>();
        }
        this.params.add(new Param(name, required));
    }

    public void removeParam(String name) {
        Param param = new Param(name);
        this.params.remove(this.params.indexOf(param));
    }

    @Override
    public String toString() {
        return "{\"name\": \"" + name + "\", \"uri\": \"" + uri + "\", \"params\": " + params + "}";
    }

}
