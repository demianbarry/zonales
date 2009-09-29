/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.daos.exceptions;

/**
 *
 * @author fep
 */
public class PreexistingEntityException extends Exception {
    public PreexistingEntityException(String message, Throwable cause) {
        super(message, cause);
    }
    public PreexistingEntityException(String message) {
        super(message);
    }
}