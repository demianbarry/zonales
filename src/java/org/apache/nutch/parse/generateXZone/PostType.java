//
// This file was generated by the JavaTM Architecture for XML Binding(JAXB) Reference Implementation, vhudson-jaxb-ri-2.2-147 
// See <a href="http://java.sun.com/xml/jaxb">http://java.sun.com/xml/jaxb</a> 
// Any modifications to this file will be lost upon recompilation of the source schema. 
// Generated on: 2011.06.01 at 03:15:40 PM ART 
//

package org.apache.nutch.parse.generateXZone;

import java.util.Date;
import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlSchemaType;
import javax.xml.bind.annotation.XmlType;

/**
 * <p>Java class for postType complex type.
 * 
 * <p>The following schema fragment specifies the expected content contained within this class.
 * 
 * <pre>
 * &lt;complexType name="postType">
 *   &lt;complexContent>
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType">
 *       &lt;sequence>
 *         &lt;element name="source" type="{http://www.w3.org/2001/XMLSchema}string"/>
 *         &lt;element name="id" type="{http://www.w3.org/2001/XMLSchema}string"/>
 *         &lt;element name="fromUser" type="{}user"/>
 *         &lt;element name="toUsers" type="{}toUsersType"/>
 *         &lt;element name="title" type="{http://www.w3.org/2001/XMLSchema}string"/>
 *         &lt;element name="text" type="{http://www.w3.org/2001/XMLSchema}string"/>
 *         &lt;element name="links" type="{}linksType"/>
 *         &lt;element name="actions" type="{}actionsType"/>
 *         &lt;element name="tags" type="{}tagsType"/>
 *         &lt;element name="created" type="{http://www.w3.org/2001/XMLSchema}dateTime"/>
 *         &lt;element name="modified" type="{http://www.w3.org/2001/XMLSchema}dateTime"/>
 *         &lt;element name="relevance" type="{http://www.w3.org/2001/XMLSchema}int"/>
 *         &lt;element name="verbatim" type="{http://www.w3.org/2001/XMLSchema}string"/>
 *       &lt;/sequence>
 *     &lt;/restriction>
 *   &lt;/complexContent>
 * &lt;/complexType>
 * </pre>
 * 
 * 
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "post", propOrder = {
    "source",
    "id",
    "fromUser",
    "toUsers",
    "title",
    "text",
    "links",
    "actions",
    "tags",
    "created",
    "modified",
    "relevance",
    "verbatim"
})
public class PostType {

    @XmlElement(required = true)
    protected String source;
    @XmlElement(required = true)
    protected String id;
    @XmlElement(required = true)
    protected User fromUser;
    @XmlElement(required = false)
    protected ToUsersType toUsers;
    @XmlElement(required = true)
    protected String title;
    @XmlElement(required = true)
    protected String text;
    @XmlElement(required = false)
    protected LinksType links;
    @XmlElement(required = false)
    protected ActionsType actions;
    @XmlElement(required = true)
    protected TagsType tags;
    @XmlElement(required = true)
    @XmlSchemaType(name = "dateTime")
    protected Date created;
    @XmlElement(required = false)
    @XmlSchemaType(name = "dateTime")
    protected Date modified;
    protected int relevance;
    @XmlElement(required = true)
    protected String verbatim;

    public PostType() {
    }

    public PostType(String source, String id, User fromUser, ToUsersType toUsers, String title, String text, LinksType links, ActionsType actions, TagsType tags, Date created, Date modified, int relevance, String verbatim) {
        this.source = source;
        this.id = id;
        this.fromUser = fromUser;
        this.toUsers = toUsers;
        this.title = title;
        this.text = text;
        this.links = links;
        this.actions = actions;
        this.tags = tags;
        this.created = created;
        this.modified = modified;
        this.relevance = relevance;
        this.verbatim = verbatim;
    }

    /**
     * Gets the value of the source property.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public String getSource() {
        return source;
    }

    /**
     * Sets the value of the source property.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public void setSource(String value) {
        this.source = value;
    }

    /**
     * Gets the value of the id property.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public String getId() {
        return id;
    }

    /**
     * Sets the value of the id property.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public void setId(String value) {
        this.id = value;
    }

    /**
     * Gets the value of the fromUser property.
     * 
     * @return
     *     possible object is
     *     {@link User }
     *     
     */
    public User getFromUser() {
        return fromUser;
    }

    /**
     * Sets the value of the fromUser property.
     * 
     * @param value
     *     allowed object is
     *     {@link User }
     *     
     */
    public void setFromUser(User value) {
        this.fromUser = value;
    }

    /**
     * Gets the value of the toUsers property.
     * 
     * @return
     *     possible object is
     *     {@link ToUsersType }
     *     
     */
    public ToUsersType getToUsers() {
        return toUsers;
    }

    /**
     * Sets the value of the toUsers property.
     * 
     * @param value
     *     allowed object is
     *     {@link ToUsersType }
     *     
     */
    public void setToUsers(ToUsersType value) {
        this.toUsers = value;
    }

    /**
     * Gets the value of the title property.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public String getTitle() {
        return title;
    }

    /**
     * Sets the value of the title property.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public void setTitle(String value) {
        this.title = value;
    }

    /**
     * Gets the value of the text property.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public String getText() {
        return text;
    }

    /**
     * Sets the value of the text property.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public void setText(String value) {
        this.text = value;
    }

    /**
     * Gets the value of the links property.
     * 
     * @return
     *     possible object is
     *     {@link LinksType }
     *     
     */
    public LinksType getLinks() {
        return links;
    }

    /**
     * Sets the value of the links property.
     * 
     * @param value
     *     allowed object is
     *     {@link LinksType }
     *     
     */
    public void setLinks(LinksType value) {
        this.links = value;
    }

    /**
     * Gets the value of the actions property.
     * 
     * @return
     *     possible object is
     *     {@link ActionsType }
     *     
     */
    public ActionsType getActions() {
        return actions;
    }

    /**
     * Sets the value of the actions property.
     * 
     * @param value
     *     allowed object is
     *     {@link ActionsType }
     *     
     */
    public void setActions(ActionsType value) {
        this.actions = value;
    }

    /**
     * Gets the value of the tags property.
     * 
     * @return
     *     possible object is
     *     {@link TagsType }
     *     
     */
    public TagsType getTags() {
        return tags;
    }

    /**
     * Sets the value of the tags property.
     * 
     * @param value
     *     allowed object is
     *     {@link TagsType }
     *     
     */
    public void setTags(TagsType value) {
        this.tags = value;
    }

    /**
     * Gets the value of the created property.
     * 
     * @return
     *     possible object is
     *     {@link Date }
     *     
     */
    public Date getCreated() {
        return created;
    }

    /**
     * Sets the value of the created property.
     * 
     * @param value
     *     allowed object is
     *     {@link Date }
     *     
     */
    public void setCreated(Date value) {
        this.created = value;
    }

    /**
     * Gets the value of the modified property.
     * 
     * @return
     *     possible object is
     *     {@link Date }
     *     
     */
    public Date getModified() {
        return modified;
    }

    /**
     * Sets the value of the modified property.
     * 
     * @param value
     *     allowed object is
     *     {@link Date }
     *     
     */
    public void setModified(Date value) {
        this.modified = value;
    }

    /**
     * Gets the value of the relevance property.
     * 
     */
    public int getRelevance() {
        return relevance;
    }

    /**
     * Sets the value of the relevance property.
     * 
     */
    public void setRelevance(int value) {
        this.relevance = value;
    }

    /**
     * Gets the value of the verbatim property.
     * 
     */
    public String getVerbatim() {
        return verbatim;
    }

    /**
     * Sets the value of the verbatim property.
     * 
     */
    public void setVerbatim(String value) {
        this.verbatim = value;
    }
}


