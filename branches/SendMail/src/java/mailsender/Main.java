/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package mailsender;

import java.security.Security;

/**
 *
 * @author nacho
 */
public class Main {

    private static final String[] sendTo = {"ignacioaita@sursoftware.com.ar"};

    public static void main(String args[]) throws Exception {

        Security.addProvider(new com.sun.net.ssl.internal.ssl.Provider());

        String emailSubjectTxt = "Prueba 2";
        String emailMsgTxt = "A ver que pasa ahora que separé las clases";

        SslMailSender.sendSSLMessage(sendTo, emailSubjectTxt, emailMsgTxt);

        System.out.println("Mail enviado a los destinatarios");
    }

    

}
