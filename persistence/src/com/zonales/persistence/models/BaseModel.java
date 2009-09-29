package com.zonales.persistence.models;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Enumeration;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.List;
import java.util.Set;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import javax.persistence.EntityManager;
import javax.persistence.EntityManagerFactory;
import javax.persistence.EntityNotFoundException;
import javax.persistence.Persistence;
import javax.persistence.Query;
import javax.transaction.HeuristicMixedException;
import javax.transaction.HeuristicRollbackException;
import javax.transaction.NotSupportedException;
import javax.transaction.RollbackException;
import javax.transaction.SystemException;
import javax.transaction.UserTransaction;
import com.zonales.persistence.daos.exceptions.IllegalOrphanException;
import com.zonales.persistence.daos.exceptions.NonexistentEntityException;
import com.zonales.persistence.daos.exceptions.RollbackFailureException;
import com.zonales.persistence.entities.BaseEntity;

/**
 *
 * @author fep
 */
public class BaseModel {

    protected BaseEntity selected;
    protected List<BaseEntity> all;
    protected List<BaseEntity> filtered;
    protected String queryString;
    protected String where;
    protected String orderBy;
    protected int offset;
    protected int maxResults;
    protected Hashtable parameters;
    protected static EntityManagerFactory emf = null;
    private Class entity = null;
    private UserTransaction utx = null;

    public BaseModel(Class entity) {
        setEntity(entity);
        setAll(Collections.synchronizedList(findEntities(entity.getSimpleName() + ".findAll", null)));
        filtered = new ArrayList();
    }

    public BaseEntity getSelected() {
        return selected;
    }

    public void setSelected(BaseEntity todo) {
        this.selected = todo;
    }

    public void setWhere(String where) {
        this.where = where;
    }

    public String getWhere() {
        return where;
    }

    public void setOrderBy(String orderBy) {
        this.orderBy = orderBy;
    }

    public void setOffset(int offset) {
        this.offset = offset;
    }

    public void setMaxResults(int maxResults) {
        this.maxResults = maxResults;
    }

    public String getQueryString() {
        if (queryString != null) {
            return this.queryString;
        }
        return generateQueryString(this.where, this.orderBy);
    }

    public void setQueryString(String queryString) {
        this.queryString = queryString;
    }

    public Hashtable getParameters() {
        if (this.parameters == null) {
            parameters = new Hashtable();
        }
        return this.parameters;
    }

    public void setParameters(Hashtable params) {
        this.parameters = params;
    }

    public List<BaseEntity> getFiltered() {
        return filtered;
    }

    public void setFiltered(List<BaseEntity> filtered) {
        this.filtered = filtered;
    }

    //-- DB access on the selected bean --//
    public void persist(boolean ownTx) throws RollbackFailureException, Exception {
        create(selected, ownTx);
    }

    public void merge(boolean ownTx) throws EntityNotFoundException, IllegalOrphanException, NonexistentEntityException, Exception {
        edit(selected, ownTx);
    }

    public void delete(boolean ownTx) throws EntityNotFoundException, IllegalOrphanException, NonexistentEntityException, RollbackFailureException, Exception {
        destroy(selected, ownTx);
    }

    public void setAll(List<BaseEntity> allEntities) {
        if (Comparable.class.isAssignableFrom(entity)) {
            List entities = allEntities;
            Collections.sort(entities);
        }
        all = allEntities;
    }

    public List<BaseEntity> getAll() {
        return all;
    }

    //-- overridable --//
    /** Generate query string */
    protected String generateQueryString(String where, String orderBy) {
        final StringBuffer sb = new StringBuffer(256);
        sb.append("FROM " + selected.getClass().getName());
        /*if (!Strings.isBlank(where)) {
            sb.append(" WHERE " + where);
        }
        if (!Strings.isBlank(orderBy)) {
            sb.append(" ORDER BY " + orderBy);
        }*/
        return sb.toString();
    }

    public Class getEntity() {
        return entity;
    }

    public void setEntity(Class entity) {
        this.entity = entity;
    }

    public UserTransaction getUtx() throws NamingException {
        if (utx == null) {
            utx = (UserTransaction) InitialContext.doLookup("UserTransaction");
        }
        return utx;
    }

    public void beginTransaction() throws NamingException, NotSupportedException, SystemException {
        getUtx().begin();
    }

    public void commitTransaction() throws NamingException, NamingException, RollbackException, HeuristicMixedException, HeuristicMixedException, HeuristicRollbackException, HeuristicRollbackException, SecurityException, IllegalStateException, IllegalStateException, SystemException {
        getUtx().commit();
    }

    public void rollbackTransaction() throws NamingException, NamingException, IllegalStateException, IllegalStateException, SecurityException, SecurityException, SystemException {
        getUtx().rollback();
    }

    public static EntityManager getEntityManager() {
        if (emf == null) {
            emf = Persistence.createEntityManagerFactory("g2p-tracker-PU");
        }
        return emf.createEntityManager();
    }

    public void create(BaseEntity entity, boolean ownTx) throws RollbackFailureException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        try {
            if (ownTx) {
                getUtx().begin();
            }

            em = getEntityManager();
            em.persist(entity);

            if (ownTx) {
                getUtx().commit();
            }

        } catch (Exception ex) {
            if (ownTx) {
                getUtx().rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public void edit(BaseEntity entity, boolean ownTx) throws NonexistentEntityException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        try {
            if (ownTx) {
                getUtx().begin();
            }

            em = getEntityManager();

            if ((findEntity(entity.getPK())) == null) {
                throw new NonexistentEntityException("El item con el id " + entity.getPK() + " fue eliminado por otro usuario.");
            }

            em.merge(entity);

            if (ownTx) {
                getUtx().commit();
            }
        } catch (Exception ex) {
            if (ownTx) {
                getUtx().rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public void destroy(BaseEntity entity, boolean ownTx) throws NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        try {
            if (ownTx) {
                getUtx().begin();
            }

            em = getEntityManager();
            em.remove(em.getReference(this.entity, entity.getPK()));

            System.out.println("-------------> STATUS: " + getUtx().getStatus());

            if (ownTx) {
                getUtx().commit();
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            if (ownTx) {
                getUtx().rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public int getEntitiesCount() throws NamingException, SystemException, NotSupportedException {
        EntityManager em = getEntityManager();
        try {
            return ((Long) em.createQuery("select count(o) from " + entity.getSimpleName() + " as o").getSingleResult()).intValue();
        } finally {
            em.close();
        }
    }

    public void create(BaseEntity entity) throws RollbackFailureException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        create(entity, true);
    }

    public void edit(BaseEntity entity) throws NonexistentEntityException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        edit(entity, true);
    }

    public void destroy(BaseEntity entity) throws NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        destroy(entity, true);
    }

    public void mergeAll(boolean ownTx) throws Exception {
        Iterator<BaseEntity> it = all.iterator();
        while (it.hasNext()) {
            edit(it.next(), ownTx);
        }
    }

    public void mergeAll() throws Exception {
        mergeAll(true);
    }

    public void mergeFiltered(boolean ownTx) throws Exception {
        Iterator<BaseEntity> it = filtered.iterator();
        while (it.hasNext()) {
            edit(it.next(), ownTx);
        }
    }

    public void mergeFiltered() throws Exception {
        mergeFiltered(true);
    }

    public static void createEntity(BaseEntity entity, boolean ownTx) throws RollbackFailureException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        UserTransaction utx = null;
        try {
            if (ownTx) {
                utx = (UserTransaction) InitialContext.doLookup("UserTransaction");
                utx.begin();
            }

            em = getEntityManager();
            em.persist(entity);

            if (ownTx) {
                utx.commit();
            }

        } catch (Exception ex) {
            if (ownTx) {
                utx.rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public static void editEntity(BaseEntity entity, boolean ownTx) throws RollbackFailureException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        UserTransaction utx = null;
        try {
            if (ownTx) {
                utx = (UserTransaction) InitialContext.doLookup("UserTransaction");
                utx.begin();
            }

            em = getEntityManager();
            em.merge(entity);

            if (ownTx) {
                utx.commit();
            }

        } catch (Exception ex) {
            if (ownTx) {
                utx.rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public static void deleteEntity(BaseEntity entity, boolean ownTx) throws RollbackFailureException, NamingException, IllegalStateException, SecurityException, SystemException, Exception {
        EntityManager em = null;
        UserTransaction utx = null;
        try {
            if (ownTx) {
                utx = (UserTransaction) InitialContext.doLookup("UserTransaction");
                utx.begin();
            }

            em = getEntityManager();
            BaseEntity entidad = em.find(entity.getClass(), entity.getPK());
            //em.remove(em.getReference(entity.getClass(), entity.getPK()));
            em.remove(em.getReference(entidad.getClass(), entidad.getPK()));

            if (ownTx) {
                utx.commit();
            }

        } catch (Exception ex) {
            if (ownTx) {
                utx.rollback();
            }
            throw ex;
        } finally {
            if (em != null) {
                em.close();
            }
        }
    }

    public void newEntity() {
        try {
            setSelected((BaseEntity) entity.newInstance());
        } catch (InstantiationException ex) {
            Logger.getLogger(BaseModel.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(BaseModel.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public List<BaseEntity> findEntities() {
        return findEntities(true, -1, -1);
    }

    public List<BaseEntity> findEntities(int maxResults, int firstResult) {
        return findEntities(false, maxResults, firstResult);
    }

    private List<BaseEntity> findEntities(boolean all, int maxResults, int firstResult) {
        EntityManager em = getEntityManager();
        String query = "select object(o) from " + this.entity.getName() + " as o";
        if (getWhere() != null) {
            query += " WHERE " + getWhere();
        }
        try {
            Query q = em.createQuery(query);
            if (!all) {
                q.setMaxResults(maxResults);
                q.setFirstResult(firstResult);
            }
            return q.getResultList();
        } finally {
            em.close();
        }
    }

    public static List<BaseEntity> findEntities(String namedQuery, Hashtable parameters) {
        EntityManager em = getEntityManager();
        Query query = em.createNamedQuery(namedQuery);

        if (parameters != null) {
            Enumeration keys = parameters.keys();

            while (keys.hasMoreElements()) {
                String param = (String) keys.nextElement();
                Object value = parameters.get(param);
                System.out.println("---> PARAM: " + param + " - VALUE: " + value);
                query.setParameter(param, value);

            }
        }
        //System.out.println("---> QUERY: " + query.getResultList().size());
        return query.getResultList();
    }

    public static List<BaseEntity> findEntitiesByParams(String namedQuery, Object... params) throws Exception {
        EntityManager em = getEntityManager();
        Query query = em.createNamedQuery(namedQuery);


        int count = params.length;
        int index = 0;
        if (count % 2 == 0) {
            while (index < count) {
                String param = (String) params[index];
                Object value = params[index + 1];
                System.out.println("---> PARAM: " + param + " - VALUE: " + value);
                query.setParameter(param, value);
                index = index + 2;
            }
        } else {
            throw new Exception("Debe especificar pares de 'parÃ¡metro','valor' para realizar una consulta.");
        }
        System.out.println("---> QUERY: " + query.getResultList().size());
        return query.getResultList();
    }

    public BaseEntity findEntity(Object pk) {
        EntityManager em = getEntityManager();
        try {
            return (BaseEntity) em.find(entity, pk);
        } finally {
            em.close();
        }
    }

    public static BaseEntity findEntityByPK(Object pk, Class entity) {
        EntityManager em = getEntityManager();
        try {
            return (BaseEntity) em.find(entity, pk);
        } finally {
            em.close();
        }
    }

    public void refreshAll() {
        setAll(findEntities(entity.getSimpleName() + ".findAll", null));
    }

    public void filter(List<BaseEntity> entities) {
        removeFilter();
        if(entities == null){
            entities = new ArrayList();
        }
        if (all != null) {
            int i = 0;
            while (all.size() > i) {
                if (!entities.contains(all.get(i))) {
                    filtered.add(all.get(i));
                    all.remove(i);
                } else {
                    i++;
                }
            }
        } else {
            refreshAll();
        }
    }

    public void filter(Set entities) {
        filter(new ArrayList(entities));
    }

    public void filter(Hashtable criteria) throws Exception {
        removeFilter();
        if (all != null) {
            Iterator<BaseEntity> entities = all.iterator();
            while (entities.hasNext()) {
                BaseEntity entity = entities.next();
                if (criteria != null) {
                    Enumeration keys = criteria.keys();

                    while (keys.hasMoreElements()) {
                        try {
                            String param = (String) keys.nextElement();
                            Object value = criteria.get(param);
                            if (getAttributeValue(param, entity) != value) {
                                all.remove(entity);
                            }
                        } catch (Exception ex) {
                            throw ex;
                        }

                    }
                }
            }
        }
    }

    private Object getAttributeValue(String attribute, BaseEntity entity) throws NoSuchMethodException, IllegalAccessException, IllegalArgumentException, InvocationTargetException {
        Method m = this.entity.getMethod("get" + attribute);
        return m.invoke(entity);
    }

    public void removeFilter() {
        if (filtered != null & filtered.size() > 0) {
            all.addAll(filtered);
            filtered.clear();
        }
    }
}
