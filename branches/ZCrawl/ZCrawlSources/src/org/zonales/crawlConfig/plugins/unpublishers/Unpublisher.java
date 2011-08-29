/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.plugins.unpublishers;

import java.util.Properties;
import org.zonales.ZGram.ZGram;

/**
 *
 * @author nacho
 */
public interface Unpublisher  {

    public String unpublish(ZGram zgram, String id, Properties props);

}
