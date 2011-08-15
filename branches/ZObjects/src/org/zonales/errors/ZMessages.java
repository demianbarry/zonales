/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.errors;

/**
 *
 * @author nacho
 */
public class ZMessages {

    //INFO
    public static final ZMessage SUCCESS = new ZMessage(100, "Success");

    //Warning (errores de código, manejados, etc.
    public static final ZMessage PREVIUS_STATE_WRONG = new ZMessage(200, "Previus state wrong");
    public static final ZMessage UPDATE_FAILED = new ZMessage(201, "Update failed");
    public static final ZMessage SAVE_FAILED = new ZMessage(202, "Save failed");
    public static final ZMessage DATA_NOT_FOUND = new ZMessage(203, "Data not found");
    public static final ZMessage PARAM_REQUIRED_FAILED = new ZMessage(204, "Param required failed");
    public static final ZMessage TAG_TYPE_NOT_FOUND = new ZMessage(205, "Declared tag type doesn't exists");
    public static final ZMessage PARSER_ZGRAM_PARTIALLY_PARSED = new ZMessage(220, "ZGram partially parsed");
    
    //Error (Excepciones)
    public static final ZMessage UNKNOWN_ERROR = new ZMessage(300, "Unknown Error");
    public static final ZMessage NO_DB_FAILED = new ZMessage(301, "DB is unavailable");
    public static final ZMessage MONGODB_ERROR = new ZMessage(302, "MongoDB error");
    public static final ZMessage ZPARSER_USE_ERROR = new ZMessage(320, "ZParser - use error");
    public static final ZMessage ZPARSER_PARSE_ERROR = new ZMessage(321, "ZParser - parse error");
    
}
