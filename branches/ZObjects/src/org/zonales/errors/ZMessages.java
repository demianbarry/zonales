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

    public static String appendMessage(ZMessage error, String msg) {
        return "{\"cod\": \"" + error.getCod() + "\", \"msg\": \"" + error.getMsg() + msg + "\"}";
    }

    public static ZMessage switchMessages(int code) {
        switch (code) {
            case 200:
                return PREVIUS_STATE_WRONG;
            case 201:
                return UPDATE_FAILED;
            case 202:
                return SAVE_FAILED;
            case 203:
                return DATA_NOT_FOUND;
            case 204:
                return PARAM_REQUIRED_FAILED;
            case 205:
                return TAG_TYPE_NOT_FOUND;
            case 206:
                return CONFIG_NOT_FOUND;
            case 220:
                return ZPARSER_ZGRAM_PARTIALLY_PARSED;
            case 300:
                return UNKNOWN_ERROR;
            case 301:
                return NO_DB_FAILED;
            case 302:
                return MONGODB_ERROR;
            case 303:
                return CONN_ERROR;
            case 320:
                return ZPARSER_USE_ERROR;
            case 321:
                return ZPARSER_PARSE_VISITOR_ERROR;
            case 322:
                return ZPARSER_PARSE_VISITOR_ILEGAL_ERROR;
            case 323:
                return ZPARSER_PARSE_VISITOR_INSTANT_ERROR;
            case 324:
                return ZPARSER_CANNOT_PARSE;
            case 350:
                return ZSCHEDULER_SCHEDULER_ERROR;
            case 351:
                return ZSCHEDULER_NAMING_ERROR;
            case 352:
                return ZSCHEDULER_UNKNOW_ERROR;
            case 353:
                return ZSCHEDULER_CONN_ERROR;
            case 354:
                return ZSCHEDULER_URL_ERROR;
            case 370:
                return ZSOURCES_CONN_ERROR;
            case 371:
                return ZSOURCES_GETURL_ERROR;
            case 380:
                return ZSERVLETS_CONN_ERROR;
            case 390:
                return GSON_CONVERTION_ERROR;
            default:
                return SUCCESS;
        }
    }
    //INFO
    public static final ZMessage SUCCESS = new ZMessage(100, "Success");
    //Warning (errores de código, manejados, etc.
    public static final ZMessage PREVIUS_STATE_WRONG = new ZMessage(200, "Previus state wrong");
    public static final ZMessage UPDATE_FAILED = new ZMessage(201, "Update failed");
    public static final ZMessage SAVE_FAILED = new ZMessage(202, "Save failed");
    public static final ZMessage DATA_NOT_FOUND = new ZMessage(203, "Data not found");
    public static final ZMessage CONFIG_NOT_FOUND = new ZMessage(206, "Crawl Config not found");
    public static final ZMessage PARAM_REQUIRED_FAILED = new ZMessage(204, "Param required failed");
    public static final ZMessage TAG_TYPE_NOT_FOUND = new ZMessage(205, "Declared tag type doesn't exists");
    public static final ZMessage ZPARSER_ZGRAM_PARTIALLY_PARSED = new ZMessage(220, "ZParser - La consulta se parseo parcialmente. La porción correcta se guardó.");
    //Error (Excepciones)
    public static final ZMessage UNKNOWN_ERROR = new ZMessage(300, "Unknown Error");
    public static final ZMessage NO_DB_FAILED = new ZMessage(301, "DB is unavailable");
    public static final ZMessage MONGODB_ERROR = new ZMessage(302, "MongoDB error");
    public static final ZMessage CONN_ERROR = new ZMessage(303, "Connection error");
    public static final ZMessage ZPARSER_USE_ERROR = new ZMessage(320, "ZParser - use error: Parser [-rule rulename] [-trace] <-file file | -string string> [-visitor visitor]");
    public static final ZMessage ZPARSER_PARSE_VISITOR_ERROR = new ZMessage(321, "ZParser - visitor error: class not found");
    public static final ZMessage ZPARSER_PARSE_VISITOR_ILEGAL_ERROR = new ZMessage(322, "ZParser - visitor error: illegal access");
    public static final ZMessage ZPARSER_PARSE_VISITOR_INSTANT_ERROR = new ZMessage(323, "ZParser - visitor error: instantiation failure");
    public static final ZMessage ZPARSER_CANNOT_PARSE = new ZMessage(324, "ZParser - No se pudo parsear la consulta");
    public static final ZMessage ZSCHEDULER_SCHEDULER_ERROR = new ZMessage(350, "ZScheduler - Error en el scheduler");
    public static final ZMessage ZSCHEDULER_NAMING_ERROR = new ZMessage(351, "ZScheduler - No se pudo obtener el scheduler del contexto");
    public static final ZMessage ZSCHEDULER_UNKNOW_ERROR = new ZMessage(352, "ZScheduler - Error desconocido");
    public static final ZMessage ZSCHEDULER_CONN_ERROR = new ZMessage(353, "ZScheduler - Error de conección");
    public static final ZMessage ZSCHEDULER_URL_ERROR = new ZMessage(354, "ZScheduler - Error en URL");
    public static final ZMessage ZSOURCES_CONN_ERROR = new ZMessage(370, "ZSources - Error de conección");
    public static final ZMessage ZSOURCES_GETURL_ERROR = new ZMessage(371, "ZSources - Error obteniendo URL de extracción");
    public static final ZMessage ZSERVLETS_CONN_ERROR = new ZMessage(380, "ZServlet - Error de conección");
    public static final ZMessage GSON_CONVERTION_ERROR = new ZMessage(390, "GSon - Error de Conversión");
}
