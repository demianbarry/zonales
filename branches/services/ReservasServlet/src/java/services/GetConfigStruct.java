/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class GetConfigStruct {

    String name = "config";
    Range range;
    List<Slot> slots = new ArrayList<Slot>();

    public GetConfigStruct(Range range) {
        this.range = range;
    }

    public void add(Slot slot) {
        this.slots.add(slot);
    }
    
}

