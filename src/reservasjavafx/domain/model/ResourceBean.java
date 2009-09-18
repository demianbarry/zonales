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
 * PersonBean.java - Contains PersonBean.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Jun 14, 2009, 4:41:02 PM
 */
package reservasjavafx.domain.model;

import fxforms.model.DomainModel;

/**
 * A JavaBean with property change support.
 * @author Carl Dea
 */
public class ResourceBean extends DomainModel{
    public static final String RECURSO = "recurso";
    public static final String FECHA = "fecha";
    public static final String HORA = "hora";
    public static final String USUARIO = "usuario";

    private String recurso;
    private String fecha;
    private String hora;
    private String usuario;

    /**
     * Returns first name of the person.
     * @return
     */
    public String getRecurso() {
        return recurso;
    }

    /**
     * Sets the first name of the person.
     * @param recurso
     */
    public void setRecurso(String recurso) {
        String old = this.recurso;
        this.recurso = recurso;
        firePropertyChange(RECURSO, old, recurso);
    }

    /**
     * Returns the last name of the person.
     * @return
     */
    public String getHora() {
        return hora;
    }

    /**
     * Sets the last name of the person.
     * @param hora
     */
    public void setHora(String hora) {
        String old = this.hora;
        this.hora = hora;
        firePropertyChange(HORA, old, hora);
    }

    /**
     * Return middle name of the person.
     * @return
     */
    public String getFecha() {
        return fecha;
    }

    /**
     * Sets the middle name of the person.
     * @param fecha
     */
    public void setFecha(String fecha) {
        String old = this.fecha;
        this.fecha = fecha;
        firePropertyChange(FECHA, old, fecha);
    }

    /**
     * Return the suffix of the person's name.
     * @return
     */
    public String getUsuario() {
        return usuario;
    }

    /**
     * Sets the suffix of the person.
     * @param usuario
     */
    public void setUsuario(String usuario) {
        String old = this.usuario;
        this.usuario = usuario;
        firePropertyChange(USUARIO, old, usuario);
    }
}
