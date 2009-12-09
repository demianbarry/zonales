/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package model;

import entities.BaseEntity;
import entities.JosPhones;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author nacho
 */
public class PhoneModel extends BaseModel {

    protected List<BaseEntity> selection;
    protected List<BaseEntity> deletedPhones = new ArrayList<BaseEntity>();
    protected List<BaseEntity> newPhones = new ArrayList<BaseEntity>();;

    public PhoneModel(boolean all) {
        super(JosPhones.class, all);
    }

    public void setSelected(int phoneId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("phoneId", phoneId);
        List<BaseEntity> result = findEntities("JosPhones.findByPhoneId", queryParameters);
        super.setSelected(result.get(0));
    }

    public List<BaseEntity> getSupplierPhones(int supplierId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("supplierId", supplierId);
        return findEntities("JosPhones.findBySupplierId", queryParameters);
    }

    public void setSupplierPhones(int supplierId) {
        Hashtable<String, Integer> queryParameters = new Hashtable<String, Integer>();
        queryParameters.put("supplierId", supplierId);
        setPhonesSelection(findEntities("JosPhones.findBySupplierId", queryParameters));
    }

    public void setPhonesSelection(List<BaseEntity> selectedEntities) {
        if (Comparable.class.isAssignableFrom(getEntity())) {
            List entities = selectedEntities;
            Collections.sort(entities);
        }
        selection = selectedEntities;
    }

    public List<BaseEntity> getPhonesSelection() {
        return selection;
    }

    public void addToPhonesSelection (JosPhones phone) {
        selection.add(phone);
    }

    public void removeFromPhonesSelection (JosPhones phone) {
        selection.remove(phone);
    }
    
    public void clearPhonesSelection() {
        selection.clear();
    }

    public List<BaseEntity> getNewPhones() {
        return newPhones;
    }

    public void addToNewPhones (JosPhones phone) {
        newPhones.add(phone);
    }

    public void removeFromNewPhones (JosPhones phone) {
        newPhones.remove(phone);
    }

    public void clearNewPhones() {
        newPhones.clear();
    }

    public List<BaseEntity> getDeletedPhones() {
        return deletedPhones;
    }

    public void addToDeletedPhones (JosPhones phone) {
        deletedPhones.add(phone);
    }

    public void removeFromDeletedPhones (JosPhones phone) {
        deletedPhones.remove(phone);
    }

    public void clearDeletedPhones() {
        deletedPhones.clear();
    }

    @Override
    public JosPhones getSelected() {
        return (JosPhones) super.getSelected();
    }

}
