/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.solrjtester;

/**
 *
 * @author nacho
 */
public class OSMAddress {

    private String road;
    private String suburb;
    private String town;
    private String city;
    private String state;
    private String country;
    private String country_code;

    public OSMAddress() {
    }

    public OSMAddress(String road, String suburb, String town, String city, String state, String country, String country_code) {
        this.road = road;
        this.suburb = suburb;
        this.town = town;
        this.city = city;
        this.state = state;
        this.country = country;
        this.country_code = country_code;
    }

    public String getState() {
        return state;
    }

    public void setState(String state) {
        this.state = state;
    }

    public String getTown() {
        return town;
    }

    public void setTown(String town) {
        this.town = town;
    }

    

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getCountry() {
        return country;
    }

    public void setCountry(String country) {
        this.country = country;
    }

    public String getCountry_code() {
        return country_code;
    }

    public void setCountry_code(String country_code) {
        this.country_code = country_code;
    }

    public String getRoad() {
        return road;
    }

    public void setRoad(String road) {
        this.road = road;
    }

    public String getSuburb() {
        return suburb;
    }

    public void setSuburb(String suburb) {
        this.suburb = suburb;
    }

    

}
