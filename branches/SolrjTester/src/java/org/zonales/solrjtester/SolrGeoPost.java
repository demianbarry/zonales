//
// This file was generated by the JavaTM Architecture for XML Binding(JAXB) Reference Implementation, vhudson-jaxb-ri-2.2-147 
// See <a href="http://java.sun.com/xml/jaxb">http://java.sun.com/xml/jaxb</a> 
// Any modifications to this file will be lost upon recompilation of the source schema. 
// Generated on: 2011.06.01 at 03:15:40 PM ART 
//
package org.zonales.solrjtester;

import org.apache.solr.client.solrj.beans.Field;

public class SolrGeoPost {
   
    @Field
    protected String id;

    @Field
    protected String text;

    @Field
    protected String[] location;

    public SolrGeoPost() {
    }

    public SolrGeoPost(String id, String text, String[] location) {
        this.id = id;
        this.text = text;
        this.location = location;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String[] getLocation() {
        return location;
    }

    public void setLocation(String[] location) {
        this.location = location;
    }

    

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }
    

}
