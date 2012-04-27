//
// This file was generated by the JavaTM Architecture for XML Binding(JAXB) Reference Implementation, vhudson-jaxb-ri-2.2-147 
// See <a href="http://java.sun.com/xml/jaxb">http://java.sun.com/xml/jaxb</a> 
// Any modifications to this file will be lost upon recompilation of the source schema. 
// Generated on: 2011.06.01 at 03:15:40 PM ART 
//
package org.zonales.entities;

import java.util.Date;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.solr.client.solrj.beans.Field;

public class SolrPost {

    @Field
    protected String docType;
    @Field
    protected String source;
    @Field
    protected Double postLatitude;
    @Field
    protected Double postLongitude;
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
    protected String fromUserPlaceId;
    @Field
    protected String fromUserPlaceName;
    @Field
    protected String fromUserPlaceType;
    @Field
    protected String title;
    @Field
    protected String text;
    @Field
    protected Date created;
    @Field
    protected Date modified;
    @Field
    protected int relevance;
    @Field
    protected int relevanceDelta;
    @Field
    protected List<String> tags;
    @Field
    protected String zoneId;
    @Field
    protected String zoneName;
    @Field
    protected String zoneType;
    @Field
    protected String zone;
    @Field
    protected String extendedString;
    @Field
    protected String zoneExtendedString;
    @Field
    protected String state;
    @Field
    protected String verbatim;

    public SolrPost() {
    }

    public SolrPost(String docType, String source, Double postLatitude, Double postLongitude, String id, String fromUserName, String fromUserCategory, String fromUserId, String fromUserUrl, String fromUserPlaceId, String fromUserPlaceName, String fromUserPlaceType, String title, String text, String zoneId, String zoneName, String zoneType, String extendedString, String zoneExtendedString, List<String> tags, Date created, Date modified, int relevance, int relevanceDelta, String state, String verbatim) {
        this.docType = docType;
        this.source = source;
        this.postLatitude = postLatitude;
        this.postLongitude = postLongitude;
        this.id = id;
        this.fromUserName = fromUserName;
        this.fromUserCategory = fromUserCategory;
        this.fromUserId = fromUserId;
        this.fromUserUrl = fromUserUrl;
        this.fromUserPlaceId = fromUserPlaceId;
        this.fromUserPlaceName = fromUserPlaceName;
        this.fromUserPlaceType = fromUserPlaceType;
        this.title = title;
        this.text = text;
        this.created = created;
        this.modified = modified;
        this.relevance = relevance;
        this.relevanceDelta = relevanceDelta;
        this.tags = tags;
        this.zoneId = zoneId;
        this.zoneName = zoneName;
        this.zoneType = zoneType;
        this.zone = zoneName;
        this.extendedString = extendedString;
        this.zoneExtendedString = zoneExtendedString;
        this.state = state;
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

    public Double getSourceLatitude() {
        return postLatitude;
    }

    public void setSourceLatitude(Double sourceLatitude) {
        this.postLatitude = sourceLatitude;
    }

    public Double getSourceLongitude() {
        return postLongitude;
    }

    public void setSourceLongitude(Double sourceLongitude) {
        this.postLongitude = sourceLongitude;
    }

    public String getDocType() {
        return docType;
    }

    public void setDocType(String docType) {
        this.docType = docType;
    }

    public int getRelevanceDelta() {
        return relevanceDelta;
    }

    public void setRelevanceDelta(int relevanceDelta) {
        this.relevanceDelta = relevanceDelta;
    }

    public String getExtendedString() {
        return extendedString;
    }

    public void setExtendedString(String extendedString) {
        this.extendedString = extendedString;
    }

    public String getZoneExtendedString() {
        return zoneExtendedString;
    }

    public void setZoneExtendedString(String zoneExtendedString) {
        this.zoneExtendedString = zoneExtendedString;
    }

    public String getFromUserPlaceId() {
        return fromUserPlaceId;
    }

    public void setFromUserPlaceId(String fromUserPlaceId) {
        this.fromUserPlaceId = fromUserPlaceId;
    }

    public String getFromUserPlaceName() {
        return fromUserPlaceName;
    }

    public void setFromUserPlaceName(String fromUserPlaceName) {
        this.fromUserPlaceName = fromUserPlaceName;
    }

    public String getFromUserPlaceType() {
        return fromUserPlaceType;
    }

    public void setFromUserPlaceType(String fromUserPlaceType) {
        this.fromUserPlaceType = fromUserPlaceType;
    }

    public Double getPostLatitude() {
        return postLatitude;
    }

    public void setPostLatitude(Double postLatitude) {
        this.postLatitude = postLatitude;
    }

    public Double getPostLongitude() {
        return postLongitude;
    }

    public void setPostLongitude(Double postLongitude) {
        this.postLongitude = postLongitude;
    }

    public String getZoneId() {
        return zoneId;
    }

    public void setZoneId(String zoneId) {
        this.zoneId = zoneId;
    }

    public String getZoneName() {
        return zoneName;
    }

    public void setZoneName(String zoneName) {
        this.zoneName = zoneName;
        this.zone = zoneName;
    }

    public String getZoneType() {
        return zoneType;
    }

    public void setZoneType(String zoneType) {
        this.zoneType = zoneType;
    }
    
    @Override
    public String toString() {
        return "SolrPost{" + "source=" + source + "id=" + id + "fromUserName=" + fromUserName + "fromUserCategory=" + fromUserCategory + "fromUserId=" + fromUserId + "fromUserUrl=" + fromUserUrl + "title=" + title + "text=" + text + "zone=" + zoneId + "tags=" + tags + "created=" + created + "modified=" + modified + "relevance=" + relevance + "verbatim=" + verbatim + '}';
    }

    @Override
    public boolean equals(Object obj) {
        if (obj != null && obj instanceof SolrPost) {
            SolrPost solrPost = (SolrPost) obj;
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "CREADO ES IGUAL? {0}", solrPost.getCreated().equals(this.getCreated()));
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "MODIFICADO ES IGUAL? {0}", solrPost.getModified().equals(this.getModified()));
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "ID ES IGUAL? {0}", solrPost.getId().equals(this.getId()));
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RELEVANCIA ES IGUAL? {0}", solrPost.getRelevance() == this.getRelevance() - this.getRelevanceDelta());
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RELEVANCIA THIS: {0}", this.getRelevance());
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RELEVANCIA THIS DELTA: {0}", this.getRelevanceDelta());
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RELEVANCIA COMPRARE: {0}", solrPost.getRelevance());
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "RELEVANCIA COMPARE DELTA: {0}", solrPost.getRelevanceDelta());

            return solrPost.getCreated().equals(this.getCreated()) &&
                   solrPost.getModified().equals(this.getModified()) &&
                   solrPost.getId().equals(this.getId()) &&
                   solrPost.getRelevance() - solrPost.getRelevanceDelta() == this.getRelevance() - this.getRelevanceDelta();
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
