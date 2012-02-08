/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.ZGram.daos;

import com.mongodb.BasicDBList;
import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.bson.types.ObjectId;
import org.zonales.BaseDao;
import org.zonales.ZGram.Periodo;
import org.zonales.ZGram.ZGram;
import org.zonales.ZGram.ZGramFilter;
import org.zonales.crawlConfig.objets.State;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.Filtro;

/**
 *
 * @author nacho
 */
public class ZGramDao extends BaseDao {

    private DBCollection extractions;

    public ZGramDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.extractions = this.db.getCollection("extractions");
        this.extractions.createIndex(new BasicDBObject("localidad", 1));
        this.extractions.createIndex(new BasicDBObject("fuente", 1));
        this.extractions.createIndex(new BasicDBObject("creado", 1));
        this.extractions.createIndex(new BasicDBObject("modificado", 1));
        this.extractions.createIndex(new BasicDBObject("estado", 1));
    }

    public String save(ZGram zgram) throws MongoException {
        BasicDBObject zgramDoc = new BasicDBObject();

        if (zgram.getZmessage() != null) {
            zgramDoc.put("cod", zgram.getZmessage().getCod());
            zgramDoc.put("msg", zgram.getZmessage().getMsg());
        }

        if (zgram.getDescripcion() != null) //opcional
        {
            zgramDoc.put("descripcion", zgram.getDescripcion());
        }

        zgramDoc.put("localidad", zgram.getLocalidad());
        zgramDoc.put("fuente", zgram.getFuente());

        if (zgram.getTags() != null) {
            ArrayList tagsToDoc = new ArrayList();
            for (String tag : zgram.getTags()) {
                tagsToDoc.add(tag);
            }
            zgramDoc.put("tags", tagsToDoc);
        }

        if (zgram.getUriFuente() != null) {
            zgramDoc.put("uriFuente", zgram.getUriFuente());
        }

        if (zgram.getCriterios() != null) {
            ArrayList criteriosToDoc = new ArrayList();
            for (Criterio criterio : zgram.getCriterios()) {
                if (criterio.getDeLosUsuarios() != null) {
                    List<String> usuarios = criterio.getDeLosUsuarios();
                    ArrayList usuariosToDoc = new ArrayList();
                    for (String usuario : usuarios) {
                        usuariosToDoc.add(usuario);
                    }
                    BasicDBObject usuariosDoc = new BasicDBObject();
                    usuariosDoc.put("deLosUsuarios", usuariosToDoc);
                    criteriosToDoc.add(usuariosDoc);
                }
                if (criterio.getDeLosUsuariosLatitudes() != null) {
                    List<Double> usuariosLat = criterio.getDeLosUsuariosLatitudes();
                    ArrayList usuariosLatToDoc = new ArrayList();
                    for (Double usuarioLat : usuariosLat) {
                        usuariosLatToDoc.add(usuarioLat);
                    }
                    BasicDBObject usuariosLatDoc = new BasicDBObject();
                    usuariosLatDoc.put("deLosUsuariosLatitudes", usuariosLatToDoc);
                    criteriosToDoc.add(usuariosLatDoc);
                }
                if (criterio.getDeLosUsuariosLongitudes() != null) {
                    List<Double> usuariosLon = criterio.getDeLosUsuariosLongitudes();
                    ArrayList usuariosLonToDoc = new ArrayList();
                    for (Double usuarioLon : usuariosLon) {
                        usuariosLonToDoc.add(usuarioLon);
                    }
                    BasicDBObject usuariosLonDoc = new BasicDBObject();
                    usuariosLonDoc.put("deLosUsuariosLongitudes", usuariosLonToDoc);
                    criteriosToDoc.add(usuariosLonDoc);
                }
                if (criterio.getAmigosDe() != null) {
                    BasicDBObject amigosDoc = new BasicDBObject();
                    amigosDoc.put("amigosDe", amigosDoc);
                    criteriosToDoc.add(amigosDoc);
                }
                if (criterio.getPalabras() != null) {
                    List<String> palabras = criterio.getPalabras();
                    ArrayList palabrasToDoc = new ArrayList();
                    BasicDBObject palabrasDoc = new BasicDBObject();
                    for (String palabra : palabras) {
                        palabrasToDoc.add(palabra);
                    }
                    palabrasDoc.put("palabras", palabrasToDoc);
                    palabrasDoc.put("siosi", criterio.getSiosi());
                    criteriosToDoc.add(palabrasDoc);
                }
            }
            zgramDoc.put("criterios", criteriosToDoc);
        }

        if (zgram.getNoCriterios() != null) {
            ArrayList nocriteriosToDoc = new ArrayList();
            for (Criterio nocriterio : zgram.getNoCriterios()) {
                if (nocriterio.getDeLosUsuarios() != null) {
                    List<String> usuarios = nocriterio.getDeLosUsuarios();
                    ArrayList usuariosToDoc = new ArrayList();
                    for (String usuario : usuarios) {
                        usuariosToDoc.add(usuario);
                    }
                    BasicDBObject usuariosDoc = new BasicDBObject();
                    usuariosDoc.put("deLosUsuarios", usuariosToDoc);
                    nocriteriosToDoc.add(usuariosDoc);
                }
                if (nocriterio.getAmigosDe() != null) {
                    BasicDBObject amigosDoc = new BasicDBObject();
                    amigosDoc.put("amigosDe", amigosDoc);
                    nocriteriosToDoc.add(amigosDoc);
                }
                if (nocriterio.getPalabras() != null) {
                    List<String> palabras = nocriterio.getPalabras();
                    ArrayList palabrasToDoc = new ArrayList();
                    BasicDBObject palabrasDoc = new BasicDBObject();
                    for (String palabra : palabras) {
                        palabrasToDoc.add(palabra);
                    }
                    palabrasDoc.put("palabras", palabrasToDoc);
                    palabrasDoc.put("siosi", nocriterio.getSiosi());
                    nocriteriosToDoc.add(palabrasDoc);
                }
            }
            zgramDoc.put("noCriterios", nocriteriosToDoc);
        }

        if (zgram.getComentarios() != null) {
            ArrayList comentariosToDoc = new ArrayList();
            for (String comentario : zgram.getComentarios()) {
                comentariosToDoc.add(comentario);
            }
            zgramDoc.put("comentarios", comentariosToDoc);
        }

        if (zgram.getFiltros() != null) {
            ArrayList filtrosToDoc = new ArrayList();
            BasicDBObject filtroDoc = new BasicDBObject();
            for (Filtro filtro : zgram.getFiltros()) {
                if (filtro.getMinShuldMatch() != null) {
                    filtroDoc.clear();
                    filtroDoc.put("minShuldMatch", filtro.getMinShuldMatch());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getDispersion() != null) {
                    filtroDoc.clear();
                    filtroDoc.put("dispersion", filtro.getDispersion());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getListaNegraDeUsuarios() != null) {
                    filtroDoc.clear();
                    filtroDoc.put("listaNegraDeUsuarios", filtro.getListaNegraDeUsuarios());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getListaNegraDePalabras() != null) {
                    filtroDoc.clear();
                    filtroDoc.put("listaNegraDePalabras", filtro.getListaNegraDePalabras());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getMinActions() != null) {
                    filtroDoc.clear();
                    filtroDoc.put("minActions", filtro.getMinActions());
                    filtrosToDoc.add(filtroDoc);
                }
            }
            zgramDoc.put("filtros", filtrosToDoc);
        }

        if (zgram.getTagsFuente() != null) {
            zgramDoc.put("tagsFuente", zgram.getTagsFuente());
        }

        zgramDoc.put("incluyeComentarios", zgram.getIncluyeComentarios());

        zgramDoc.put("verbatim", zgram.getVerbatim());
        zgramDoc.put("estado", zgram.getEstado());
        zgramDoc.put("creado", (new Date()).getTime());
        if (zgram.getCreadoPor() != null) {
            zgramDoc.put("creadoPor", zgram.getCreadoPor());
            zgramDoc.put("modificadoPor", zgram.getModificadoPor());
        } else {
            zgramDoc.put("creadoPor", "Anonimo"); //TODO: Chanchada, corregir luego de que esté implementado el Middleware
            zgramDoc.put("modificadoPor", "Anonimo");
        }
        zgramDoc.put("modificado", (new Date()).getTime());

        zgramDoc.put("periodicidad", zgram.getPeriodicidad());
        if ((Double) zgram.getSourceLatitude() != null) {
            zgramDoc.put("sourceLatitude", zgram.getSourceLatitude());
        }
        if ((Double) zgram.getSourceLongitude() != null) {
            zgramDoc.put("sourceLongitude", zgram.getSourceLongitude());
        }

        System.out.println(zgramDoc.toString());
        this.extractions.insert(zgramDoc);
        return zgramDoc.get("_id").toString();
    }

    public void update(String id, ZGram newZgram) throws MongoException {
        BasicDBObject query = new BasicDBObject("_id", new ObjectId(id));
        DBObject resp;

        resp = this.extractions.findOne(query);

        if (resp != null) {
            BasicDBObject zgramDoc = new BasicDBObject();

            if (newZgram != null) {

                if (newZgram.getCod() != null) {
                    zgramDoc.put("cod", newZgram.getZmessage().getCod());
                } else {
                    zgramDoc.put("cod", (Integer) resp.get("cod"));
                }

                if (newZgram.getMsg() != null) {
                    zgramDoc.put("msg", newZgram.getZmessage().getMsg());
                } else {
                    zgramDoc.put("msg", (String) resp.get("msg"));
                }

                if (newZgram.getDescripcion() != null) {
                    zgramDoc.put("descripcion", newZgram.getDescripcion());
                } else {
                    zgramDoc.put("descripcion", (String) resp.get("descripcion"));
                }

                if (newZgram.getLocalidad() != null) {
                    zgramDoc.put("localidad", newZgram.getLocalidad());
                } else {
                    zgramDoc.put("localidad", (String) resp.get("localidad"));
                }

                if (newZgram.getFuente() != null) {
                    zgramDoc.put("fuente", newZgram.getFuente());
                } else {
                    zgramDoc.put("fuente", (String) resp.get("fuente"));
                }

                if (newZgram.getTags() != null) {
                    ArrayList tagsToDoc = new ArrayList();
                    for (String tag : newZgram.getTags()) {
                        tagsToDoc.add(tag);
                    }
                    zgramDoc.put("tags", tagsToDoc);
                } else {
                    zgramDoc.put("tags", (ArrayList) resp.get("tags"));
                }

                if (newZgram.getUriFuente() != null) {
                    zgramDoc.put("uriFuente", newZgram.getUriFuente());
                } else {
                    zgramDoc.put("uriFuente", (String) resp.get("uriFuente"));
                }

                if (newZgram.getCriterios() != null) {
                    ArrayList criteriosToDoc = new ArrayList();
                    for (Criterio criterio : newZgram.getCriterios()) {
                        if (criterio.getDeLosUsuarios() != null) {
                            List<String> usuarios = criterio.getDeLosUsuarios();
                            ArrayList usuariosToDoc = new ArrayList();
                            for (String usuario : usuarios) {
                                usuariosToDoc.add(usuario);
                            }
                            BasicDBObject usuariosDoc = new BasicDBObject();
                            usuariosDoc.put("deLosUsuarios", usuariosToDoc);
                            criteriosToDoc.add(usuariosDoc);
                        }
                        if (criterio.getDeLosUsuariosLatitudes() != null) {
                            List<Double> usuariosLat = criterio.getDeLosUsuariosLatitudes();
                            ArrayList usuariosLatToDoc = new ArrayList();
                            for (Double usuarioLat : usuariosLat) {
                                usuariosLatToDoc.add(usuarioLat);
                            }
                            BasicDBObject usuariosLatDoc = new BasicDBObject();
                            usuariosLatDoc.put("deLosUsuariosLatitudes", usuariosLatToDoc);
                            criteriosToDoc.add(usuariosLatDoc);
                        }
                        if (criterio.getDeLosUsuariosLongitudes() != null) {
                            List<Double> usuariosLon = criterio.getDeLosUsuariosLongitudes();
                            ArrayList usuariosLonToDoc = new ArrayList();
                            for (Double usuarioLon : usuariosLon) {
                                usuariosLonToDoc.add(usuarioLon);
                            }
                            BasicDBObject usuariosLonDoc = new BasicDBObject();
                            usuariosLonDoc.put("deLosUsuariosLongitudes", usuariosLonToDoc);
                            criteriosToDoc.add(usuariosLonDoc);
                        }
                        if (criterio.getAmigosDe() != null) {
                            BasicDBObject amigosDoc = new BasicDBObject();
                            amigosDoc.put("amigosDe", amigosDoc);
                            criteriosToDoc.add(amigosDoc);
                        }
                        if (criterio.getPalabras() != null) {
                            List<String> palabras = criterio.getPalabras();
                            ArrayList palabrasToDoc = new ArrayList();
                            BasicDBObject palabrasDoc = new BasicDBObject();
                            for (String palabra : palabras) {
                                palabrasToDoc.add(palabra);
                            }
                            palabrasDoc.put("palabras", palabrasToDoc);
                            palabrasDoc.put("siosi", criterio.getSiosi());
                            criteriosToDoc.add(palabrasDoc);
                        }
                    }
                    zgramDoc.put("criterios", criteriosToDoc);
                } else {
                    zgramDoc.put("criterios", (ArrayList) resp.get("criterios"));
                }

                if (newZgram.getNoCriterios() != null) {
                    ArrayList nocriteriosToDoc = new ArrayList();
                    for (Criterio nocriterio : newZgram.getNoCriterios()) {
                        if (nocriterio.getDeLosUsuarios() != null) {
                            List<String> usuarios = nocriterio.getDeLosUsuarios();
                            ArrayList usuariosToDoc = new ArrayList();
                            for (String usuario : usuarios) {
                                usuariosToDoc.add(usuario);
                            }
                            BasicDBObject usuariosDoc = new BasicDBObject();
                            usuariosDoc.put("deLosUsuarios", usuariosToDoc);
                            nocriteriosToDoc.add(usuariosDoc);
                        }
                        if (nocriterio.getAmigosDe() != null) {
                            BasicDBObject amigosDoc = new BasicDBObject();
                            amigosDoc.put("amigosDe", amigosDoc);
                            nocriteriosToDoc.add(amigosDoc);
                        }
                        if (nocriterio.getPalabras() != null) {
                            List<String> palabras = nocriterio.getPalabras();
                            ArrayList palabrasToDoc = new ArrayList();
                            BasicDBObject palabrasDoc = new BasicDBObject();
                            for (String palabra : palabras) {
                                palabrasToDoc.add(palabra);
                            }
                            palabrasDoc.put("palabras", palabrasToDoc);
                            palabrasDoc.put("siosi", nocriterio.getSiosi());
                            nocriteriosToDoc.add(palabrasDoc);
                        }
                    }
                    zgramDoc.put("noCriterios", nocriteriosToDoc);
                } else {
                    zgramDoc.put("noCriterios", (ArrayList) resp.get("noCriterios"));
                }

                if (newZgram.getComentarios() != null) {
                    ArrayList comentariosToDoc = new ArrayList();
                    for (String comentario : newZgram.getComentarios()) {
                        comentariosToDoc.add(comentario);
                    }
                    zgramDoc.put("comentarios", comentariosToDoc);
                } else {
                    zgramDoc.put("comentarios", (ArrayList) resp.get("comentarios"));
                }

                if (newZgram.getFiltros() != null) {
                    ArrayList filtrosToDoc = new ArrayList();
                    BasicDBObject filtroDoc = new BasicDBObject();
                    for (Filtro filtro : newZgram.getFiltros()) {
                        if (filtro.getMinShuldMatch() != null) {
                            filtroDoc.put("minShuldMatch", filtro.getMinShuldMatch());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getDispersion() != null) {
                            filtroDoc.put("dispersion", filtro.getDispersion());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getListaNegraDeUsuarios() != null) {
                            filtroDoc.put("listaNegraDeUsuarios", filtro.getListaNegraDeUsuarios());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getListaNegraDePalabras() != null) {
                            filtroDoc.put("listaNegraDePalabras", filtro.getListaNegraDePalabras());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getMinActions() != null) {
                            filtroDoc.put("minActions", filtro.getMinActions());
                            filtrosToDoc.add(filtroDoc);
                        }
                    }
                    zgramDoc.put("filtros", filtrosToDoc);
                } else {
                    zgramDoc.put("filtros", (ArrayList) resp.get("filtros"));
                }

                if (newZgram.getTagsFuente() != null) {
                    zgramDoc.put("tagsFuente", newZgram.getTagsFuente());
                } else {
                    zgramDoc.put("tagsFuente", (Boolean) resp.get("tagsFuente"));
                }

                if (newZgram.getIncluyeComentarios() != null) {
                    zgramDoc.put("incluyeComentarios", newZgram.getIncluyeComentarios());
                } else {
                    zgramDoc.put("incluyeComentarios", (Boolean) resp.get("incluyeComentarios"));
                }

                if (newZgram.getEstado() != null) {
                    zgramDoc.put("estado", newZgram.getEstado());
                } else {
                    zgramDoc.put("estado", (String) resp.get("estado"));
                }

                if (newZgram.getVerbatim() != null) {
                    zgramDoc.put("verbatim", newZgram.getVerbatim());
                } else {
                    zgramDoc.put("verbatim", (String) resp.get("verbatim"));
                }

                if (newZgram.getPeriodicidad() != null) {
                    zgramDoc.put("periodicidad", newZgram.getPeriodicidad());
                } else {
                    zgramDoc.put("periodicidad", (Integer)(resp.get("periodicidad")));
                }

                if (newZgram.getSiosi() != null) {
                    zgramDoc.put("siosi", newZgram.getSiosi());
                } else {
                    zgramDoc.put("siosi", (Boolean) resp.get("siosi"));
                }

                if (newZgram.getUltimaExtraccionConDatos() != null) {
                    zgramDoc.put("ultimaExtraccionConDatos", newZgram.getUltimaExtraccionConDatos());
                } else {
                    zgramDoc.put("ultimaExtraccionConDatos", (Long) resp.get("ultimaExtraccionConDatos"));
                }

                if (newZgram.getUltimoHitDeExtraccion() != null) {
                    zgramDoc.put("ultimoHitDeExtraccion", newZgram.getUltimoHitDeExtraccion());
                } else {
                    zgramDoc.put("ultimoHitDeExtraccion", (Long) resp.get("ultimoHitDeExtraccion"));
                }

                if ((Double) newZgram.getSourceLatitude() != null) {
                    zgramDoc.put("sourceLatitude", newZgram.getSourceLatitude());
                } else {
                    zgramDoc.put("sourceLatitude", (Double) resp.get("sourceLatitude"));
                }

                if ((Double) newZgram.getSourceLongitude() != null) {
                    zgramDoc.put("sourceLongitude", newZgram.getSourceLongitude());
                } else {
                    zgramDoc.put("sourceLongitude", (Double) resp.get("sourceLongitude"));
                }

            }

            zgramDoc.put("creado", (Long) resp.get("creado"));
            zgramDoc.put("creadoPor", (String) resp.get("creadoPor"));
            zgramDoc.put("modificado", (new Date()).getTime());
            if (newZgram.getModificadoPor() != null) {
                zgramDoc.put("modificadoPor", newZgram.getModificadoPor());
            } else {
                zgramDoc.put("modificadoPor", "Anonimo");  //TODO: Chanchada, corregir luego de que esté implementado el Middleware
            }
            this.extractions.update(new BasicDBObject().append("_id", new ObjectId(id)), zgramDoc);
        }
    }

    public String retrieveJson(String id) {
        BasicDBObject query = new BasicDBObject("_id", new ObjectId(id));
        DBObject resp;

        resp = this.extractions.findOne(query);

        System.out.println(resp);

        return resp.toString();
    }

    public Long retrieveLastExtractionHit(String id) {
        BasicDBObject query = new BasicDBObject("_id", new ObjectId(id));
        DBObject resp;

        resp = this.extractions.findOne(query);

        if (resp != null) {
            return (Long) resp.get("ultimoHitDeExtraccion");
        } else {
            return null;
        }
    }

    public List<String> getPublished() {
        List<String> idList = new ArrayList<String>();
        BasicDBObject query = new BasicDBObject();
        DBObject resp;
        DBCursor cur;

        query.put("estado", State.PUBLISHED);
        cur = this.extractions.find(query);

        while (cur.hasNext()) {
            resp = cur.next();
            idList.add(resp.get("_id").toString());
        }

        return idList;
    }

    public String retrieveJson(ZGramFilter filtros, Boolean onlyNames) {
        BasicDBObject query = new BasicDBObject();
        BasicDBList or = new BasicDBList();
        BasicDBObject all = new BasicDBObject();
        DBObject resp;
        DBCursor cur;

        if (filtros.getEstado() != null) {
            query.put("estado", filtros.getEstado());
        }
        if (filtros.getFuente() != null) {
            query.put("fuente", filtros.getFuente());
        }
        if (filtros.getLocalidad() != null) {
            for (String localidad : filtros.getLocalidad().split(",")) {
                or.add(new BasicDBObject("localidad", localidad));
            }
            query.put("$or", or);
        }

        if (filtros.getTags() != null) {
            all.put("$all", filtros.getTags());
            query.put("tags", all);
        }

        Calendar now = Calendar.getInstance();
        if (filtros.getPeriodo() != null && !filtros.getPeriodo().equals(Periodo.ALL)) {
            System.out.println(now.getTime().getTime());
            now.set(Calendar.HOUR_OF_DAY, 0);
            now.set(Calendar.MINUTE, 0);
            now.set(Calendar.SECOND, 0);
            now.set(Calendar.MILLISECOND, 0);
            if (filtros.getPeriodo().equals(Periodo.DAY)) {
                query.put("modificado", new BasicDBObject("$gt", now.getTime().getTime()));
            }
            now.set(Calendar.DAY_OF_WEEK, Calendar.MONDAY);
            if (filtros.getPeriodo().equals(Periodo.WEEK)) {
                query.put("modificado", new BasicDBObject("$gt", now.getTime().getTime()));
            }
            now.set(Calendar.DAY_OF_MONTH, 1);
            if (filtros.getPeriodo().equals(Periodo.MONTH)) {
                query.put("modificado", new BasicDBObject("$gt", now.getTime().getTime()));
            }
        }
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "QUERY: {0}", new Object[]{query.toString()});

        cur = this.extractions.find(query);

        String ret = "[";
        Boolean nothing = true;
        while (cur.hasNext()) {
            resp = cur.next();
            if (onlyNames) {
                ret += "{\"_id\": \"" + resp.get("_id") + "\",";
                ret += "\"localidad\": \"" + resp.get("localidad") + "\",";
                ret += "\"tags\": " + resp.get("tags") + ",";
                ret += "\"descripcion\": \"" + resp.get("descripcion") + "\",";
                ret += "\"estado\": \"" + resp.get("estado") + "\",";
                ret += "\"modificado\": " + resp.get("modificado") + "},";
            } else {
                ret += resp + ",";
            }
            if (nothing) {
                nothing = false;
            }
        }
        if (!nothing) {
            ret = ret.substring(0, ret.length() - 1);
        }
        ret += "]";

        return ret;
    }

    public String retrieveAll() {
        return retrieveAll(false);
    }

    public String retrieveAll(Boolean onlyNames) {
        String ret = "[";

        DBObject resp;
        DBCursor cur = this.extractions.find();

        while (cur.hasNext()) {
            resp = cur.next();
            //resp.removeField("_id");
            System.out.println(resp);
            if (resp.get("estado") != null) {
                if (onlyNames) {
                    ret += "{\"_id\": \"" + resp.get("_id") + "\",";
                    ret += "\"localidad\": \"" + resp.get("localidad") + "\",";
                    ret += "\"tags\": " + resp.get("tags") + ",";
                    ret += "\"descripcion\": \"" + resp.get("descripcion") + "\",";
                    ret += "\"estado\": \"" + resp.get("estado") + "\",";
                    ret += "\"modificado\": " + resp.get("modificado") + "},";
                } else {
                    ret += resp + ",";
                }
            }
        }

        ret = ret.substring(0, ret.length() - 1);
        ret += "]";

        return ret;
    }

    /*
    public Service retrieve(String name) {
    BasicDBObject query = new BasicDBObject("name", name);
    DBObject resp;
    DBCursor cur;
    Service service = new Service();
    ArrayList<BasicDBObject> params, plugins;
    
    cur = this.services.find(query);
    
    resp = cur.next();
    resp.removeField("_id");
    System.out.println(resp);
    
    service.setName((String)resp.get("name"));
    service.setUri((String)resp.get("uri"));
    try {
    service.setState((String)resp.get("state"));
    } catch (TypeNotPresentException ex) {
    service.setState(State.GENERATED);
    }
    
    //service.setPluginName((String)resp.get("pluginName"));
    
    params = (ArrayList<BasicDBObject>)resp.get("params");
    
    for (BasicDBObject param: params) {
    service.addParam((String)param.get("name"), (Boolean)param.get("required"));
    }
    
    plugins = (ArrayList<BasicDBObject>)resp.get("plugins");
    
    for (BasicDBObject plugin: plugins) {
    service.addPlugin((String)plugin.get("class_name"), (String)plugin.get("type"));
    }
    
    return service;
    
    }
     */
}
