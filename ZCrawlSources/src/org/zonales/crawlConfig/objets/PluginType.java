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

    public static ArrayList<String> getPluginTypes() {
        ArrayList<String> types = new ArrayList<String>();

        types.add("URLGetter");
        types.add("Publisher");

        return types;
    }

}
