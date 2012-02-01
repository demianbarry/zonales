//
// This file was generated by the JavaTM Architecture for XML Binding(JAXB) Reference Implementation, vhudson-jaxb-ri-2.2-147 
// See <a href="http://java.sun.com/xml/jaxb">http://java.sun.com/xml/jaxb</a> 
// Any modifications to this file will be lost upon recompilation of the source schema. 
// Generated on: 2011.06.01 at 03:15:40 PM ART 
//
package org.zonales.entities;

import java.util.Date;
import java.util.List;
import org.apache.solr.client.solrj.beans.Field;

public class SolrPost {

    @Field
    protected String source;
    @Field
    protected Double sourceLatitude;
    @Field
    protected Double sourceLongitude;
    @Field
    protected String id;
    @Field
    protected String fromUserName;
    @Field
    protected String fromUserCategory;
    @Field
    protected String fromUserId;
    @Field
    protected String fromUserUrl;
    @Field
    protected Double fromUserLatitude;
    @Field
    protected Double fromUserLongitude;
    @Field
    protected String title;
    @Field
    protected String text;
    @Field
    protected String zone;
    @Field
    protected List<String> tags;
    @Field
    protected Date created;
    @Field
    protected Date modified;
    @Field
    protected int relevance;
    @Field
    protected String state;
    @Field
    protected String verbatim;

    public SolrPost() {
    }

    public SolrPost(String source, Double latitude, Double longitude, String id, String fromUserName, String fromUserCategory, String fromUserId, String fromUserUrl, Double fromUserLatitude, Double fromUserLongitude, String title, String text, String zone, List<String> tags, Date created, Date modified, int relevance, String verbatim) {
        this.source = source;
        this.sourceLatitude = latitude;
        this.sourceLongitude = longitude;
        this.id = id;
        this.fromUserName = fromUserName;
        this.fromUserCategory = fromUserCategory;
        this.fromUserId = fromUserId;
        this.fromUserUrl = fromUserUrl;
        this.fromUserLatitude = fromUserLatitude;
        this.fromUserLongitude = fromUserLongitude;
        this.title = title;
        this.text = text;
        this.zone = zone;
        this.tags = tags;
        this.created = created;
        this.modified = modified;
        this.relevance = relevance;
        this.verbatim = verbatim;
    }

    public Date getCreated() {
        return created;
    }

    public void setCreated(Date created) {
        this.created = created;
    }

    public String getFromUserCategory() {
        return fromUserCategory;
    }

    public void setFromUserCategory(String fromUserCategory) {
        this.fromUserCategory = fromUserCategory;
    }

    public String getFromUserId() {
        return fromUserId;
    }

    public void setFromUserId(String fromUserId) {
        this.fromUserId = fromUserId;
    }

    public String getFromUserName() {
        return fromUserName;
    }

    public void setFromUserName(String fromUserName) {
        this.fromUserName = fromUserName;
    }

    public String getFromUserUrl() {
        return fromUserUrl;
    }

    public void setFromUserUrl(String fromUserUrl) {
        this.fromUserUrl = fromUserUrl;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public Date getModified() {
        return modified;
    }

    public void setModified(Date modified) {
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

    public List<String> getTags() {
        return tags;
    }

    public void setTags(List<String> tags) {
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

    public String getState() {
        return state;
    }

    public void setState(String state) {
        this.verbatim = state;
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

    public Double getFromUserLatitude() {
        return fromUserLatitude;
    }

    public void setFromUserLatitude(Double fromUserLatitude) {
        this.fromUserLatitude = fromUserLatitude;
    }

    public Double getFromUserLongitude() {
        return fromUserLongitude;
    }

    public void setFromUserLongitude(Double fromUserLongitude) {
        this.fromUserLongitude = fromUserLongitude;
    }

    public Double getSourceLatitude() {
        return sourceLatitude;
    }

    public void setSourceLatitude(Double sourceLatitude) {
        this.sourceLatitude = sourceLatitude;
    }

    public Double getSourceLongitude() {
        return sourceLongitude;
    }

    public void setSourceLongitude(Double sourceLongitude) {
        this.sourceLongitude = sourceLongitude;
    }

    @Override
    public String toString() {
        return "SolrPost{" + "source=" + source + "id=" + id + "fromUserName=" + fromUserName + "fromUserCategory=" + fromUserCategory + "fromUserId=" + fromUserId + "fromUserUrl=" + fromUserUrl + "title=" + title + "text=" + text + "zone=" + zone + "tags=" + tags + "created=" + created + "modified=" + modified + "relevance=" + relevance + "verbatim=" + verbatim + '}';
    }

    @Override
    public boolean equals(Object obj) {
        if (obj != null && obj instanceof SolrPost) {
            SolrPost solrPost = (SolrPost) obj;
            return solrPost.getCreated().equals(this.getCreated()) && solrPost.getModified().equals(this.getModified()) && solrPost.getId().equals(this.getId());
        } else {
            return false;
        }
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 37 * hash + (this.id != null ? this.id.hashCode() : 0);
        hash = 37 * hash + (this.created != null ? this.created.hashCode() : 0);
        hash = 37 * hash + (this.modified != null ? this.modified.hashCode() : 0);
        return hash;
    }
}
