/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getReserve;

import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author Nosotros
 */
public class GetReserveStruct {

    String name = "reserve";
    Data data;
    List<ResourceIdNameDescription> resources = new ArrayList<ResourceIdNameDescription>();

    public GetReserveStruct() {
    }

    public GetReserveStruct(Data data) {
        this.data = data;
    }

    public void add(ResourceIdNameDescription resource) {
        this.resources.add(resource);
    }

}
