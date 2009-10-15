/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosReserve;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class ReserveModel extends BaseModel {

    public ReserveModel() {
        super(JosReserve.class);
    }

    public void setSelected(int reserveId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("reserveId", reserveId);
        List<BaseEntity> result = findEntities("JosReserve.findByReserveId", queryParameters);
        super.setSelected(result.get(0));
    }

    @Override
    public JosReserve getSelected() {
        return (JosReserve) super.getSelected();
    }

}
