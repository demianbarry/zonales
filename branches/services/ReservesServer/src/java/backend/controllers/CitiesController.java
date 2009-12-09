/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package backend.controllers;

import entities.JosCities;
import exceptions.ExceptionsController;
import javax.persistence.EntityNotFoundException;
import model.CityModel;
import org.zkoss.zk.ui.event.ForwardEvent;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Groupbox;
import org.zkoss.zul.Messagebox;
import org.zkoss.zul.api.Button;
import org.zkoss.zul.api.Listbox;
import org.zkoss.zul.api.Textbox;

/**
 *
 * @author nacho
 */
public class CitiesController extends BaseController {

    CityModel cityModel = null;
    Listbox citiesList;
    Button newButton;
    Button editButton;
    Button deleteButton;
    Button aceptarButton;
    Button cancelarButton;
    Groupbox editableArea;
    Groupbox buttonArea;
    Textbox zipCode;
    Textbox city;
    Textbox province;
    Boolean buttons = true;
    int selectedItem;

    public CitiesController() {
        super(false);
        cityModel = new CityModel(true);
    }

    public void onCreate$citiesWindow(ForwardEvent event) {
        try {
            // Obtengo el DataBinder que instancia la página
            binder = (DataBinder) getVariable("binder", true);
            //binder = (DataBinder) getAttribute("binder");
            refreshList();
        } catch (Exception ex) {
            ExceptionsController.exceptionCatch(ex);
        }
    }

    public void onSelect$citiesList(ForwardEvent event) {
        if (!newButton.isVisible()) {
            citiesList.setSelectedIndex(selectedItem);
        } else {
            selectedItem = citiesList.getSelectedIndex();
            editButton.setDisabled(false);
            deleteButton.setDisabled(false);
            zipCode.setValue(cityModel.getSelected().getZipCode());
            city.setValue(cityModel.getSelected().getCity());
            province.setValue(cityModel.getSelected().getProvince());
            refreshEditableArea();
        }
    }

    public void onClick$editButton(ForwardEvent event) {
        setEditMode(true);
        disableTextboxs(false);
        changeButtons();
        zipCode.setFocus(true);
    }

    public void onClick$newButton(ForwardEvent event) {
        setEditMode(false);
        editButton.setDisabled(false);
        deleteButton.setDisabled(false);
        disableTextboxs(false);
        zipCode.setValue("");
        city.setValue("");
        province.setValue("");
        changeButtons();
        zipCode.setFocus(true);
    }

    public void onClick$deleteButton(ForwardEvent event) {
        try {
            int option = Messagebox.show("Eliminar localidad?", "Eliminar", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);
            if (option == Messagebox.OK) {
                cityModel.delete(true);
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
                cityModel.getSelected().setCity(city.getValue());
                cityModel.getSelected().setProvince(province.getValue());
                cityModel.getSelected().setZipCode(zipCode.getValue());
                cityModel.merge(true);
            } else {
                JosCities josCities = new JosCities(null, city.getValue(), zipCode.getValue(), province.getValue());
                cityModel.setSelected(josCities);
                cityModel.persist(true);
            }
            //clearEditableArea();
            changeButtons();
            disableTextboxs(true);
            refreshList();
        } catch (Exception ex) {
            ExceptionsController.exceptionCatch(ex);
        }

    }

    public void onClick$cancelarButton(ForwardEvent event) {
        disableTextboxs(true);
        setEditMode(false);
        zipCode.setValue(cityModel.getSelected().getZipCode());
        city.setValue(cityModel.getSelected().getCity());
        province.setValue(cityModel.getSelected().getProvince());
        changeButtons();
        refreshEditableArea();
    }

    public void refreshList() {
        cityModel.refreshAll();
        binder.loadComponent(citiesList); //reload visible to force refresh
        binder.loadAttribute(citiesList, "model"); //reload model to force refresh
    }

    public void refreshEditableArea() {
        cityModel.refreshAll();
    }

    public void refreshButtonArea() {
        cityModel.refreshAll();
    }

    public void clearEditableArea() {
        city.setValue("");
        zipCode.setValue("");
        province.setValue("");
        refreshEditableArea();
    }

    public CityModel getCityModel() {
        return cityModel;
    }

    public void setCityModel(CityModel cityModel) {
        this.cityModel = cityModel;
    }

    private void changeButtons() {
        editButton.setVisible(!buttons);
        newButton.setVisible(!buttons);
        deleteButton.setVisible(!buttons);
        aceptarButton.setVisible(buttons);
        cancelarButton.setVisible(buttons);
        buttons = !buttons;
        refreshButtonArea();
    }

    public void disableTextboxs(Boolean value) {
        zipCode.setDisabled(value);
        city.setDisabled(value);
        province.setDisabled(value);
    }
}
