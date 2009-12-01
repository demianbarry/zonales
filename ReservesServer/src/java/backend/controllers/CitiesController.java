/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package backend.controllers;

import entities.JosCities;
import exceptions.ExceptionsController;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.persistence.EntityNotFoundException;
import javax.swing.JOptionPane;
import model.CityModel;
import org.zkoss.zk.ui.event.ForwardEvent;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Groupbox;
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
    Button newCityButton;
    Button editCityButton;
    Button deleteCityButton;
    Button aceptarButton;
    Button cancelarButton;
    Groupbox editableArea;
    Groupbox buttonArea;
    Textbox zipCode;
    Textbox city;
    Textbox province;
    Boolean buttons = true;

    public CitiesController() {
        super(false);
        cityModel = new CityModel();
        System.out.println("----->" + ((JosCities) cityModel.getAll().get(0)).getCity());
    }

    public void onCreate$citiesWindow(ForwardEvent event) {
        try {
            // Obtengo el DataBinder que instancia la página
            binder = (DataBinder) getAttribute("binder", true);
            refreshList();
        } catch (Exception ex) {
            Logger.getLogger(CitiesController.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void onSelect$citiesList(ForwardEvent event) {
        zipCode.setValue(cityModel.getSelected().getZipCode());
        city.setValue(cityModel.getSelected().getCity());
        province.setValue(cityModel.getSelected().getProvince());
        refreshEditableArea();

    }

    public void onClick$editCityButton(ForwardEvent event) {
        setEditMode(true);
        disableTextboxs(false);
        changeButtons();
        zipCode.setFocus(true);
    }

    public void onClick$newCityButton(ForwardEvent event) {
        setEditMode(false);
        disableTextboxs(false);
        zipCode.setValue("");
        city.setValue("");
        province.setValue("");
        changeButtons();
        zipCode.setFocus(true);
    }

    public void onClick$deleteCityButton(ForwardEvent event) {
        try {
            int option = JOptionPane.showConfirmDialog(null, "Eliminar localidad?", "Eliminar", JOptionPane.YES_NO_OPTION);
            if (option == 0) {
                cityModel.delete(true);
            }
            clearEditableArea();
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
                int option = JOptionPane.showConfirmDialog(null, "Guardar?", "Nueva", JOptionPane.YES_NO_OPTION);
                if (option == 0) {
                    cityModel.getSelected().setCity(city.getValue());
                    cityModel.getSelected().setProvince(province.getValue());
                    cityModel.getSelected().setZipCode(zipCode.getValue());
                    cityModel.merge(true);
                }
            } else {
                int option = JOptionPane.showConfirmDialog(null, "Guardar?", "Editar", JOptionPane.YES_NO_OPTION);
                if (option == 0) {
                    JosCities josCities = new JosCities(null, city.getValue(), zipCode.getValue(), province.getValue());
                    cityModel.setSelected(josCities);
                    //cityModel.getSelected().setCity(city.getValue());
                    //cityModel.getSelected().setProvince(province.getValue());
                    //cityModel.getSelected().setZipCode(zipCode.getValue());
                    cityModel.persist(true);
                }
            }
            clearEditableArea();
            changeButtons();
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
        binder.loadComponent(editableArea); //reload visible to force refresh
        binder.loadAttribute(editableArea, "model"); //reload model to force refresh
    }

    public void clearEditableArea() {
        city.setValue("");
        zipCode.setValue("");
        province.setValue("");
        refreshEditableArea();
    }

    public void refreshButtonArea() {
        cityModel.refreshAll();
        binder.loadComponent(buttonArea); //reload visible to force refresh
        binder.loadAttribute(buttonArea, "model"); //reload model to force refresh
    }

    public CityModel getCityModel() {
        return cityModel;
    }

    public void setCityModel(CityModel cityModel) {
        this.cityModel = cityModel;
    }

    private void changeButtons() {
        editCityButton.setVisible(!buttons);
        newCityButton.setVisible(!buttons);
        deleteCityButton.setVisible(!buttons);
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
