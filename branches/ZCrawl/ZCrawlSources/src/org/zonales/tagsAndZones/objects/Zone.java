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
    private float ScenterLat;
    private float centerLon;
    private int zoomLevel;

    public Zone() {
    }

    public Zone(float ScenterLat, float centerLon, int zoomLevel) {
        this.ScenterLat = ScenterLat;
        this.centerLon = centerLon;
        this.zoomLevel = zoomLevel;
    }

    public Zone(int id, String name, Tag parent, Type type, String state, float ScenterLat, float centerLon, int zoomLevel) {
        this.ScenterLat = ScenterLat;
        this.centerLon = centerLon;
        this.zoomLevel = zoomLevel;
    }

    public float getScenterLat() {
        return ScenterLat;
    }

    public void setScenterLat(float ScenterLat) {
        this.ScenterLat = ScenterLat;
    }

    public float getCenterLon() {
        return centerLon;
    }

    public void setCenterLon(float centerLon) {
        this.centerLon = centerLon;
    }

    public int getZoomLevel() {
        return zoomLevel;
    }

    public void setZoomLevel(int zoomLevel) {
        this.zoomLevel = zoomLevel;
    }

    

}
