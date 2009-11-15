/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package entities;

import java.sql.Timestamp;
import javax.persistence.Column;
import javax.persistence.Version;

/**
 *
 * @author Administrador
 */
@org.hibernate.annotations.Entity(optimisticLock = org.hibernate.annotations.OptimisticLockType.ALL,
dynamicUpdate = true)
public abstract class BaseEntity implements Comparable<BaseEntity> {

    public abstract Object getPK();
    @Version
    @Column(name = "OBJ_VERSION")
    protected Timestamp version;

    public Timestamp getVersion() {
        return version;
    }

    public void setVersion(Timestamp version) {
        this.version = version;
    }

}
