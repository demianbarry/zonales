/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.zonales.persistence.daos.exceptions;

/**
 *
 * @author fep
 */
public class NonexistentEntityException extends Exception {
    public NonexistentEntityException(String message, Throwable cause) {
        super(message, cause);
    }
    public NonexistentEntityException(String message) {
        super(message);
    }
}
