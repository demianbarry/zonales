/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosResourcesGroup;
import java.util.Date;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class ResourceGroupModel extends BaseModel {

    public ResourceGroupModel() {
        super(JosResourcesGroup.class);
    }

    public List<BaseEntity> getSlots(Date from, Date to) {
        if (selected != null) {
            Hashtable<String, Object> queryParameters = new Hashtable<String, Object>();
            queryParameters.put("groupId", ((JosResourcesGroup)selected).getGroupId());
            queryParameters.put("from", from);
            queryParameters.put("to", to);
            return findEntities("JosSlotsHasJosResourcesGroup.findByGroupIdInRange", queryParameters);
        }

        return null;
    }

    public void setSelected(int groupId) {

        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("groupId", groupId);
        super.setSelected(findEntities("JosResourcesGroup.findByGroupId", queryParameters).get(0));
    }



}
