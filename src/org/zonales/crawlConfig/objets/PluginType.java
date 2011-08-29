/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

import java.util.ArrayList;

/**
 *
 * @author nacho
 */
public class PluginType {
    
    public static final String URL_GETTER = "URLGetter";
    public static final String PUBLISHER = "Publisher";
    public static final String UNPUBLISHER = "Unpublisher";
    

    public static ArrayList<String> getPluginTypes() {
        ArrayList<String> types = new ArrayList<String>();

        types.add(URL_GETTER);
        types.add(PUBLISHER);
        types.add(UNPUBLISHER);

        return types;
    }

}
