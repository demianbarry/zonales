/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.errors;

/**
 *
 * @author nacho
 */
public class Errors {

    //INFO
    public static final Error SUCCESS = new Error(100, "Success");

    //Warning (errores de código, manejados, etc.
    public static final Error PREVIUS_STATE_WRONG = new Error(200, "Previus state wrong");
    public static final Error UPDATE_FAILED = new Error(201, "Update failed");
    public static final Error SAVE_FAILED = new Error(202, "Save failed");
    public static final Error DATA_NOT_FOUND = new Error(203, "Data not found");
    public static final Error PARAM_REQUIRED_FAILED = new Error(204, "Param required failed");
    
    //Error (Excepciones)
    public static final Error UNKNOWN_ERROR = new Error(300, "Unknown Error");
    public static final Error NO_DB_FAILED = new Error(301, "DB is unavailable");

}
