/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosSlots;
import java.util.Hashtable;

/**
 *
 * @author Nos
 */
public class SlotsModel extends BaseModel {

    public SlotsModel() {
        super(JosSlots.class);
    }

    public void setSelected(int slotId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("slotId", slotId);
        this.setSelected((BaseEntity) findEntities("JosSlots.findBySlotId", queryParameters));
    }

    @Override
    public JosSlots getSelected() {
        return this.getSelected();
    }

}
