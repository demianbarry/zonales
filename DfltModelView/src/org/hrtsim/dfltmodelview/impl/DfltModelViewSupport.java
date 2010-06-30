package org.hrtsim.dfltmodelview.impl;

import org.openide.cookies.CloseCookie;
import org.openide.cookies.OpenCookie;
import org.openide.loaders.MultiDataObject;
import org.openide.loaders.OpenSupport;
import org.openide.windows.CloneableTopComponent;

/**
 *
 * @author fep
 */
public class DfltModelViewSupport extends OpenSupport implements OpenCookie, CloseCookie {

    public DfltModelViewSupport(MultiDataObject.Entry e) {
        super(e);
    }

    @Override
    protected CloneableTopComponent createCloneableTopComponent() {
        DfltModelViewTopComponent tc = new DfltModelViewTopComponent();
        tc.setDisplayName("Sistema de Tiempo Real");
        return tc;
    }
    
}
