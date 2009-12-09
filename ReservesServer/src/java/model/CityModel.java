/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosCities;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author nacho
 */
public class CityModel extends BaseModel {

    public CityModel(boolean all) {
        super(JosCities.class, all);
    }

    public void setSelected(int cityId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("cityId", cityId);
        List<BaseEntity> result = findEntities("JosCities.findByCityId", queryParameters);
        super.setSelected(result.get(0));
    }


    @Override
    public JosCities getSelected() {
        return (JosCities) super.getSelected();
    }


}
