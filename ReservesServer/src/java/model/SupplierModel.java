/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosSuppliers;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author nacho
 */
public class SupplierModel extends BaseModel {

    public SupplierModel(boolean all) {
        super(JosSuppliers.class, all);
    }

    public void setSelected(int supplierId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("supplierId", supplierId);
        List<BaseEntity> result = findEntities("JosSuppliers.findBySupplierId", queryParameters);
        super.setSelected(result.get(0));
    }

    @Override
    public JosSuppliers getSelected() {
        return (JosSuppliers) super.getSelected();
    }

}
