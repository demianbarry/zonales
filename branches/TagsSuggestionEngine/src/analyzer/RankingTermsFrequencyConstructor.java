package analyzer;

import java.io.File;
import java.util.TreeMap;
import org.apache.lucene.analysis.standard.StandardAnalyzer;
import org.apache.lucene.index.FilterIndexReader;


import org.apache.lucene.index.IndexReader;
import org.apache.lucene.queryParser.QueryParser;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.ScoreDoc;
import org.apache.lucene.search.TopDocsCollector;
import org.apache.lucene.search.TopScoreDocCollector;

import org.apache.lucene.store.FSDirectory;
import org.apache.lucene.util.Version;

/**
 * Constructor del ranking de frecuencias de términos.
 *
 * @author Juan Manuel Cortez   <juanmanuelcortez@gmail.com>
 */
public class RankingTermsFrequencyConstructor {

    /** index directory path */
    private static final String PATH = "/home/g2p/.netbeans/6.8/GlassFish_v3/config/solr/data/index/";

    public RankingTermsFrequencyConstructor() {
    }

    /** 
     * Genera un mapa de términos y sus respectivas frecuencias ponderadas dentro del índice.
     * 
     * @param fields recibe un array de cadenas con los fields que se deben analizar. 
     * @param id id del contenido que se desea buscar
     * 
     * @return TreeMap<String, Integer> El mapa de términos con sus frecuencias poderadas, ordenados en forma decreciente.
     */
    public TreeMap<String, Integer> getTermFreq(String[] fields, int id) throws Throwable {

        FilterIndexReader fir = new FilterIndexReader(IndexReader.open(FSDirectory.open(new File(PATH))));

        /*
         * Busco en el índice el id del documento (id interno del índice) en función del id del artículo
         * Podemos recuperar las frecuencias de los términos en función de este id interno del índice, por eso lo recuperamos.
         */
        // Intancio un searcher a partir de un index reader
        IndexSearcher searcher = new IndexSearcher(fir);
        // Instancio un colector de resultados
        TopDocsCollector collector = TopScoreDocCollector.create(1, true);
        // Creo la query a partir de un query parser, buscando el id del artículo en el field "id"
        Query q = new QueryParser(Version.LUCENE_30, "id", new StandardAnalyzer(Version.LUCENE_30)).parse(String.valueOf(id));
        // Busco
        searcher.search(q, collector);
        // Tomo la lista de documentos del índice con sus scores
        ScoreDoc[] hits = collector.topDocs().scoreDocs;
        // Me quedo, arbitrariamente, con el primero, y tomo el valor del campo doc que es el id interno del índice
        int docId = hits[0].doc;

        // mapa que contendrá los términos y su "score" de acuerdo a la frecuencia y el field
        TreeMap<String, Integer> mapTerms = new TreeMap<String, Integer>();

        // por cada field
        for (int i = 0; i < fields.length; i++) {

            // tomo el vector de términos...
            String[] terms = fir.getTermFreqVector(docId, fields[i]).getTerms();
            // ... y el de frecuencias
            int[] freqs = fir.getTermFreqVector(docId, fields[i]).getTermFrequencies();

            // por cada término
            for (int j = 0; j < terms.length && j < freqs.length; j++) {
                // is el término ya había sido guardado
                if (mapTerms.containsKey(terms[j])) {
                    // le sumo la nueva frecuencia encontrada, ponderada de acuerdo al field donde se encontró
                    mapTerms.put(terms[j], mapTerms.get(terms[j]) + Integer.valueOf(freqs[j]) * (terms[j].equals("title") ? 5 : (terms[j].equals("intro_content") ? 2 : 1)));
                // sino
                } else {
                    // agregamos el término con su frecuencia, ponderada de acuerdo al field donde se encontró
                    mapTerms.put(terms[j], Integer.valueOf(freqs[j]) * (terms[j].equals("title") ? 5 : (terms[j].equals("intro_content") ? 2 : 1)));
                }
            }
        }

        return mapTerms;
    }
}
