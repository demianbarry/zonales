/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package mailsender.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.net.URLDecoder;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.mail.Message;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author nacho
 */
public class SendMail extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        final String SMTP_HOST_NAME = props.getProperty("SMTP_HOST_NAME");
        final String SMTP_PORT = props.getProperty("SMTP_PORT");
        final String SSL_FACTORY = props.getProperty("SSL_FACTORY");
        final String emailGoogle = props.getProperty("emailGoogle");
        final String emailGooglePassword = props.getProperty("emailGooglePassword");

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String toStr = request.getParameter("to");
        String subject = request.getParameter("subject");
        String message = request.getParameter("message");

        Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "SendMail: Message: {0}", message);

        String[] to = toStr.split(",");

        boolean debug = false;

        Properties mailProps = new Properties();
        mailProps.put("mail.smtp.host", SMTP_HOST_NAME);
        mailProps.put("mail.smtp.auth", "true");
        mailProps.put("mail.debug", "false");
        mailProps.put("mail.smtp.port", SMTP_PORT);
        mailProps.put("mail.smtp.socketFactory.port", SMTP_PORT);
        mailProps.put("mail.smtp.socketFactory.class", SSL_FACTORY);
        mailProps.put("mail.smtp.socketFactory.fallback", "false");

        Session session = Session.getDefaultInstance(mailProps,
                new javax.mail.Authenticator() {

                    @Override
                    protected PasswordAuthentication getPasswordAuthentication() {
                        return new PasswordAuthentication(emailGoogle, emailGooglePassword);
                    }
                });

        session.setDebug(debug);

        Message msg = new MimeMessage(session);
        InternetAddress addressFrom = new InternetAddress(emailGoogle);
        msg.setFrom(addressFrom);

        InternetAddress[] addressTo = new InternetAddress[to.length];
        for (int i = 0; i < to.length; i++) {
            addressTo[i] = new InternetAddress(to[i]);
        }
        msg.setRecipients(Message.RecipientType.TO, addressTo);

        msg.setSubject(subject);
        msg.setContent(message, "text/html");
        Transport.send(msg);
    }

}
