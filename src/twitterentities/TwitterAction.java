package twitterentities;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author juanma
 */
@XmlRootElement()
public class TwitterAction {

    public TwitterAction() {
    }
    
    public TwitterAction(String type, int cant) {
        this.type = type;
        this.cant = cant;
    }

    @XmlElement
    private String type;

    /**
     * Get the value of type
     *
     * @return the value of type
     */
    public String getType() {
        return type;
    }

    /**
     * Set the value of type
     *
     * @param type new value of type
     */
    public void setType(String type) {
        this.type = type;
    }

    @XmlElement
    private int cant;

    /**
     * Get the value of cant
     *
     * @return the value of cant
     */
    public int getCant() {
        return cant;
    }

    /**
     * Set the value of cant
     *
     * @param cant new value of cant
     */
    public void setCant(int cant) {
        this.cant = cant;
    }

}
