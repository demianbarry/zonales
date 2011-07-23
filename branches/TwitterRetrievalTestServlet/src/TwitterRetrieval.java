/*
 * Copyright 2004 The Apache Software Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/* $Id$
 *
 */

import com.google.gson.Gson;
import entities.ActionType;
import entities.PostType;
import java.io.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import entities.PostsType;
import entities.TagsType;
import entities.ToUsersType;
import entities.User;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

/**
 * Example servlet showing request headers
 *
 * @author 
 */
public class TwitterRetrieval extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {

        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();
        
        try {
            String query = request.getParameter("q");
            String tags = request.getParameter("tags");
            String [] tag;
            String url = "http://search.twitter.com/search?q=" + query + "&result_type=recent";//args[0];
            out = response.getWriter();
            tag = tags.split(",");
          
            Document doc = Jsoup.connect(url.replace(" ", "+")).get();
            //out.println("Fetching url: " + url);

            //  Elements test = doc.select("div.msg");
            Elements posteo = doc.select("ul li.result"); //obtengo los post.
            // Elements media = doc.select("[src]");
            
            String src;
            

            List<PostType> postsList = new ArrayList();
            PostType post;
            //List<LinkType> links = new ArrayList();
            List<ActionType> actions;
            List<User> toUsers = new ArrayList();
            List<TagsType> tagsList = new ArrayList();
            /***************************/
            for (Element link : posteo) {

                src = link.html();

                post = new PostType();
                post.setSource("Twitter");
                post.setId(Jsoup.parse(src).select("a.lit").get(0).attr("href"));

                if(Jsoup.parse(src).select("a.username").size() > 0)
                post.setFromUser(new User(Jsoup.parse(src).select("a.username").get(0).attr("href"),
                        Jsoup.parse(src).select("a.username").get(0).text(),
                        Jsoup.parse(src).select("div.avatar a img").size() > 0 ? Jsoup.parse(src).select("div.avatar a img").get(0).attr("src") : "",
                        "user"));
                if(Jsoup.parse(src).select(".msgtxt a.username ").size() > 0){
                    toUsers = new ArrayList();
                    for (Element toUser : Jsoup.parse(src).select(".msgtxt a.username ")) {
                        toUsers.add(new User(toUser.attr("href"),
                                toUser.text(),
                             "",
                             "user"));
                    }
                }
                
                post.setToUsers(new ToUsersType(toUsers));
                //System.out.println("<P<OST>" + Jsoup.parse(src).select("a.username").get(0).text() + "</POST>");
                //
                post.setTitle(Jsoup.parse(src).select(".msgtxt").get(0).text().substring(0, Jsoup.parse(src).select(".msgtxt").get(0).text().length() > 30 ? 30 : Jsoup.parse(src).select(".msgtxt").get(0).text().length() - 1));
                post.setText(Jsoup.parse(src).select(".msgtxt").get(0).text());

                String tiempo = Jsoup.parse(src).select("div.info").size() > 0 ? Jsoup.parse(src).select("div.info").get(0).text() : "";
                Calendar calendar = Calendar.getInstance();
                if (tiempo.matches("^.*[0-9].+minutes.+")) {
                    calendar.add(Calendar.MINUTE, Integer.parseInt(tiempo.substring(0, tiempo.indexOf(" "))) * (-1));
                    //   System.out.println("<POST>" + calendar.getTime() + " - " + tiempo + "</POST>");
                } else if (tiempo.matches("^.*[0-9].+hour.+")) {
                    calendar.add(Calendar.HOUR, Integer.parseInt(tiempo.substring(tiempo.indexOf(" ") + 1, tiempo.indexOf(" ", tiempo.indexOf(" ") + 1))) * (-1));
                    //   System.out.println("<POST>" + calendar.getTime() + " - " + tiempo + "</POST>");
                }

                post.setCreated(calendar.getTime());
                post.setModified(calendar.getTime());
                post.setRelevance(0);

                

                postsList.add(post);

            }
            PostsType posts = new PostsType(postsList);
            response.setContentType("text/javascript");
            
            out.println((new Gson()).toJson(posts));


            /*

            

            /*for (Tweet tweet : (List<Tweet>) result.getTweets()) {
            actions = new ArrayList();
            //actions.add(new ActionType("retweets", twitter.getRetweetedByIDs(tweet.getId()).getIDs().length));
            //actions.add(new ActionType("replies", twitter.getRelatedResults(tweet.getId()).getTweetsWithReply().size()));
            
            post = new PostType();
            post.setSource("Twitter");
            post.setId(String.valueOf(tweet.getId()));
            post.setFromUser(new User(String.valueOf(tweet.getFromUserId()),
            tweet.getFromUser(),
            tweet.getProfileImageUrl(),
            tweet.getSource()));
            
            if (tweet.getToUser() != null) {
            toUsers.add(new User(String.valueOf(tweet.getToUserId()),
            tweet.getToUser(),
            null,
            tweet.getSource()));
            post.setToUsers(new ToUsersType(toUsers));
            }
            
            post.setTitle(tweet.getText().substring(0, tweet.getText().length() > 30 ? 30 : tweet.getText().length() - 1));
            post.setText(tweet.getText());
            //post.setLinks(new LinksType(links));
            post.setActions(new ActionsType(actions));
            post.setCreated(tweet.getCreatedAt());
            post.setModified(tweet.getCreatedAt());
            post.setRelevance(actions.get(0).getCant() * 3 + actions.get(1).getCant());
            
            postsList.add(post);
            }*/
            /*PostsType posts = new PostsType(postsList);
            Gson gson = new Gson();
            if ("json".equalsIgnoreCase(request.getParameter("format"))) {
            response.setContentType("text/javascript");
            out = response.getWriter();
            out.println(gson.toJson(posts));
            } else {
            response.setContentType("application/xml");
            out = response.getWriter();
            try {
            for (PostType postIt : posts.getPost()) {
            postIt.setVerbatim(gson.toJson(postIt));
            }
            Twitter2XML(posts, out);
            } catch (Exception ex) {
            Logger.getLogger(TwitterRetrieval.class.getName()).log(Level.SEVERE, null, ex);
            }
            }*/


        } catch (Exception ex) {
            Logger.getLogger(TwitterRetrieval.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public void doPost(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {
        doGet(request, response);
    }

    private void Twitter2XML(PostsType posts, PrintWriter out) throws Exception {
        JAXBContext context = JAXBContext.newInstance("entities");
        Marshaller marshaller = context.createMarshaller();
        marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
        marshaller.marshal(posts, out);
    }

    private void TwitterParser() {
        throw new UnsupportedOperationException("Not yet implemented");

    }
}
