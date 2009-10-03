/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import entities.JosSlots;

/**
 *
 * @author Nosotros
 */
public class GetConfigStruct {

    String nombre = "config";
    Range range;
    JosSlots[] slots;

    public GetConfigStruct(Range range, JosSlots[] slots) {
        this.range = range;
        this.slots = slots;
    }

}

