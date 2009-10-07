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

    String nombre = "config";
    Range range;
    List<Slots> slots = new ArrayList<Slots>();

    public GetConfigStruct(Range range) {
        this.range = range;
    }

    public void add(Slots slot) {
        this.slots.add(slot);
    }
    
}

