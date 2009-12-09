/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosReserveHasJosResources;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class ReservesHasResourcesModel extends BaseModel {

    public ReservesHasResourcesModel(boolean all) {
        super(JosReserveHasJosResources.class, all);
    }

    public List<BaseEntity> getReserves() {
        if (selected != null) {
            Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
            queryParameters.put("reserveId", (Integer) selected.getPK());
            return findEntities("JosReserve.findByReserveId", queryParameters);
        }

        return null;
    }

    @Override
    public JosReserveHasJosResources getSelected() {
        return (JosReserveHasJosResources) super.getSelected();
    }

}
