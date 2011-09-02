/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler.exceptions;

import org.zonales.errors.ZMessage;

/**
 *
 * @author nacho
 */
public class ExtractException extends Exception {

    private ZMessage zmessage;

    public ExtractException() {
    }

    public ExtractException(ZMessage zmessage) {
        this.zmessage = zmessage;
    }

    public ExtractException(String message, ZMessage zmessage) {
        this.zmessage = zmessage;
    }


    public ZMessage getZmessage() {
        return zmessage;
    }

    public void setZmessage(ZMessage zmessage) {
        this.zmessage = zmessage;
    }


}
