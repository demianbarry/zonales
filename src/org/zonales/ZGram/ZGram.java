/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram;

import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class ZGram {
    private Error error;
    private ZCrawling metadata;
    private String verbatim;

    public ZGram() {
    }

    public ZGram(Error error, ZCrawling metadata, String verbatim) {
        this.error = error;
        this.metadata = metadata;
        this.verbatim = verbatim;
    }

    public Error getError() {
        return error;
    }

    public void setError(Error error) {
        this.error = error;
    }

    public ZCrawling getMetadata() {
        return metadata;
    }

    public void setMetadata(ZCrawling metadata) {
        this.metadata = metadata;
    }

    public String getVerbatim() {
        return verbatim;
    }

    public void setVerbatim(String verbatim) {
        this.verbatim = verbatim;
    }

}
