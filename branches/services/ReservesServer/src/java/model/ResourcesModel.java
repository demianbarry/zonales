/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosResources;
import java.util.Calendar;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class ResourcesModel extends BaseModel {

    public ResourcesModel(boolean all) {
        super(JosResources.class, all);
    }

    public void setSelected(int resourceId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("resourceId", resourceId);
        List<BaseEntity> result = findEntities("JosResources.findByResourceId", queryParameters);
        super.setSelected(result.get(0));
    }

    public List<BaseEntity> getReserves(Calendar date) {

        Calendar from = Calendar.getInstance();
        Calendar to = Calendar.getInstance();

        from.set(date.get(Calendar.YEAR),
                 date.get(Calendar.MONTH),
                 date.get(Calendar.DAY_OF_MONTH),
                 0, 0, 0);

        to.set(date.get(Calendar.YEAR),
               date.get(Calendar.MONTH),
               date.get(Calendar.DAY_OF_MONTH),
               23, 59, 59);

        if (selected != null) {
            Hashtable<String, Object> queryParameters = new Hashtable<String, Object>();
            queryParameters.put("resources", (JosResources)selected);
            queryParameters.put("from", from.getTime());
            queryParameters.put("to", to.getTime());
            return findEntities("JosReserveHasJosResources.findByResourceIdInDate", queryParameters);
            //return findEntities("JosReserveHasJosResources.findByResourceId", queryParameters);
        }

        return null;
    }

    @Override
    public JosResources getSelected() {
        return (JosResources) super.getSelected();
    }

}
