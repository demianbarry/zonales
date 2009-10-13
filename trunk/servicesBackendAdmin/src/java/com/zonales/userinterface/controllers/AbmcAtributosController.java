/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.userinterface.controllers;

import com.zonales.persistence.daos.exceptions.RollbackFailureException;
import com.zonales.persistence.entities.ClaseAtributo;
import com.zonales.persistence.entities.ValorPermitidoAtrcomp;
import com.zonales.persistence.models.BaseModel;
import com.zonales.persistence.models.ClaseAtributoModel;
import java.util.Iterator;
import java.util.List;
import java.util.StringTokenizer;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.transaction.SystemException;
import org.zkoss.zk.ui.event.Event;
import org.zkoss.zkex.zul.Columnchildren;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Button;
import org.zkoss.zul.Checkbox;
import org.zkoss.zul.Grid;
import org.zkoss.zul.Listbox;
import org.zkoss.zul.Listcell;
import org.zkoss.zul.Listitem;
import org.zkoss.zul.Messagebox;
import org.zkoss.zul.Panel;
import org.zkoss.zul.Textbox;

/**
 *
 * @author Cristian
 */
public class AbmcAtributosController extends BaseController {
    private ClaseAtributoModel atributoModel;

    protected  Listbox atributos;
    protected Button btnAceptar;
    protected Textbox nombre;
    protected Textbox descripcion;
    protected Textbox observaciones;
    protected Checkbox filtraPorPadre;
    protected Textbox qryFiltraPorPadre;
    protected Textbox qryLovExterna;
    protected Listbox tipo;
    protected Checkbox ecualizable;
    protected Checkbox obligatorio;
    protected Checkbox editar;
    protected Checkbox selValorPermitido;
    protected Columnchildren columnaValores;
    protected Listbox valores;
    protected Columnchildren editarValor;
    protected Textbox desde;
    protected Textbox hasta;
    protected Textbox descripcionValores;
    protected Textbox observacionesValores;

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
        ((ClaseAtributo) atributoModel.getAll().get(0)).getPK();
        atributos.getSelectedItem().getId();
    }

    public void onClick$btnAceptar(Event event){
        guardar();
    }

    private void guardar() {
        if (editar.isChecked()) {
            try {
                ClaseAtributo atributoActual = (ClaseAtributo) atributoModel.getSelected();
                atributoActual.setNombre(nombre.getValue());
                atributoActual.setDescripcion(descripcion.getValue());
                atributoActual.setEcualizable(ecualizable.isChecked());
                atributoActual.setFiltraXPadre(filtraPorPadre.isChecked());
                atributoActual.setObligatorio(obligatorio.isChecked());
                atributoActual.setObservaciones(observaciones.getValue());
                atributoActual.setQryFiltraXPadre(qryFiltraPorPadre.getValue());
                atributoActual.setQryLovExterna(qryLovExterna.getValue());
                atributoActual.setTipo((String) tipo.getSelectedItem().getValue());

                // TERMINAR !!!!!!!!!!!!1
                atributoActual.setValorPermitidoAtrcompList(null);

                BaseModel.editEntity(atributoActual, true);

                
            } catch (RollbackFailureException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            } catch (NamingException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            } catch (IllegalStateException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            } catch (SecurityException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            } catch (SystemException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            } catch (Exception ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                ex.printStackTrace();
            }

        }
    }


    public void onCheck$selValorPermitido(Event event){
        qryLovExterna.setVisible(selValorPermitido.isChecked());
        columnaValores.setVisible(!selValorPermitido.isChecked());
    }

    public void onClick$eliminarAtributo(Event event){
        try {
            int res = Messagebox.show("¿Esta seguro?", "Por favor confirme", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);

            // si el usuario confirma la accion
            if (res == Messagebox.OK){
                // elimino el  atributo y todos sus valores permitidos

                // hago invisible la entrada
            }

        } catch (InterruptedException ex) {
            Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            ex.printStackTrace();
        }
    }

    public void onClick$nuevoAtributo(Event event){
        // agrego una nueva fila con todos sus campos en blanco

        // hago visible el boton aceptar
    }

    public void onClick$editarAtributo(Event event){
        // hago editable todos los campos

        // hago visible el boton aceptar
    }

    public void onClick$nuevoValor(Event event){
        editarValor.setVisible(true);

        desde.setValue("");
        hasta.setValue("");
        observacionesValores.setValue("");
        descripcionValores.setValue("");
    }

    public void onClick$editarValor(Event event){
        // obtengo los datos del valor seleccionado
        ValorPermitidoAtrcomp valorActual;

        StringTokenizer tokens = new StringTokenizer(valores.getSelectedItem().getId(), "-");
        tokens.nextToken();
        int pk = Integer.parseInt(tokens.nextToken());

        valorActual = (ValorPermitidoAtrcomp) BaseModel.findEntityByPK(pk, ValorPermitidoAtrcomp.class);


        editarValor.setVisible(true);
        

        desde.setValue(valorActual.getValor());
        hasta.setValue(valorActual.getValorHasta());
        observacionesValores.setValue(valorActual.getObservaciones());
        descripcionValores.setValue(valorActual.getDescripcion());
    }

    public void onClick$eliminarValor(Event event){
        try {
            int res = Messagebox.show("¿Esta seguro?", "Por favor confirme", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);

            // si el usuario confirma la accion
            if (res == Messagebox.OK){
                // elimino el  valor 

                // hago invisible la entrada
            }

        } catch (InterruptedException ex) {
            Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            ex.printStackTrace();
        }
    }

    public void onClick$btnAceptarValor(Event event){
        // chequeo si es un insertar o actualizar

        // realizo la operacion

        // hago invisible el boton aceptar
    }

    public void onSelect$atributos(Event event){
        Integer pk;
        ClaseAtributo atributo;
        List<ValorPermitidoAtrcomp> valores;
        Iterator<Listitem> itItems;

        // recupero el la PK del atributo
        pk = Integer.parseInt(atributos.getSelectedItem().getId());

        // consulto a la base de datos por todos los valores asociados
        atributo = (ClaseAtributo) BaseModel.findEntityByPK(pk, ClaseAtributo.class);
        valores = atributo.getValorPermitidoAtrcompList();

        // completo la interfaz con los datos obtenidos
        nombre.setValue(atributo.getNombre());
        descripcion.setValue(atributo.getDescripcion());
        ecualizable.setChecked(atributo.isEcualizable());
        filtraPorPadre.setChecked(atributo.isFiltraXPadre());
        obligatorio.setChecked(atributo.isObligatorio());
        observaciones.setValue(atributo.getObservaciones());
        qryFiltraPorPadre.setValue(atributo.getQryFiltraXPadre());

        itItems = tipo.getItems().iterator();

        while (itItems.hasNext()){
            Listitem itemActual = itItems.next();
            if (((String) itemActual.getValue()).compareTo(atributo.getTipo())  == 0){
                tipo.setSelectedItem(itemActual);
                break;
            }
        }

        // si no se requiere una consulta a una tabla externa
        if (atributo.getQryLovExterna() == null){
            qryLovExterna.setVisible(false);
            selValorPermitido.setChecked(true);

            valores = atributo.getValorPermitidoAtrcompList();
            Iterator<ValorPermitidoAtrcomp> itValor = valores.iterator();

            while (itValor.hasNext()){
                ValorPermitidoAtrcomp valorActual = itValor.next();
                Listitem itemActual = new Listitem();

                itemActual.appendChild(new Listcell(valorActual.getValor()));
                itemActual.appendChild(new Listcell(valorActual.getValorHasta()));
                itemActual.appendChild(new Listcell(valorActual.getDescripcion()));
                itemActual.appendChild(new Listcell(valorActual.getObservaciones()));
                itemActual.setId("vp-" + valorActual.getId());

                this.valores.appendChild(itemActual);

            }
        }
        else {
            qryLovExterna.setValue(atributo.getQryLovExterna());
            qryLovExterna.setVisible(true);
            selValorPermitido.setChecked(false);
        }

    }

}