/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getAvailability;

/**
 *
 * @author Nosotros
 */
public class ResourceIdAndName {

    int id;
    String name;

    public ResourceIdAndName(int id, String name) {
        this.id = id;
        this.name = name;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

}
