/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package reservasjavafx;

/**
 *
 * @author Nosotros
 */
public class ResourceIdAndName {

    int id;
    String name;

    public ResourceIdAndName() {
    }

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

    @Override
    public boolean equals(Object obj) {

        if (obj == null) {
            return false;
        }

        if (getClass() != obj.getClass()) {
            return false;
        }
        final ResourceIdAndName other = (ResourceIdAndName) obj;

        if (this.id != other.id) {
            return false;
        }

        return true;
    }

    @Override
    public int hashCode() {
        int hash = 3;
        hash = 53 * hash + this.id;
        return hash;
    }
}
