/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

import java.util.Date;

/**
 *
 * @author nacho
 */
public class CrawlsTest {

    //Consulta en el lenguaje definido en el ABNF
    private String query;

    //Metadata resultante de la consulta
    private String metadata;

    //Resultados de la consulta
    private String results;

    //Fuente
    private String source;

    //Fecha del test
    private Date testDate;

    public CrawlsTest() {
    }

    public CrawlsTest(String query, String metadata, String results) {
        this.query = query;
        this.metadata = metadata;
        this.results = results;
    }

    public String getMetadata() {
        return metadata;
    }

    public void setMetadata(String metadata) {
        this.metadata = metadata;
    }

    public String getQuery() {
        return query;
    }

    public void setQuery(String query) {
        this.query = query;
    }

    public String getResults() {
        return results;
    }

    public void setResults(String results) {
        this.results = results;
    }
    

}
