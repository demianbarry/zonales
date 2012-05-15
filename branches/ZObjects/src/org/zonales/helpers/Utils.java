/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.helpers;

/**
 *
 * @author Juanma
 */
public class Utils {

    public static String normalizeZone(String word) {
        word = word.toLowerCase();
        word = word.replaceAll(", ", ",+");
        word = word.replace(" ", "_");
        word = word.replace("+", " ");
        word = word.replace("á", "a");
        word = word.replace("é", "e");
        word = word.replace("í", "i");
        word = word.replace("ó", "o");
        word = word.replace("ú", "u");
        return word;
    }
}
