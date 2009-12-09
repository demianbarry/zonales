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

    public SlotsModel(boolean all) {
        super(JosSlots.class, all);
    }

    public void setSelected(int slotId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("slotId", slotId);
        List<BaseEntity> result = findEntities("JosSlots.findBySlotId", queryParameters);
        super.setSelected(result.get(0));
    }

    @Override
    public JosSlots getSelected() {
        return (JosSlots) super.getSelected();
    }

}
