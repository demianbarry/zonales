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

    

}
