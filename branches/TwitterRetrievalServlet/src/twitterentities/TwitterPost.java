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
public class TwitterPost {

    public TwitterPost() {
    }

    public TwitterPost(String id, TwitterUser from, TwitterUser[] to, String title, String text, TwitterLink[] links, TwitterAction[] adds, String created, String modified, Integer relevance) {
        this.id = id;
        this.from = from;
        this.toUsers = to;
        this.title = title;
        this.text = text;
        this.links = links;
        this.actions = adds;
        this.created = created;
        this.modified = modified;
        this.relevance = relevance;
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
    protected TwitterUser from;

    /**
     * Get the value of from
     *
     * @return the value of from
     */
    public TwitterUser getFrom() {
        return from;
    }

    /**
     * Set the value of from
     *
     * @param from new value of from
     */
    public void setFrom(TwitterUser from) {
        this.from = from;
    }

    @XmlElement
    private TwitterUser[] toUsers;

    /**
     * Get the value of to
     *
     * @return the value of to
     */
    public TwitterUser[] getTo() {
        return toUsers;
    }

    /**
     * Set the value of to
     *
     * @param to new value of to
     */
    public void setTo(TwitterUser[] to) {
        this.toUsers = to;
    }

    @XmlElement
    private String title;

    /**
     * Get the value of tittle
     *
     * @return the value of tittle
     */
    public String getTittle() {
        return title;
    }

    /**
     * Set the value of tittle
     *
     * @param tittle new value of tittle
     */
    public void setTittle(String tittle) {
        this.title = tittle;
    }

    @XmlElement
    private String text;

    /**
     * Get the value of text
     *
     * @return the value of text
     */
    public String getText() {
        return text;
    }

    /**
     * Set the value of text
     *
     * @param text new value of text
     */
    public void setText(String text) {
        this.text = text;
    }

    @XmlElement
    private TwitterLink[] links;

    /**
     * Get the value of links
     *
     * @return the value of links
     */
    public TwitterLink[] getLinks() {
        return links;
    }

    /**
     * Set the value of links
     *
     * @param links new value of links
     */
    public void setLinks(TwitterLink[] links) {
        this.links = links;
    }
    
    private TwitterAction[] actions;

    /**
     * Get the value of adds
     *
     * @return the value of adds
     */
    public TwitterAction[] getActions() {
        return actions;
    }

    /**
     * Set the value of adds
     *
     * @param actions new value of adds
     */
    public void setActions(TwitterAction[] actions) {
        this.actions = actions;
    }

    @XmlElement
    private String created;

    /**
     * Get the value of created
     *
     * @return the value of created
     */
    public String getCreated() {
        return created;
    }

    /**
     * Set the value of created
     *
     * @param created new value of created
     */
    public void setCreated(String created) {
        this.created = created;
    }

    @XmlElement
    private String modified;

    /**
     * Get the value of modified
     *
     * @return the value of modified
     */
    public String getModified() {
        return modified;
    }

    /**
     * Set the value of modified
     *
     * @param modified new value of modified
     */
    public void setModified(String modified) {
        this.modified = modified;
    }

    @XmlElement
    private Integer relevance;

    /**
     * Get the value of relevance
     *
     * @return the value of relevance
     */
    public Integer getRelevance() {
        return relevance;
    }

    /**
     * Set the value of relevance
     *
     * @param relevance new value of relevance
     */
    public void setRelevance(Integer relevance) {
        this.relevance = relevance;
    }

}
