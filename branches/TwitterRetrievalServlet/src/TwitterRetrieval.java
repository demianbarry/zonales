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
import java.io.*;
import java.util.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import twitter4j.Query;
import twitter4j.QueryResult;
import twitter4j.Tweet;
import twitter4j.Twitter;
import twitter4j.TwitterException;
import twitter4j.TwitterFactory;
import twitter4j.conf.ConfigurationBuilder;
import entities.ActionType;
import entities.ActionsType;
import entities.PostType;
import entities.PostsType;
import entities.TagsType;
import entities.ToUsersType;
import entities.User;

/**
 * Example servlet showing request headers
 *
 * @author James Duncan Davidson <duncan@eng.sun.com>
 */
public class TwitterRetrieval extends HttpServlet {

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {

        response.setCharacterEncoding("UTF-8");
        PrintWriter out;
        try {


            // img stuff not req'd for source code html showing
            // all links relative
            // XXX
            // making these absolute till we work out the
            // addition of a PathInfo issue

            ConfigurationBuilder cb = new ConfigurationBuilder();
            cb.setDebugEnabled(true).setOAuthConsumerKey("56NAE9lQHSOZIGXRktd5Qw").setOAuthConsumerSecret("zJjJrUUs1ubwKjtPOyYzrwBJzpwq7ud8Aryq1VhYH2E").setOAuthAccessTokenURL("https://api.twitter.com/oauth/access_token").setOAuthRequestTokenURL("https://api.twitter.com/oauth/request_token").setOAuthAuthorizationURL("https://api.twitter.com/oauth/authorize").setOAuthAccessToken("234742739-I1l0VGTTjRUbZrfH1jvKnTVFU9ZEvkxxUDpvsAJ2").setOAuthAccessTokenSecret("jLe3imI3JiPgmHCatt6SqYgRAcX5q8s6z38oUrqMc");

            TwitterFactory tf = new TwitterFactory(cb.build());
            Twitter twitter = tf.getInstance();

            Query query = new Query(request.getParameter("q"));

            String tags = request.getParameter("tags");
            String[] tagsArray = null;
            if (tags != null) {
                tagsArray = tags.split(",");
            }

            QueryResult result;

            result = twitter.search(query);

            List<PostType> postsList = new ArrayList();

            PostType post;
            //List<LinkType> links = new ArrayList();
            List<ActionType> actions;
            List<User> toUsers = new ArrayList();

            for (Tweet tweet : (List<Tweet>) result.getTweets()) {
                actions = new ArrayList();
                actions.add(new ActionType("retweets", twitter.getRetweetedByIDs(tweet.getId()).getIDs().length));
                actions.add(new ActionType("replies", twitter.getRelatedResults(tweet.getId()).getTweetsWithReply().size()));

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
                post.setCreated(String.valueOf(tweet.getCreatedAt().getTime()));
                post.setModified(String.valueOf(tweet.getCreatedAt().getTime()));
                post.setRelevance(actions.get(0).getCant() * 3 + actions.get(1).getCant());

                if (tagsArray != null && tagsArray.length > 0) {
                    post.setTags(new TagsType(Arrays.asList(tagsArray)));
                }

                postsList.add(post);
            }
            PostsType posts = new PostsType(postsList);
            Gson gson = new Gson();
            if ("xml".equalsIgnoreCase(request.getParameter("format"))) {
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
            } else {
                response.setContentType("text/javascript");
                out = response.getWriter();
                out.println(gson.toJson(posts));
            }


        } catch (TwitterException ex) {
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
}