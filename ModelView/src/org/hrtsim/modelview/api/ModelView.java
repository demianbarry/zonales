package org.hrtsim.modelview.api;

import org.hrtsim.basemodel.api.BaseModel;
import org.openide.loaders.MultiDataObject;
import org.openide.windows.CloneableOpenSupport;

/**
 *
 * @author fep
 */
public interface ModelView  {

    public CloneableOpenSupport getOpenSupport(MultiDataObject mdo);

    public void show(BaseModel baseModel);

}