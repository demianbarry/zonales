/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.plugins.urlgetters;

import org.zonales.crawlConfig.objets.Service;
import org.zonales.crawlParser.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public interface GetServiceURL  {

    public String getURL(ZCrawling metadata, Service service);

}
