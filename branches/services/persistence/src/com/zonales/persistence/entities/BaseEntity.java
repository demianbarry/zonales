package com.zonales.persistence.entities;

import java.sql.Timestamp;
import java.util.Date;
import java.util.Properties;
import javax.persistence.Column;
import javax.persistence.Version;

/**
 *
 * @author fep
 */
@org.hibernate.annotations.Entity(optimisticLock = org.hibernate.annotations.OptimisticLockType.ALL,
dynamicUpdate = true)
public abstract class BaseEntity {

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
