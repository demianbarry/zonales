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
    private ArrayList<Plugin> plugins;
    private String state;

    public Service() {
    }

    public Service(String name, String uri) {
        this.name = name;
        this.uri = uri;
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
        //TODO: Verificar que el parámetro no exista en el ArrayList
        if (this.params == null) {
            this.params = new ArrayList<Param>();
        }
        this.params.add(new Param(name, required));
    }

    public void removeParam(String name) {
        Param param = new Param(name);
        this.params.remove(this.params.indexOf(param));
    }

    public Param getParam(String name) {
        Param param = new Param(name);
        return this.params.get(this.params.indexOf(param));
    }

    public ArrayList<Plugin> getPlugins() {
        return plugins;
    }

    public void setPlugins(ArrayList<Plugin> plugins) {
        this.plugins = plugins;
    }

    public void addPlugin(String className, String type) throws TypeNotPresentException {
        ArrayList<String> possiblesTypes = PluginType.getPluginTypes();

        if (possiblesTypes.indexOf(type) < 0) {
            throw new TypeNotPresentException(type, null);
        }
        
        //TODO: Verificar que el plugin no exista en el ArrayList
        if (this.plugins == null) {
            this.plugins = new ArrayList<Plugin>();
        }

        this.plugins.add(new Plugin(className, type));
    }

    public void removePlugin(String className) {
        Plugin plugin = new Plugin(className);
        this.plugins.remove(this.plugins.indexOf(plugin));
    }

    public Plugin getplugin(String className) {
        Plugin plugin = new Plugin(className);
        return this.plugins.get(this.plugins.indexOf(plugin));
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

    @Override
    public String toString() {
        return "{\"name\": \"" + name + "\", \"uri\": \"" + uri + "\", \"params\": " + params + "}";
    }

}
