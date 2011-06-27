/**
- Ocultar texto citado -
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package org.apache.nutch.indexer.feed;

//JDK imports
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;

//APACHE imports
import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.io.Text;
import org.apache.nutch.crawl.CrawlDatum;
import org.apache.nutch.crawl.Inlinks;
import org.apache.nutch.indexer.IndexingException;
import org.apache.nutch.indexer.IndexingFilter;
import org.apache.nutch.indexer.NutchDocument;
import org.apache.nutch.indexer.lucene.LuceneWriter;
import org.apache.nutch.metadata.Feed;
import org.apache.nutch.metadata.Metadata;
import org.apache.nutch.parse.Parse;
import org.apache.nutch.parse.ParseData;

/**
 * @author dogacan
 * @author mattmann
 * @since NUTCH-444
 *
 * An {@link IndexingFilter} implementation to pull out the
 * relevant extracted {@link Metadata} fields from the RSS feeds
 * and into the index.
 *
 */
public class FeedIndexingFilter implements IndexingFilter {

   // public static final String dateFormatStr = "yyyyMMddHHmm";
    public static final String dateFormatStr = "yyyy'-'MM'-'dd'T'HH:mm:ss.S'Z'";
    private Configuration conf;
    private final static String PUBLISHED_DATE = "publishedDate";
    private final static String UPDATED_DATE = "updatedDate";

    /**
     * Extracts out the relevant fields:
     *
     * <ul>
     *  <li>FEED_AUTHOR</li>
     *  <li>FEED_TAGS</li>
     *  <li>FEED_PUBLISHED</li>
     *  <li>FEED_UPDATED</li>
     *  <li>FEED</li>
     * </ul>
     *
     * And sends them to the {@link Indexer} for indexing within the Nutch
     * index.
     *
     */
    public NutchDocument filter(NutchDocument doc, Parse parse, Text url, CrawlDatum datum,
            Inlinks inlinks) throws IndexingException {
        ParseData parseData = parse.getData();
        Metadata parseMeta = parseData.getParseMeta();
        String tagString = "";


        String[] authors = parseMeta.getValues(Feed.FEED_AUTHOR);
        String[] tags = parseMeta.getValues(Feed.FEED_TAGS);
        String published = parseMeta.get(Feed.FEED_PUBLISHED);
        String updated = parseMeta.get(Feed.FEED_UPDATED);
        String feed = parseMeta.get(Feed.FEED);
        String feed_title = parseMeta.get(Feed.FEED_TITLE);
        String feed_text = parseMeta.get(Feed.FEED_TEXT);
        String feed_id = parseMeta.get(Feed.FEED_ID);
        String[] feed_links = parseMeta.getValues(Feed.FEED_LINKS);
        String[] feed_actions = parseMeta.getValues(Feed.FEED_ACTIONS);
        String feed_relevance = parseMeta.get(Feed.FEED_RELEVANCE);
        String feed_verbatim = parseMeta.get(Feed.FEED_VERBATIM);

	 doc.removeField("site");
         doc.removeField("host");
         doc.removeField("tstamp");
         doc.removeField("url");
         doc.removeField("title");
         doc.removeField("content");
	 doc.removeField("segments");

        if (authors != null && authors.length > 0) {
            doc.add("fromuser_name", authors[0]);
            /*for (String author : authors) {
            doc.add("fromUser", author);

            }*/
        }


        if (tags != null) {
            for (String tag : tags) {
                tagString += "<tag>" + tag + "</tag>";
            }
        }
        doc.add("tags", tagString);

        if (feed != null) {
            doc.add("source", feed);
        }

        if (feed_title != null) {
            doc.add("title", feed_title);
        }

        if (feed_id != null) {
            doc.add("id", feed_id);
        }

        /*if (feed_links != null)
        for (String link : feed_links) {
        doc.add("links", link);
        }*/

        /*if (feed_actions != null)
        for (String action : feed_actions) {
        doc.add("actions", action);
        }*/

        if (feed_relevance != null) {
            doc.add("relevance", Integer.valueOf(feed_relevance));
        }

        SimpleDateFormat sdf = new SimpleDateFormat(
                dateFormatStr);
        sdf.setTimeZone(TimeZone.getTimeZone("GMT"));
        if (published != null) {
            Date date = new Date(Long.parseLong(published));
            doc.add("created",  sdf.format(date));

        }

        if (updated != null) {
            Date date = new Date(Long.parseLong(updated));
            doc.add("modified",  sdf.format(date));
        }

        if (feed_verbatim != null) {
            doc.add("verbatim", feed_verbatim);
        }

         if (feed_text != null) {
             doc.add("text", feed_text);
        }



        return doc;
    }

    /**
     * @return the {@link Configuration} object used to configure
     * this {@link IndexingFilter}.
     */
    public Configuration getConf() {
        return conf;
    }

    public void addIndexBackendOptions(Configuration conf) {
        LuceneWriter.addFieldOptions(Feed.FEED_AUTHOR,
                LuceneWriter.STORE.YES, LuceneWriter.INDEX.TOKENIZED, conf);

        LuceneWriter.addFieldOptions(Feed.FEED_TAGS,
                LuceneWriter.STORE.YES, LuceneWriter.INDEX.TOKENIZED, conf);

        LuceneWriter.addFieldOptions(Feed.FEED,
                LuceneWriter.STORE.YES, LuceneWriter.INDEX.TOKENIZED, conf);

        LuceneWriter.addFieldOptions(PUBLISHED_DATE,
                LuceneWriter.STORE.YES, LuceneWriter.INDEX.NO_NORMS, conf);

        LuceneWriter.addFieldOptions(UPDATED_DATE,
                LuceneWriter.STORE.YES, LuceneWriter.INDEX.NO_NORMS, conf);

    }

    /**
     * Sets the {@link Configuration} object used to configure this
     * {@link IndexingFilter}.
     *
     * @param conf The {@link Configuration} object used to configure
     * this {@link IndexingFilter}.
     */
    public void setConf(Configuration conf) {
        this.conf = conf;
    }
}
