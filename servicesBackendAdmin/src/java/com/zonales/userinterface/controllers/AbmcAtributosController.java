/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.userinterface.controllers;

import com.zonales.persistence.entities.ClaseAtributo;
import com.zonales.persistence.models.ClaseAtributoModel;
import org.zkoss.zk.ui.event.Event;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Button;
import org.zkoss.zul.Grid;

/**
 *
 * @author Cristian
 */
public class AbmcAtributosController extends BaseController {
    private ClaseAtributoModel atributoModel;

    private Grid atributos;
    private Button btnAceptar;

    public AbmcAtributosController(){
        super(false);
        atributoModel = new ClaseAtributoModel();
    }

    public ClaseAtributoModel getAtributoModel() {
        return atributoModel;
    }

    public void setAtributoModel(ClaseAtributoModel atributoModel) {
        this.atributoModel = atributoModel;
    }

    public void onCreate$abmcAtributosWin(Event event) {
        // Obtengo el DataBinder que instancia la página
        binder = (DataBinder) getVariable("binder", true);

//        final List model = (List) atributos.getModel();
//
//        if (!model.isEmpty()) {
//            // si el model no está vacio, selecciono el primer registro y cargo el detalle
//            atributoModel.setSelected((ClaseAtributo) model.get(0));
//            binder.loadComponent(rolesDetail);
//        }
    }

    public void onClick$btnAceptar(Event event){

    }

}