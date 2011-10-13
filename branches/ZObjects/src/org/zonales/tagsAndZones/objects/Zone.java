/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.tagsAndZones.objects;

/**
 *
 * @author nacho
 */
public class Zone extends Tag {
    private Double centerLat;
    private Double centerLon;
    private int zoomLevel;

    public Zone() {
    }

    public Zone(String name) {
        super(name);
    }
    
    public Zone(int id,String name) {
        super(id,name);
    }

    public Zone(Double ScenterLat, Double centerLon, int zoomLevel) {
        this.centerLat = ScenterLat;
        this.centerLon = centerLon;
        this.zoomLevel = zoomLevel;
    }

    public Zone(int id, String name, Tag parent, Type type, String state, Double ScenterLat, Double centerLon, int zoomLevel) {
        this.centerLat = ScenterLat;
        this.centerLon = centerLon;
        this.zoomLevel = zoomLevel;
    }

    public Double getCenterLat() {
        return centerLat;
    }

    public void setCenterLat(Double ScenterLat) {
        this.centerLat = ScenterLat;
    }

    public Double getCenterLon() {
        return centerLon;
    }

    public void setCenterLon(Double centerLon) {
        this.centerLon = centerLon;
    }

    public int getZoomLevel() {
        return zoomLevel;
    }

    public void setZoomLevel(int zoomLevel) {
        this.zoomLevel = zoomLevel;
    }

    @Override
    public String toString() {
        return "Name: " + super.getName() + " - CenterLat: " + centerLat + " - CenterLon: " + centerLon;
    }

    

}
