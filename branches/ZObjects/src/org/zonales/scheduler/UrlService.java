/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import org.zonales.errors.ZMessage;

/**
 *
 * @author nacho
 */
public class UrlService extends ZMessage {

    private String url;

    public UrlService() {
    }

    public UrlService(int cod, String msg, String url) {
        super(cod, msg);
        this.url = url;
    }

    public UrlService(String url) {
        this.url = url;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

}
