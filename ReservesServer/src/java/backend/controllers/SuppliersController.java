/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package backend.controllers;

import entities.BaseEntity;
import entities.JosCities;
import entities.JosPhones;
import entities.JosSuppliers;
import exceptions.ExceptionsController;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.persistence.EntityNotFoundException;
import model.CityModel;
import model.PhoneModel;
import model.SupplierModel;
import org.zkoss.zhtml.Messagebox;
import org.zkoss.zk.ui.event.ForwardEvent;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Combobox;
import org.zkoss.zul.Comboitem;
import org.zkoss.zul.api.Button;
import org.zkoss.zul.Groupbox;
import org.zkoss.zul.Listbox;
import org.zkoss.zul.Textbox;

/**
 *
 * @author nacho
 */
public class SuppliersController extends BaseController {

    SupplierModel supplierModel = null;
    CityModel cityModel = null;
    PhoneModel phoneModel = null;
    Listbox suppliersList;
    Listbox phonesList;
    Button newButton;
    Button editButton;
    Button deleteButton;
    Button aceptarButton;
    Button cancelarButton;
    Button addPhoneButton;
    Button removePhoneButton;
    Groupbox editableArea;
    Groupbox buttonArea;
    Textbox name;
    Textbox street;
    Textbox number;
    Textbox city;
    Textbox phone;
    Combobox cities;
    Boolean buttons = true;
    List<BaseEntity> citiesList;
    int selectedItem;

    public SuppliersController() {
        super(false);
        supplierModel = new SupplierModel(true);
        cityModel = new CityModel(false);
        phoneModel = new PhoneModel(false);
    }

    public void onCreate$suppliersWindow(ForwardEvent event) {
        try {
            // Obtengo el DataBinder que instancia la página
            binder = (DataBinder) getVariable("binder", true);
            //binder = (DataBinder) getAttribute("binder");
            final List model = (List) suppliersList.getModel();

            if (!model.isEmpty()) {
                // si el model no está vacio, selecciono el primer registro y cargo el detalle
                supplierModel.setSelected((JosSuppliers) model.get(0));
                supplierSelect();
                refreshList();
            }
        } catch (Exception ex) {
            ExceptionsController.exceptionCatch(ex);
        }
    }

    public void onFocus$suppliersWindow (ForwardEvent event) {
        refreshCitiesList();
    }

    public void onFocus$cities (ForwardEvent event) {
        refreshCitiesList();
    }

    public void onSelect$suppliersList(ForwardEvent event) {
        supplierSelect();

    }

    public void onClick$editButton(ForwardEvent event) {
        setEditMode(true);
        refreshCitiesList();
        //cities.setSelectedIndex(citiesList.indexOf(supplierModel.getSelected()));
        //cities.setSelectedItem(cities.getItemAtIndex(citiesList.indexOf(supplierModel.getSelected())));
        cities.setValue(supplierModel.getSelected().getCityId().getCity());
        disableTextboxs(false);
        changeButtons();
        name.setFocus(true);
    }

    public void onClick$newButton(ForwardEvent event) {
        setEditMode(false);
        disableTextboxs(false);
        name.setValue("");
        cities.setValue("");
        street.setValue("");
        number.setValue("");
        changeButtons();
        name.setFocus(true);
    }

    public void onClick$deleteButton(ForwardEvent event) {
        try {
            int option = Messagebox.show("Eliminar Proveedor?", "Eliminar", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);
            if (option == Messagebox.OK) {
                supplierModel.delete(true);
            }
            clearEditableArea();
            editButton.setDisabled(true);
            deleteButton.setDisabled(true);
            refreshList();
        } catch (EntityNotFoundException ex) {
            ExceptionsController.exceptionCatch(ex);
        } catch (Exception ex) {
            ExceptionsController.exceptionCatch(ex);
        }
    }

    public void onClick$aceptarButton(ForwardEvent event) {
        try {
            if (isEditMode()) {
                supplierModel.getSelected().setName(name.getValue());
                supplierModel.getSelected().setStreet(street.getValue());
                supplierModel.getSelected().setNumber(Integer.valueOf(number.getValue()));
                if (cities.getSelectedIndex() >= 0) {
                    supplierModel.getSelected().setCityId((JosCities)citiesList.get(cities.getSelectedIndex()));
                }
                supplierModel.merge(true);
            } else {
                JosSuppliers josSupplier = new JosSuppliers(null, name.getValue(), street.getValue(), Integer.valueOf(number.getValue()),(JosCities)citiesList.get(cities.getSelectedIndex()));
                supplierModel.setSelected(josSupplier);
                supplierModel.persist(true);
            }
            city.setValue(supplierModel.getSelected().getCityId().getCity());
            if (phoneModel.getNewPhones().size() != 0) {
                List <BaseEntity> newPhones = phoneModel.getNewPhones();
                for (int i = 0; i < newPhones.size(); i++) {
                    phoneModel.setSelected(newPhones.get(i));
                    phoneModel.persist(true);
                }
            }
            if (phoneModel.getDeletedPhones().size() != 0) {
                List <BaseEntity> deletedPhones = phoneModel.getDeletedPhones();
                for (int i = 0; i < deletedPhones.size(); i++) {
                    phoneModel.setSelected(deletedPhones.get(i));
                    phoneModel.delete(true);
                }
            }
            changeButtons();
            disableTextboxs(true);
            phone.setValue("");
            phoneModel.clearNewPhones();
            phoneModel.clearDeletedPhones();
            refreshList();
        } catch (Exception ex) {
            ExceptionsController.exceptionCatch(ex);
        }

    }


    public void onClick$cancelarButton(ForwardEvent event) {
        disableTextboxs(true);
        if (isEditMode()) {
            name.setValue(supplierModel.getSelected().getName());
            street.setValue(supplierModel.getSelected().getStreet());
            number.setValue(supplierModel.getSelected().getNumber().toString());
            city.setValue(supplierModel.getSelected().getCityId().getCity());
        }
        setEditMode(false);
        phone.setValue("");
        phoneModel.clearNewPhones();
        phoneModel.clearDeletedPhones();
        changeButtons();
        refreshEditableArea();
    }

    public void onClick$addPhoneButton(ForwardEvent event) {
        if (phone.getValue().compareTo("") != 0) {
            JosPhones addPhone = new JosPhones(null, supplierModel.getSelected(), phone.getValue());
            phoneModel.addToNewPhones(addPhone);
            phoneModel.addToPhonesSelection(addPhone);
            refreshPhonesList();
        } else {
            try {
                Messagebox.show("Debe ingresar un teléfono", "Error", Messagebox.OK, Messagebox.ERROR);
            } catch (InterruptedException ex) {
                Logger.getLogger(SuppliersController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        phone.setValue("");
    }

    public void onClick$removePhoneButton(ForwardEvent event) {
        phoneModel.addToDeletedPhones(phoneModel.getSelected());
        phoneModel.removeFromPhonesSelection(phoneModel.getSelected());
        refreshPhonesList();
    }

    public void refreshList() {
        supplierModel.refreshAll();
        binder.loadComponent(suppliersList); //reload visible to force refresh
        binder.loadAttribute(suppliersList, "model"); //reload model to force refresh
    }

    public void refreshEditableArea() {
        supplierModel.refreshAll();
    }

    public void refreshButtonArea() {
        supplierModel.refreshAll();
    }

    public void refreshCitiesList() {
        cityModel.refreshAll();
        cities.getChildren().clear();
        citiesList = cityModel.findEntities();
        for (int i = 0; i < citiesList.size(); i++) {
            cities.appendItem(((JosCities)citiesList.get(i)).getCity());
        }
        binder.loadComponent(cities); //reload visible to force refresh
        binder.loadAttribute(cities, "model"); //reload model to force refresh
    }

    public void refreshPhonesList() {
        phoneModel.refreshAll();
        binder.loadComponent(phonesList); //reload visible to force refresh
        binder.loadAttribute(phonesList, "model"); //reload model to force refresh
    }

    public void clearEditableArea() {
        city.setValue("");
        street.setValue("");
        number.setValue("");
        name.setValue("");
        cities.setValue("");
        refreshEditableArea();
    }

    public CityModel getCityModel() {
        return cityModel;
    }

    public void setCityModel(CityModel cityModel) {
        this.cityModel = cityModel;
    }

    public SupplierModel getSupplierModel() {
        return supplierModel;
    }

    public PhoneModel getPhoneModel() {
        return phoneModel;
    }

    public void setPhoneModel(PhoneModel phoneModel) {
        this.phoneModel = phoneModel;
    }

    public void setSupplierModel(SupplierModel supplierModel) {
        this.supplierModel = supplierModel;
    }

    private void changeButtons() {
        editButton.setVisible(!buttons);
        newButton.setVisible(!buttons);
        deleteButton.setVisible(!buttons);
        aceptarButton.setVisible(buttons);
        cancelarButton.setVisible(buttons);
        addPhoneButton.setDisabled(!buttons);
        removePhoneButton.setDisabled(!buttons);
        buttons = !buttons;
        refreshButtonArea();
    }

    private void disableTextboxs(Boolean value) {
        name.setDisabled(value);
        street.setDisabled(value);
        number.setDisabled(value);
        city.setDisabled(value);
        cities.setDisabled(value);
        phone.setDisabled(value);
        city.setVisible(value);
        phonesList.setDisabled(value);
        cities.setVisible(!value);
        refreshCitiesList();
    }

    private void supplierSelect() {
        if (!newButton.isVisible()) {
            suppliersList.setSelectedIndex(selectedItem);
        } else {
            selectedItem = suppliersList.getSelectedIndex();
            editButton.setDisabled(false);
            deleteButton.setDisabled(false);
            name.setValue(supplierModel.getSelected().getName());
            street.setValue(supplierModel.getSelected().getStreet());
            number.setValue(supplierModel.getSelected().getNumber().toString());
            city.setValue(supplierModel.getSelected().getCityId().getCity());
            phoneModel.setSupplierPhones(supplierModel.getSelected().getSupplierId());
            refreshPhonesList();
            refreshEditableArea();
        }
    }

}
