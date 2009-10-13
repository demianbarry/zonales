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
import org.zkoss.zul.Label;
import org.zkoss.zul.Listbox;
import org.zkoss.zul.Listcell;
import org.zkoss.zul.Listitem;
import org.zkoss.zul.Messagebox;
import org.zkoss.zul.Textbox;

/**
 *
 * @author Cristian
 */
public class AbmcAtributosController extends BaseController {

    private ClaseAtributoModel atributoModel;
    protected Listbox atributos;
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
    protected Label atribPK;
    protected Label valorPK;

    private enum Accion {

        CONSULTAR, EDITAR, CREAR, ELIMINAR
    }
    private Accion accionValor;
    private Accion accionAtributo;

    public AbmcAtributosController() {
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

    public void onClick$btnAceptar(Event event) {
        guardar();
    }

    private void guardar() {
        if (accionAtributo == Accion.CONSULTAR) {
            try {
                Messagebox.show("Por favor, seleccione primero editar o nuevo");
                return;
            } catch (InterruptedException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }


        try {
            ClaseAtributo atributoActual = new ClaseAtributo();
            atributoActual.setId(new Integer(atribPK.getValue()));
            atributoActual.setNombre(nombre.getValue());
            atributoActual.setDescripcion(descripcion.getValue());
            atributoActual.setEcualizable(ecualizable.isChecked());
            atributoActual.setFiltraXPadre(filtraPorPadre.isChecked());
            atributoActual.setObligatorio(obligatorio.isChecked());
            atributoActual.setObservaciones(observaciones.getValue());
            atributoActual.setQryFiltraXPadre(qryFiltraPorPadre.getValue());
            atributoActual.setQryLovExterna(qryLovExterna.getValue());
            atributoActual.setTipo((String) tipo.getSelectedItem().getValue());

            // chequeo si es un insertar o actualizar
            switch (accionAtributo) {
                case CREAR:
                    BaseModel.createEntity(atributoActual, true);
                    break;
                case EDITAR:
                    BaseModel.editEntity(atributoActual, true);
                    break;
                case ELIMINAR:
                    BaseModel.deleteEntity(atributoActual, true);
                    break;
                default:
                    break;
            }


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

    public void onCheck$selValorPermitido(Event event) {
        qryLovExterna.setVisible(selValorPermitido.isChecked());
        columnaValores.setVisible(!selValorPermitido.isChecked());
    }

    public void onClick$eliminarAtributo(Event event) {
        try {
            int res = Messagebox.show("¿Esta seguro?", "Por favor confirme", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);

            // si el usuario confirma la accion
            if (res == Messagebox.OK) {
                // elimino el  atributo y todos sus valores permitidos
                accionAtributo = Accion.ELIMINAR;
                onClick$btnAceptar(event);

                // hago invisible la entrada
                atributos.getSelectedItem().setVisible(false);
                atributos.setSelectedIndex(0);
            }

        } catch (InterruptedException ex) {
            Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            ex.printStackTrace();
        }
    }

    public void onClick$nuevoAtributo(Event event) {
        nombre.setValue("");
        descripcion.setValue("");
        observaciones.setValue("");
        filtraPorPadre.setChecked(false);
        qryFiltraPorPadre.setValue("");
        selValorPermitido.setChecked(true);
        qryLovExterna.setValue("");
        tipo.setSelectedIndex(0);
        ecualizable.setChecked(true);
        obligatorio.setChecked(false);
        atribPK.setValue("0");

        accionAtributo = Accion.CREAR;
        habilitarDetallesAtributo(true);

    }

    public void onClick$editarAtributo(Event event) {
        if (accionAtributo != Accion.CONSULTAR) {
            try {
                Messagebox.show("No ha seleccionado ningun atributo");

                return;
            } catch (InterruptedException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }

        habilitarDetallesAtributo(true);

        accionAtributo = Accion.EDITAR;
    }

    public void onClick$nuevoValor(Event event) {
        editarValor.setVisible(true);

        desde.setValue("");
        hasta.setValue("");
        observacionesValores.setValue("");
        descripcionValores.setValue("");
        valorPK.setValue("0");

        accionValor = Accion.CREAR;
    }

    private void habilitarDetallesAtributo(boolean habilitar) {
        nombre.setReadonly(!habilitar);
        descripcion.setReadonly(!habilitar);
        observaciones.setReadonly(!habilitar);
        filtraPorPadre.setDisabled(!habilitar);
        qryFiltraPorPadre.setReadonly(!habilitar);
        selValorPermitido.setDisabled(!habilitar);
        qryLovExterna.setReadonly(!habilitar);
        tipo.setDisabled(!habilitar);
        ecualizable.setDisabled(!habilitar);
        obligatorio.setDisabled(!habilitar);
    }

    public void onClick$editarValor(Event event) {
        if (accionValor != Accion.CONSULTAR) {
            try {
                Messagebox.show("No ha seleccionado ningun valor");

                return;
            } catch (InterruptedException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }

        habilitarDetallesValor(true);
        accionValor = Accion.EDITAR;
    }

    public void onClick$eliminarValor(Event event) {
        if (accionValor != Accion.CONSULTAR) {
            try {
                Messagebox.show("Por favor, seleccione primero un valor");
                return;
            } catch (InterruptedException ex) {
                Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }

        try {
            int res = Messagebox.show("¿Esta seguro?", "Por favor confirme", Messagebox.OK | Messagebox.CANCEL, Messagebox.QUESTION);

            // si el usuario confirma la accion
            if (res == Messagebox.OK) {
                accionValor = Accion.ELIMINAR;
                onClick$btnAceptarValor(event);

                // hago invisible la entrada
                valores.getSelectedItem().setVisible(false);
                valores.setSelectedIndex(0);
            }

        } catch (InterruptedException ex) {
            Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
            ex.printStackTrace();
        }
    }

    public void onClick$btnAceptarValor(Event event) {
        try {
            if (accionValor == Accion.CONSULTAR) {
                try {
                    Messagebox.show("Por favor, seleccione primero editar o nuevo");
                    return;
                } catch (InterruptedException ex) {
                    Logger.getLogger(AbmcAtributosController.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
            // recupero los datos
            ValorPermitidoAtrcomp valorActual = new ValorPermitidoAtrcomp();
            valorActual.setId(new Integer(valorPK.getValue()));
            valorActual.setDescripcion(descripcionValores.getValue());
            valorActual.setObservaciones(observacionesValores.getValue());
            valorActual.setValor(desde.getValue());
            valorActual.setValorHasta(hasta.getValue());
            // chequeo si es un insertar o actualizar
            switch (accionValor) {
                case CREAR:
                    BaseModel.createEntity(valorActual, true);
                    break;
                case EDITAR:
                    BaseModel.editEntity(valorActual, true);
                    break;
                case ELIMINAR:
                    BaseModel.deleteEntity(valorActual, true);
                    break;
                default:
                    break;
            }
            // realizo la operacion
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

        // realizo la operacion

    }

    public void onSelect$atributos(Event event) {
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
        atribPK.setValue(atributo.getId().toString());

        itItems = tipo.getItems().iterator();

        while (itItems.hasNext()) {
            Listitem itemActual = itItems.next();
            if (((String) itemActual.getValue()).compareTo(atributo.getTipo()) == 0) {
                tipo.setSelectedItem(itemActual);
                break;
            }
        }

        // si no se requiere una consulta a una tabla externa
        if (atributo.getQryLovExterna() == null) {
            qryLovExterna.setVisible(false);
            selValorPermitido.setChecked(true);

            valores = atributo.getValorPermitidoAtrcompList();
            Iterator<ValorPermitidoAtrcomp> itValor = valores.iterator();

            while (itValor.hasNext()) {
                ValorPermitidoAtrcomp valorActual = itValor.next();
                Listitem itemActual = new Listitem();

                itemActual.appendChild(new Listcell(valorActual.getValor()));
                itemActual.appendChild(new Listcell(valorActual.getValorHasta()));
                itemActual.appendChild(new Listcell(valorActual.getDescripcion()));
                itemActual.appendChild(new Listcell(valorActual.getObservaciones()));
                itemActual.setId("vp-" + valorActual.getId());

                this.valores.appendChild(itemActual);

            }
        } else {
            qryLovExterna.setValue(atributo.getQryLovExterna());
            qryLovExterna.setVisible(true);
            selValorPermitido.setChecked(false);
        }

        accionValor = Accion.CONSULTAR;
        habilitarDetallesAtributo(false);

    }

    public void onSelect$valores(Event event) {
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
        valorPK.setValue(valorActual.getId().toString());

        habilitarDetallesValor(false);
        accionValor = Accion.CONSULTAR;
    }

    private void habilitarDetallesValor(boolean habilitar) {
        desde.setReadonly(!habilitar);
        hasta.setReadonly(!habilitar);
        descripcionValores.setReadonly(!habilitar);
        observacionesValores.setReadonly(!habilitar);
    }
}
