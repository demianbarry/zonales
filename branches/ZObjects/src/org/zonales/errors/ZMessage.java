/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.errors;

/**
 *
 * @author nacho
 */
public class ZMessage {
    private int cod;
    private String msg;

    public ZMessage() {
    }

    public ZMessage(int cod, String msg) {
        this.cod = cod;
        this.msg = msg;
    }

    public int getCod() {
        return cod;
    }

    public void setCod(int cod) {
        this.cod = cod;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    @Override
    public String toString() {
        return "{\"cod\":" + cod + ",\"msg\":\"" + msg + "\"}";
    }

}
