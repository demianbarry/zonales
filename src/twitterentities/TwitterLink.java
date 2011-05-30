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
public class TwitterLink {

    public TwitterLink() {
    }

    public TwitterLink(String type, String url) {
        this.type = type;
        this.url = url;
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
    private String url;

    /**
     * Get the value of url
     *
     * @return the value of url
     */
    public String getUrl() {
        return url;
    }

    /**
     * Set the value of url
     *
     * @param url new value of url
     */
    public void setUrl(String url) {
        this.url = url;
    }

}
