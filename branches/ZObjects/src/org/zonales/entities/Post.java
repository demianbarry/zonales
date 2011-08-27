//
// This file was generated by the JavaTM Architecture for XML Binding(JAXB) Reference Implementation, vhudson-jaxb-ri-2.2-147 
// See <a href="http://java.sun.com/xml/jaxb">http://java.sun.com/xml/jaxb</a> 
// Any modifications to this file will be lost upon recompilation of the source schema. 
// Generated on: 2011.06.01 at 03:15:40 PM ART 
//
package org.zonales.entities;

import java.util.ArrayList;


public class Post {

    protected String source;
    protected String id;
    protected User fromUser;
    protected ArrayList<User> toUsers;
    protected String title;
    protected String text;
    protected ArrayList<LinkType> links;
    protected ArrayList<ActionType> actions;
    protected String zone;
    protected ArrayList<String> tags;
    protected Long created;
    protected Long modified;
    protected int relevance;
    protected String verbatim;

    public Post() {
    }

    public Post(String source, String id, User fromUser, ArrayList<User> toUsers, String title, String text, ArrayList<LinkType> links, ArrayList<ActionType> actions, String zone, ArrayList<String> tags, Long created, Long modified, int relevance, String verbatim) {
        this.source = source;
        this.id = id;
        this.fromUser = fromUser;
        this.toUsers = toUsers;
        this.title = title;
        this.text = text;
        this.links = links;
        this.actions = actions;
        this.zone = zone;
        this.tags = tags;
        this.created = created;
        this.modified = modified;
        this.relevance = relevance;
        this.verbatim = verbatim;
    }

    public ArrayList<ActionType> getActions() {
        return actions;
    }

    public void setActions(ArrayList<ActionType> actions) {
        this.actions = actions;
    }

    public Long getCreated() {
        return created;
    }

    public void setCreated(Long created) {
        this.created = created;
    }

    public User getFromUser() {
        return fromUser;
    }

    public void setFromUser(User fromUser) {
        this.fromUser = fromUser;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public ArrayList<LinkType> getLinks() {
        return links;
    }

    public void setLinks(ArrayList<LinkType> links) {
        this.links = links;
    }

    public Long getModified() {
        return modified;
    }

    public void setModified(Long modified) {
        this.modified = modified;
    }

    public int getRelevance() {
        return relevance;
    }

    public void setRelevance(int relevance) {
        this.relevance = relevance;
    }

    public String getSource() {
        return source;
    }

    public void setSource(String source) {
        this.source = source;
    }

    public ArrayList<String> getTags() {
        return tags;
    }

    public void setTags(ArrayList<String> tags) {
        this.tags = tags;
    }

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public ArrayList<User> getToUsers() {
        return toUsers;
    }

    public void setToUsers(ArrayList<User> toUsers) {
        this.toUsers = toUsers;
    }

    public String getVerbatim() {
        return verbatim;
    }

    public void setVerbatim(String verbatim) {
        this.verbatim = verbatim;
    }

    public String getZone() {
        return zone;
    }

    public void setZone(String zone) {
        this.zone = zone;
    }

    @Override
    public String toString() {
        return "Post{" + "source=" + source + "id=" + id + "fromUser=" + fromUser + "toUsers=" + toUsers + "title=" + title + "text=" + text + "links=" + links + "actions=" + actions + "tags=" + tags + "created=" + created + "modified=" + modified + "relevance=" + relevance + "verbatim=" + verbatim + '}';
    }

    


}
