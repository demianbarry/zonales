/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

/**
 *
 * @author nacho
 */
public class OSMPlace {

    private String place_id;
    private String licence;
    private String osm_type;
    private String osm_id;
    private String lat;
    private String lon;
    private String display_name;
    private OSMAddress address;

    public OSMPlace() {
    }

    public OSMPlace(String place_id, String licence, String osm_type, String osm_id, String lat, String lon, String display_name, OSMAddress address) {
        this.place_id = place_id;
        this.licence = licence;
        this.osm_type = osm_type;
        this.osm_id = osm_id;
        this.lat = lat;
        this.lon = lon;
        this.display_name = display_name;
        this.address = address;
    }

    public OSMAddress getAddress() {
        return address;
    }

    public void setAddress(OSMAddress address) {
        this.address = address;
    }

    public String getDisplay_name() {
        return display_name;
    }

    public void setDisplay_name(String display_name) {
        this.display_name = display_name;
    }

    public String getLat() {
        return lat;
    }

    public void setLat(String lat) {
        this.lat = lat;
    }

    public String getLicence() {
        return licence;
    }

    public void setLicence(String licence) {
        this.licence = licence;
    }

    public String getLon() {
        return lon;
    }

    public void setLon(String lon) {
        this.lon = lon;
    }

    public String getOsm_id() {
        return osm_id;
    }

    public void setOsm_id(String osm_id) {
        this.osm_id = osm_id;
    }

    public String getOsm_type() {
        return osm_type;
    }

    public void setOsm_type(String osm_type) {
        this.osm_type = osm_type;
    }

    public String getPlace_id() {
        return place_id;
    }

    public void setPlace_id(String place_id) {
        this.place_id = place_id;
    }

    

}
