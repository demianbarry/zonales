/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosSlots;
import java.util.Hashtable;
import java.util.List;

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
        System.out.println("-----> SlotId en Model: " + slotId);
        queryParameters.put("slotId", slotId);
        List<BaseEntity> result = findEntities("JosSlots.findBySlotId", queryParameters);
        System.out.println("----> Result: " + result.get(0).getPK());
        System.out.println("----> Tamañp lista: " + result.size());
        super.setSelected(result.get(0));
    }

    @Override
    public JosSlots getSelected() {
        return (JosSlots) super.getSelected();
    }

}
