package twitterentities;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

/**
 *
 * @author juanma
 */
@XmlRootElement()
public class TwitterUser {

    public TwitterUser() {
    }

    public TwitterUser(String id, String name, java.lang.String avatar, java.lang.String url) {
        this.id = id;
        this.name = name;
        this.avatar = avatar;
        this.url = url;
    }
    @XmlElement
    private String id;

    /**
     * Get the value of id
     *
     * @return the value of id
     */
    public String getId() {
        return id;
    }

    /**
     * Set the value of id
     *
     * @param id new value of id
     */
    public void setId(String id) {
        this.id = id;
    }

    @XmlElement
    private String category = "user";

    /**
     * Get the value of category
     *
     * @return the value of category
     */
    public String getCategory() {
        return category;
    }

    /**
     * Set the value of category
     *
     * @param category new value of category
     */
    public void setCategory(String category) {
        this.category = category;
    }
    @XmlElement
    private String name;

    /**
     * Get the value of name
     *
     * @return the value of name
     */
    public String getName() {
        return name;
    }

    /**
     * Set the value of name
     *
     * @param name new value of name
     */
    public void setName(String name) {
        this.name = name;
    }
    @XmlElement
    private String avatar;

    /**
     * Get the value of avatar
     *
     * @return the value of avatar
     */
    public String getAvatar() {
        return avatar;
    }

    /**
     * Set the value of avatar
     *
     * @param avatar new value of avatar
     */
    public void setAvatar(String avatar) {
        this.avatar = avatar;
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
