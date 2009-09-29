/**
 * @version	$Id$
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
package com.zonales.persistence.entities;

import java.sql.Timestamp;
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
