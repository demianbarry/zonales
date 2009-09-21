/*
 * Copyright (c) 2009, Carl Dea
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. Neither the name of JFXtras nor the names of its contributors may be used
 *    to endorse or promote products derived from this software without
 *    specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * DomainModel.java - Domain objects should inherit this to have
 * property change support.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 */
package fxforms.model;

import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.beans.PropertyChangeSupport;


/**
 * This abstract class represents a domain object that supports
 * property change support for JavaBean behavior.
 * @author Carl Dea
 */
public abstract class DomainModel{
    /** property change support */
    protected final PropertyChangeSupport _pcs = new PropertyChangeSupport( this );

    /**
     * Add property change listener to respond to property change events.
     * @param listener - Listener to process property change events.
     */
    public void addPropertyChangeListener( PropertyChangeListener listener ){
        _pcs.addPropertyChangeListener( listener );
    }

    /**
     * Remove property change listener to respond to property change events.
     * @param listener
     */
    public void removePropertyChangeListener( PropertyChangeListener listener ){
        _pcs.removePropertyChangeListener( listener );
    }

    /**
     * Fires an indexed property change event based on the property, index old and
     * new values when value has changed.
     * @param propertyName - bean's property name.
     * @param index - This index of a Map or List.
     * @param oldValue - Old value
     * @param newValue - New value
     */
    public void fireIndexedPropertyChange(String propertyName, int index, Object oldValue, Object newValue){
        _pcs.fireIndexedPropertyChange(propertyName, index, oldValue, newValue);
    }

    /**
     * Fires an indexed property change event based on the property, index old and
     * new values when value has changed.
     * @param propertyName - bean's property name.
     * @param index - This index of a Map or List.
     * @param oldValue - Old value
     * @param newValue - New value
     */
    public void fireIndexedPropertyChange(String propertyName, int index, boolean oldValue, boolean newValue){
        _pcs.fireIndexedPropertyChange(propertyName, index, oldValue, newValue);
    }

    /**
     * Fires an indexed property change event based on the property, index old and
     * new values when value has changed.
     * @param propertyName - bean's property name.
     * @param index - This index of a {@link Map} or {@link List}.
     * @param oldValue - Old value as int value
     * @param newValue - New value as int value
     */
    public void fireIndexedPropertyChange(String propertyName, int index, int oldValue, int newValue){
        _pcs.fireIndexedPropertyChange(propertyName, index, oldValue, newValue);
    }

    /**
     * Fires an indexed property change event based on {@link PropertyChangeEvent}.
     * @param propertyName - bean's property name.
     * @param oldValue - Old value
     * @param newValue - New value
     */
    public void fireIndexedPropertyChange(PropertyChangeEvent pce){
        _pcs.firePropertyChange(pce);
    }

    /**
     * Fires an property change event based on the property, old and
     * new values when field value has changed.
     * @param propertyName - bean's property name.
     * @param oldValue - Old value as {@link Object}
     * @param newValue - New value as {@link Object}
     */
    public void firePropertyChange(String propertyName, Object oldValue, Object newValue){
        _pcs.firePropertyChange(propertyName, oldValue, newValue);
    }

    /**
     * Fires an property change event based on the property, old and
     * new values when field value has changed.
     * @param propertyName - bean's property name.
     * @param oldValue - Old value as int value
     * @param newValue - New value as int value
     */
    public void firePropertyChange(String propertyName, int oldValue, int newValue){
        _pcs.firePropertyChange(propertyName, oldValue, newValue);
    }

    /**
     * Fires an property change event based on the property, old and
     * new values when value has changed.
     * @param propertyName - bean's property name.
     * @param oldValue - Old value as boolean
     * @param newValue - New value as boolean
     */
    public void firePropertyChange(String propertyName, boolean oldValue, boolean newValue){
        _pcs.firePropertyChange(propertyName, oldValue, newValue);
    }
}
