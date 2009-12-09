/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package model;

import entities.BaseEntity;
import entities.JosSlotsHasJosResourcesGroup;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class SlotsHasResourcesGroupModel extends BaseModel {

    public SlotsHasResourcesGroupModel(boolean all) {
        super(JosSlotsHasJosResourcesGroup.class, all);
    }

    public List<BaseEntity> getSlot() {
        if (selected != null) {
            Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
            queryParameters.put("slotId", (Integer) selected.getPK());
            return findEntities("JosSlots.findBySlotId", queryParameters);
        }

        return null;
    }

    @Override
    public JosSlotsHasJosResourcesGroup getSelected() {
        return (JosSlotsHasJosResourcesGroup) super.getSelected();
    }
}
