/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.plugins.publishers;

import java.util.Properties;
import org.zonales.ZGram.ZGram;

/**
 *
 * @author nacho
 */
public interface Publisher  {

    public String publish(ZGram zgram, String id, Properties props);

}
