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
 * BeanUtil.java - Contains BeanUtil.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Aug 13, 2009, 1:32:42 AM
 */
package fxforms.utils;

import java.beans.BeanInfo;
import java.beans.IntrospectionException;
import java.beans.Introspector;
import java.beans.PropertyDescriptor;
import java.lang.reflect.Method;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * This utility class using reflection to retrieve and set POJOs using
 * JavaBean accessors / mutators getters/setters
 *
 * @author Carl Dea
 */
public final class BeanUtil {

    private BeanUtil(){
    }

    /**
     * Returns value based on the propertyName.
     * @param bean - Bean to be used
     * @param propertyName - Property to get
     * @return property Value
     */
    public static Object getValue(Object bean, String propertyName) {
        if (bean == null) {return null;}
        PropertyDescriptor propertyDescriptor = null;
        BeanInfo info = null;
        try {
            info = Introspector.getBeanInfo(bean.getClass());
            if (info != null){
                for (PropertyDescriptor element : info.getPropertyDescriptors()) {
                    if (propertyName.equals(element.getName())) {
                        propertyDescriptor = element;
                    }
                }
                Method getter = propertyDescriptor.getReadMethod();
                try {
                    return getter.invoke(bean, (Object[]) null);
                } catch (Exception e) {
                    throw new RuntimeException(e);
                }
            }
        } catch (IntrospectionException ex) {
            Logger.getLogger(BeanUtil.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    /**
     * Sets a beans value on a property.
     * @param bean - Bean to be used
     * @param propertyName - Property to set
     * @param value - The value to set property value to.
     */
    public static void setValue(Object bean, String propertyName, Object value){
        if (bean == null) {return;}
        
        PropertyDescriptor propertyDescriptor = null;
        BeanInfo info = null;
        try {
            info = Introspector.getBeanInfo(bean.getClass());
            if (info != null){
                for (PropertyDescriptor element : info.getPropertyDescriptors()) {
                    if (propertyName.equals(element.getName())) {
                        propertyDescriptor = element;
                    }
                }
                Method setter = propertyDescriptor.getWriteMethod();
                try {
                    setter.invoke(bean, new Object[] { value });
                } catch (Exception e) {
                    throw new RuntimeException(e);
                }
            }
        } catch (IntrospectionException ex) {
            Logger.getLogger(BeanUtil.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
