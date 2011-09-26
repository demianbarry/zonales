/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.helpers;

import java.util.Properties;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.Message.RecipientType;
import javax.mail.internet.AddressException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

/**
 *
 * @author nacho
 */
public class SendMail {

    private String from;
    private String[] to;
    private String subject;
    private String text;
    private String smtpHost;
    private String smtpPort;

    public SendMail(String from, String[] to, String subject, String text, String smtpHost, String smtpPort) {
        this.from = from;
        this.to = to;
        this.subject = subject;
        this.text = text;
        this.smtpHost = smtpHost;
        this.smtpPort = smtpPort;
    }

    public void send() {

        Properties props = new Properties();
        props.put("mail.smtp.host", this.smtpHost);
        props.put("mail.smtp.port", this.smtpPort);

        Session mailSession = Session.getDefaultInstance(props);
        Message simpleMessage = new MimeMessage(mailSession);

        InternetAddress fromAddress = null;
        InternetAddress[] toAddress = null;
        try {
            fromAddress = new InternetAddress(from);
            for (int i = 0; i < this.to.length; i++) {
                toAddress[i] = new InternetAddress(this.to[i]);
            }
        } catch (AddressException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        try {
            simpleMessage.setFrom(fromAddress);
            simpleMessage.setRecipients(RecipientType.TO, toAddress);
            simpleMessage.setSubject(subject);
            simpleMessage.setText(text);

            Transport.send(simpleMessage);
        } catch (MessagingException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }
}
