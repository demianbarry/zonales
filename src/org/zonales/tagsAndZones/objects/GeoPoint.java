/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.objects;

/**
 *
 * @author rodrigo
 */
public class GeoPoint {

    private String street_name;
    private int street_number;
    private float longitude;
    private float latitude;
    private String ext_zip_code;

    public GeoPoint() {
    }

    public GeoPoint(String street_name, int street_number, float longitude, float latitude, String ext_zip_code) {
        this.street_name = street_name;
        this.street_number = street_number;
        this.longitude = longitude;
        this.latitude = latitude;
        this.ext_zip_code = ext_zip_code;
    }

    public String getExt_zip_code() {
        return ext_zip_code;
    }

    public void setExt_zip_code(String ext_zip_code) {
        this.ext_zip_code = ext_zip_code;
    }

    public float getLatitude() {
        return latitude;
    }

    public void setLatitude(float latitude) {
        this.latitude = latitude;
    }

    public float getLongitude() {
        return longitude;
    }

    public void setLongitude(float longitude) {
        this.longitude = longitude;
    }

    public String getStreet_name() {
        return street_name;
    }

    public void setStreet_name(String street_name) {
        this.street_name = street_name;
    }

    public int getStreet_number() {
        return street_number;
    }

    public void setStreet_number(int street_number) {
        this.street_number = street_number;
    }


}
