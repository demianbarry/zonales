/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class getAvailabilityStruct {

    String name = "noAvailability";
    ResourceGroupInDate resourceGroupInDate;
    List<ResourceIdAndName> noAvailableResources = new ArrayList<ResourceIdAndName>();

    public getAvailabilityStruct(ResourceGroupInDate resourceGroupInDate) {
        this.resourceGroupInDate = resourceGroupInDate;
    }

    public void add(ResourceIdAndName resourceIdAndName) {
        this.noAvailableResources.add(resourceIdAndName);
    }

}
