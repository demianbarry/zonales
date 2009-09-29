/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.daos.exceptions;

/**
 *
 * @author fep
 */
public class RollbackFailureException extends Exception {
    public RollbackFailureException(String message, Throwable cause) {
        super(message, cause);
    }
    public RollbackFailureException(String message) {
        super(message);
    }
}