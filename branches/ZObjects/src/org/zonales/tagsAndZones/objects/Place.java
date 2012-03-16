/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.objects;

import java.util.List;

/**
 *
 * @author nacho
 */
public class Place extends Tag {

    private String address;
    private String description;    
    private String extendedString;
    private String geoData;
    private String image;
    private List links;
    private String zone;
    

    public Place() {
    }

    public Place(String name) {
        super(name);
    }

    public Place(int id, String name) {
        super(id, name);
    }

    @Override
    public String toString() {
        return "Name: " + super.getName() + " - Description: " + description + " - ExtendedString: " + extendedString;
    }

    public String getExtendedString() {
        return extendedString;
    }

    public void setExtendedString(String extendedString) {
        this.extendedString = extendedString;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getGeoData() {
        return geoData;
    }

    public void setGeoData(String geoData) {
        this.geoData = geoData;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public List getLinks() {
        return links;
    }

    public void setLinks(List links) {
        this.links = links;
    }

    public String getZone() {
        return zone;
    }

    public void setZone(String zone) {
        this.zone = zone;
    }    
}
