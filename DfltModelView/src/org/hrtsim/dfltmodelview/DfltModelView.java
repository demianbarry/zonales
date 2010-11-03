package org.hrtsim.dfltmodelview;

import org.hrtsim.basemodel.api.BaseModel;
import org.hrtsim.dfltmodelview.impl.DfltModelViewSupport;
import org.hrtsim.modelview.api.ModelView;
import org.openide.loaders.MultiDataObject;
import org.openide.windows.CloneableOpenSupport;

/**
 *
 * @author fep
 */
//@ServiceProvider(service = ModelView.class)
public class DfltModelView implements ModelView {

    public CloneableOpenSupport getOpenSupport(MultiDataObject mdo) {
        return new DfltModelViewSupport(mdo.getPrimaryEntry());
    }

    public void show() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void show(BaseModel baseModel) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

}