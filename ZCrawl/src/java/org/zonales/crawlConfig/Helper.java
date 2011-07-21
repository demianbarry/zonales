/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig;

import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author nacho
 */
public class Helper {

    public static String getProperty(String name) {

        File fichero = new File("WEB-INF/");

        String[] listaArchivos = fichero.list();
        for (int i = 0; i < listaArchivos.length; i++) {
            System.out.println(listaArchivos[i]);
        }

        InputStream propFile = ClassLoader.getSystemResourceAsStream("/WEB-INF/servlet.properties");

        //File propFile = new File("servlet.properties");
        Properties properties = new Properties();

        try {
            properties.load(propFile);
        } catch (IOException ex) {
            Logger.getLogger(Helper.class.getName()).log(Level.SEVERE, null, ex);
        }

        return properties.getProperty(name);
    }
}
