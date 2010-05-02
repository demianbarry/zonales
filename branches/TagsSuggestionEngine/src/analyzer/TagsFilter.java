/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package analyzer;

import java.util.HashMap;
import java.util.HashSet;
import java.util.Set;
import java.util.StringTokenizer;
import org.tartarus.snowball.ext.SpanishStemmer;

/**
 *
 * @author juanma
 */
public class TagsFilter {

    /**
     * Este método lematiza tags recibidos como un array donde los índices impares contienen el id del tag, y los pares el tag propiamente dicho.
     * Debe tenerse en cuenta que el tag puede ser una frase, por lo que se lo tokeniza para lematizar token por token.
     *
     * @param tags Array de strings donde los índice impares se espera que sean ids y los pares tags
     * @return Mapa de tags lematizados con un set de ids donde han sido encontrados
     */
    public static HashMap<String, Set<Integer>> getSteemedTags(String[] tags) {
        SpanishStemmer ss = new SpanishStemmer();

        HashMap<String, Set<Integer>> steemedTags = new HashMap<String, Set<Integer>>();

        StringTokenizer stringT;
        String current;
        HashSet idsSet;

        // por cada cadena en el array de tags...
        for (int i = 0; i < tags.length; i += 2) {
            // tokenizo el tag, que puede ser una frase
            stringT = new StringTokenizer(tags[i + 1]);
            // por cada token (cada término en el tag)
            while (stringT.hasMoreTokens()) {
                // lematizo el token
                ss.setCurrent(stringT.nextToken());
                ss.stem();
                current = ss.getCurrent();
                // si está lematizado añado el id del tag donde lo encontré de nuevo
                if (steemedTags.containsKey(current)) {
                    steemedTags.get(current).add(Integer.parseInt(tags[i]));
                    // sino
                } else {
                    // lo agrego
                    idsSet = new HashSet<Integer>();
                    idsSet.add(Integer.parseInt(tags[i]));
                    steemedTags.put(current, idsSet);
                }
            }
        }

        return steemedTags;
    }
}
