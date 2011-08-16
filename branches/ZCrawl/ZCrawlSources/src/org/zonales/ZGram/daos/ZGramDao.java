/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram.daos;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import org.bson.types.ObjectId;
import org.zonales.BaseDao;
import org.zonales.ZGram.ZGram;
import org.zonales.crawlConfig.objets.State;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.Filtro;

/**
 *
 * @author nacho
 */
public class ZGramDao extends BaseDao {

    private DBCollection zgrams;

    public ZGramDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.zgrams = this.db.getCollection("zgrams");
        this.zgrams.createIndex(new BasicDBObject("localidad", 1));
        this.zgrams.createIndex(new BasicDBObject("fuente", 1));
        this.zgrams.createIndex(new BasicDBObject("creado", 1));
        this.zgrams.createIndex(new BasicDBObject("modificado", 1));
        this.zgrams.createIndex(new BasicDBObject("estado", 1));
    }

    public String save(ZGram zgram) throws MongoException {
        BasicDBObject zgramDoc = new BasicDBObject();

        if (zgram.getZmessage() != null) {
            zgramDoc.put("cod", zgram.getZmessage().getCod());
            zgramDoc.put("msg", zgram.getZmessage().getMsg());
        }

        if (zgram.getMetadata().getDescripcion() != null)  //opcional
            zgramDoc.put("descripcion", zgram.getMetadata().getDescripcion());
        
        zgramDoc.put("localidad", zgram.getMetadata().getFuente());
        zgramDoc.put("fuente", zgram.getMetadata().getFuente());
        
        if (zgram.getMetadata().getTags() != null) {
            ArrayList tagsToDoc = new ArrayList();
            for (String tag: zgram.getMetadata().getTags()) {
                tagsToDoc.add(tag);
            }
            zgramDoc.put("tags", tagsToDoc);
        }
        
        if (zgram.getMetadata().getUriFuente() != null)
            zgramDoc.put("uri_fuente", zgram.getMetadata().getUriFuente());

        if (zgram.getMetadata().getCriterios() != null) {
            ArrayList criteriosToDoc = new ArrayList();
            for (Criterio criterio : zgram.getMetadata().getCriterios()) {
                if (criterio.getDeLosUsuarios() != null) {
                    List<String> usuarios = criterio.getDeLosUsuarios();
                    ArrayList usuariosToDoc = new ArrayList();
                    for (String usuario: usuarios) {
                        usuariosToDoc.add(usuario);
                    }
                    criteriosToDoc.add(usuariosToDoc);
                }
                if (criterio.getAmigosDe() != null) {
                    criteriosToDoc.add(criterio.getAmigosDe());
                }
                if (criterio.getPalabras() != null) {
                    List<String> palabras = criterio.getPalabras();
                    ArrayList palabrasToDoc = new ArrayList();
                    BasicDBObject palabrasDoc = new BasicDBObject();
                    for (String palabra: palabras) {
                        palabrasToDoc.add(palabra);
                    }
                    palabrasDoc.put("palabras", palabrasToDoc);
                    palabrasDoc.put("siosi", criterio.getSiosi());
                    criteriosToDoc.add(palabrasDoc);
                }
            }
            zgramDoc.put("criterios", criteriosToDoc);
        }

        if (zgram.getMetadata().getNoCriterios() != null) {
            ArrayList nocriteriosToDoc = new ArrayList();
            for (Criterio nocriterio : zgram.getMetadata().getNoCriterios()) {
                if (nocriterio.getDeLosUsuarios() != null) {
                    List<String> usuarios = nocriterio.getDeLosUsuarios();
                    ArrayList usuariosToDoc = new ArrayList();
                    for (String usuario: usuarios) {
                        usuariosToDoc.add(usuario);
                    }
                    nocriteriosToDoc.add(usuariosToDoc);
                }
                if (nocriterio.getAmigosDe() != null) {
                    nocriteriosToDoc.add(nocriterio.getAmigosDe());
                }
                if (nocriterio.getPalabras() != null) {
                    List<String> palabras = nocriterio.getPalabras();
                    ArrayList palabrasToDoc = new ArrayList();
                    BasicDBObject palabrasDoc = new BasicDBObject();
                    for (String palabra: palabras) {
                        palabrasToDoc.add(palabra);
                    }
                    palabrasDoc.put("palabras", palabrasToDoc);
                    palabrasDoc.put("siosi", nocriterio.getSiosi());
                    nocriteriosToDoc.add(palabrasDoc);
                }
            }
            zgramDoc.put("no-criterios", nocriteriosToDoc);
        }

        if (zgram.getMetadata().getComentarios() != null) {
            ArrayList comentariosToDoc = new ArrayList();
            for (String comentario: zgram.getMetadata().getComentarios()) {
                comentariosToDoc.add(comentario);
            }
            zgramDoc.put("comentarios", comentariosToDoc);
        }

        if (zgram.getMetadata().getFiltros() != null) {
            ArrayList filtrosToDoc = new ArrayList();
            BasicDBObject filtroDoc = new BasicDBObject();
            for (Filtro filtro : zgram.getMetadata().getFiltros()) {
                if (filtro.getMinShuldMatch() != null){
                    filtroDoc.clear();
                    filtroDoc.put("min_shuld_match", filtro.getMinShuldMatch());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getDispersion() != null){
                    filtroDoc.clear();
                    filtroDoc.put("dispersion", filtro.getDispersion());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getListaNegraDeUsuarios() != null){
                    filtroDoc.clear();
                    filtroDoc.put("lista negra de usuarios", filtro.getListaNegraDeUsuarios());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getListaNegraDePalabras() != null){
                    filtroDoc.clear();
                    filtroDoc.put("lista negra de palabras", filtro.getListaNegraDePalabras());
                    filtrosToDoc.add(filtroDoc);
                }
                if (filtro.getMinActions() != null){
                    filtroDoc.clear();
                    filtroDoc.put("minActions", filtro.getMinActions());
                    filtrosToDoc.add(filtroDoc);
                }
            }
            zgramDoc.put("filtros", filtrosToDoc);
        }

        if (zgram.getMetadata().getTagsFuente() != null)
            zgramDoc.put("tags_fuente", zgram.getMetadata().getTagsFuente());

        zgramDoc.put("verbatim", zgram.getVerbatim());
        zgramDoc.put("estado", zgram.getEstado());
        zgramDoc.put("creado", (new Date()).getTime());

        System.out.println(zgramDoc.toString());
        this.zgrams.insert(zgramDoc);
        return zgramDoc.get("_id").toString();
    }

    public void update(String id, ZGram newZgram) throws MongoException {
        BasicDBObject query = new BasicDBObject("_id", new ObjectId(id));
        DBObject resp;
        DBCursor cur;

        cur = this.zgrams.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject zgramDoc = new BasicDBObject();

            if (newZgram.getZmessage() != null) {
                zgramDoc.put("cod", newZgram.getZmessage().getCod());
                zgramDoc.put("msg", newZgram.getZmessage().getMsg());
            } else {
                zgramDoc.put("cod", (Integer)resp.get("cod"));
                zgramDoc.put("msg", (String)resp.get("msg"));
            }

            if (newZgram.getMetadata() != null) {
                zgramDoc.put("descripcion", newZgram.getMetadata().getDescripcion());
                zgramDoc.put("localidad", newZgram.getMetadata().getLocalidad());
                zgramDoc.put("fuente", newZgram.getMetadata().getFuente());
                
                if (newZgram.getMetadata().getTags() != null) {
                    ArrayList tagsToDoc = new ArrayList();
                    for (String tag: newZgram.getMetadata().getTags()) {
                        tagsToDoc.add(tag);
                    }
                    zgramDoc.put("tags", tagsToDoc);
                }

                zgramDoc.put("uri_fuente", newZgram.getMetadata().getUriFuente());

                for (Criterio criterio : newZgram.getMetadata().getCriterios()) {
                    ArrayList criteriosToDoc = new ArrayList();
                    if (criterio.getDeLosUsuarios() != null) {
                        List<String> usuarios = criterio.getDeLosUsuarios();
                        ArrayList usuariosToDoc = new ArrayList();
                        for (String usuario: usuarios) {
                            usuariosToDoc.add(usuario);
                        }
                        criteriosToDoc.add(usuariosToDoc);
                    }
                    if (criterio.getAmigosDe() != null) {
                        criteriosToDoc.add(criterio.getAmigosDe());
                    }
                    if (criterio.getPalabras() != null) {
                        List<String> palabras = criterio.getPalabras();
                        ArrayList palabrasToDoc = new ArrayList();
                        BasicDBObject palabrasDoc = new BasicDBObject();
                        for (String palabra: palabras) {
                            palabrasToDoc.add(palabra);
                        }
                        palabrasDoc.put("palabras", palabrasToDoc);
                        palabrasDoc.put("siosi", criterio.getSiosi());
                        criteriosToDoc.add(palabrasDoc);
                    }
                    zgramDoc.put("criterios", criteriosToDoc);
                }

                for (Criterio nocriterio : newZgram.getMetadata().getNoCriterios()) {
                    ArrayList nocriteriosToDoc = new ArrayList();
                    if (nocriterio.getDeLosUsuarios() != null) {
                        List<String> usuarios = nocriterio.getDeLosUsuarios();
                        ArrayList usuariosToDoc = new ArrayList();
                        for (String usuario: usuarios) {
                            usuariosToDoc.add(usuario);
                        }
                        nocriteriosToDoc.add(usuariosToDoc);
                    }
                    if (nocriterio.getAmigosDe() != null) {
                        nocriteriosToDoc.add(nocriterio.getAmigosDe());
                    }
                    if (nocriterio.getPalabras() != null) {
                        List<String> palabras = nocriterio.getPalabras();
                        ArrayList palabrasToDoc = new ArrayList();
                        BasicDBObject palabrasDoc = new BasicDBObject();
                        for (String palabra: palabras) {
                            palabrasToDoc.add(palabra);
                        }
                        palabrasDoc.put("palabras", palabrasToDoc);
                        palabrasDoc.put("siosi", nocriterio.getSiosi());
                        nocriteriosToDoc.add(palabrasDoc);
                    }
                    zgramDoc.put("no-criterios", nocriteriosToDoc);
                }

                if (newZgram.getMetadata().getComentarios() != null) {
                    ArrayList comentariosToDoc = new ArrayList();
                    for (String comentario: newZgram.getMetadata().getComentarios()) {
                        comentariosToDoc.add(comentario);
                    }
                    zgramDoc.put("comentarios", comentariosToDoc);
                }

                if (newZgram.getMetadata().getFiltros() != null) {
                    ArrayList filtrosToDoc = new ArrayList();
                    BasicDBObject filtroDoc = new BasicDBObject();
                    for (Filtro filtro : newZgram.getMetadata().getFiltros()) {
                        if (filtro.getMinShuldMatch() != null){
                            filtroDoc.clear();
                            filtroDoc.put("min_shuld_match", filtro.getMinShuldMatch());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getDispersion() != null){
                            filtroDoc.clear();
                            filtroDoc.put("dispersion", filtro.getDispersion());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getListaNegraDeUsuarios() != null){
                            filtroDoc.clear();
                            filtroDoc.put("lista negra de usuarios", filtro.getListaNegraDeUsuarios());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getListaNegraDePalabras() != null){
                            filtroDoc.clear();
                            filtroDoc.put("lista negra de palabras", filtro.getListaNegraDePalabras());
                            filtrosToDoc.add(filtroDoc);
                        }
                        if (filtro.getMinActions() != null){
                            filtroDoc.clear();
                            filtroDoc.put("minActions", filtro.getMinActions());
                            filtrosToDoc.add(filtroDoc);
                        }
                    }
                    zgramDoc.put("filtros", filtrosToDoc);
                }

                if (newZgram.getMetadata().getTagsFuente() != null)
                    zgramDoc.put("tags_fuente", newZgram.getMetadata().getTagsFuente());

                zgramDoc.put("estado", newZgram.getEstado());

            } else {
                zgramDoc.put("descripcion", (String)resp.get("descripcion"));
                zgramDoc.put("localidad", (String)resp.get("localidad"));
                zgramDoc.put("fuente", (String)resp.get("fuente"));
                zgramDoc.put("tags", (ArrayList)resp.get("tags"));
                zgramDoc.put("uri_fuente", (String)resp.get("uri_fuente"));
                zgramDoc.put("criterios", (ArrayList)resp.get("criterios"));
                zgramDoc.put("no-criterios", (ArrayList)resp.get("no-criterios"));
                zgramDoc.put("comentarios", (ArrayList)resp.get("comentarios"));
                zgramDoc.put("filtros", (ArrayList)resp.get("filtros"));
                zgramDoc.put("tags_fuente", (Boolean)resp.get("tags_fuente"));
                zgramDoc.put("estado", (String)resp.get("estado"));
            }

            zgramDoc.put("creado", (Date)resp.get("creado"));
            zgramDoc.put("modificado", (new Date()).getTime());

            this.zgrams.update(new BasicDBObject().append("_id", id), zgramDoc);
        }
    }

    public String retrieveJson(String id) {
        BasicDBObject query = new BasicDBObject("_id", new ObjectId(id));
        DBObject resp;
        DBCursor cur;

        cur = this.zgrams.find(query);

        resp = cur.next();
        //resp.removeField("_id");
        System.out.println(resp);

        return resp.toString();
    }

    public String retrieveAll() {
        return retrieveAll(false);
    }

    public String retrieveAll(Boolean onlyNames) {
        String ret = "[";
        DBObject resp;
        DBCursor cur = this.zgrams.find();

        while (cur.hasNext()) {
            resp = cur.next();
            //resp.removeField("_id");
            System.out.println(resp);
            if (resp.get("estado") == null || !((String)resp.get("state")).equals(State.VOID)) {
                if (onlyNames) {
                    ret += resp.get("localidad");
                    ret += resp.get("tags");
                    ret += resp.get("descripcion");
                    ret += resp.get("estado");
                    ret += resp.get("modificado") + ",";
                } else {
                    ret += resp + ",";
                }
            } else {
                return null;
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
