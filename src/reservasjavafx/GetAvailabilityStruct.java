/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package reservasjavafx;

import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class GetAvailabilityStruct {

    String name = "noAvailability";
    ResourceGroupInDate resourceGroupInDate;
    List<ResourceIdAndName> noAvailableResources = new ArrayList<ResourceIdAndName>();

    public GetAvailabilityStruct() {
    }

    public GetAvailabilityStruct(ResourceGroupInDate resourceGroupInDate) {
        this.resourceGroupInDate = resourceGroupInDate;
    }

    public void add(ResourceIdAndName resourceIdAndName) {
        this.noAvailableResources.add(resourceIdAndName);
    }

}
