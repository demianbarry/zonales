/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosResourcesGroup;
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

    public List<BaseEntity> getSlots() {
        if (selected != null) {
            Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
            queryParameters.put("groupId", ((JosResourcesGroup)selected).getGroupId());
            return findEntities("JosSlotsHasJosResourcesGroup.findByGroupId", queryParameters);
        }

        return null;
    }

    public List<BaseEntity> getResources() {
        if (selected != null) {
            Hashtable<String, Object> queryParameters = new Hashtable<String, Object>();
            queryParameters.put("group", (JosResourcesGroup)selected);
            return findEntities("JosResources.findByGroupId", queryParameters);
        }

        return null;
    }

    public void setSelected(int groupId) {

        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("groupId", groupId);
        super.setSelected(findEntities("JosResourcesGroup.findByGroupId", queryParameters).get(0));
    }



}
