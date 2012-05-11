/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.entities;

import java.util.Date;

/**
 *
 * @author rodrigo
 */
public class Comment {
   
    Date timestamp;
    String id;
    User author;
    String text;

    public Comment() {
    }

    public Comment(Date timestamp, String id, User author, String text) {
        this.timestamp = timestamp;
        this.id = id;
        this.author = author;
        this.text = text;
    }

    public User getAuthor() {
        return author;
    }

    public void setAuthor(User author) {
        this.author = author;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public Date getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(Date timestamp) {
        this.timestamp = timestamp;
    }

    
}
