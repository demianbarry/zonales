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
import org.zonales.entities.ActionType;
import org.zonales.entities.ActionsType;
import org.zonales.entities.LinkType;
import org.zonales.entities.LinksType;
import org.zonales.entities.Post;
import org.zonales.entities.PostType;
import org.zonales.entities.PostsType;
import org.zonales.entities.TagsType;
import org.zonales.entities.ToUsersType;
import org.zonales.entities.User;
import org.zonales.entities.Zone;
import org.zonales.tagsAndZones.daos.PlaceDao;
import org.zonales.tagsAndZones.daos.ZoneDao;
import org.zonales.tagsAndZones.objects.Place;

/**
 * Example servlet showing request headers
 *
 * @author James Duncan Davidson <duncan@eng.sun.com>
 */
public class TwitterRetrieval extends HttpServlet {

    class LatLon {

        String latitud, longitud;

        public LatLon(String lat, String lon) {
            latitud = lat;
            longitud = lon;
        }
    }
    String[] latitud, longitud, usuarios;
    String temp;
    public static final int MAX_TITLE_LENGTH = 30;

    @Override
    public void doGet(HttpServletRequest request,
            HttpServletResponse response)
            throws IOException, ServletException {

        request.setCharacterEncoding("UTF-8");
        response.setCharacterEncoding("UTF-8");
        PrintWriter out;
        try {


            // img stuff not req'd for source code html showing
            // all links relative
            // XXX
            // making these absolute till we work out the
            // addition of a PathInfo issue

            ConfigurationBuilder cb = new ConfigurationBuilder();
            System.setProperty("twitter4j.http.httpClient", "twitter4j.internal.http.HttpClientImpl");
            cb.setOAuthConsumerKey("56NAE9lQHSOZIGXRktd5Qw").setOAuthConsumerSecret("zJjJrUUs1ubwKjtPOyYzrwBJzpwq7ud8Aryq1VhYH2E").setOAuthAccessTokenURL("https://api.twitter.com/oauth/access_token").setOAuthRequestTokenURL("https://api.twitter.com/oauth/request_token").setOAuthAuthorizationURL("https://api.twitter.com/oauth/authorize").setOAuthAccessToken("234742739-I1l0VGTTjRUbZrfH1jvKnTVFU9ZEvkxxUDpvsAJ2").setOAuthAccessTokenSecret("jLe3imI3JiPgmHCatt6SqYgRAcX5q8s6z38oUrqMc");

            TwitterFactory tf = new TwitterFactory(cb.build());
            Twitter twitter = tf.getInstance();

            Query query = new Query(request.getParameter("q"));

            String tags = request.getParameter("tags");

            String zone = request.getParameter("zone");

            String users = request.getParameter("users");

            Map<String, LatLon> latslongs = new HashMap();


            String[] tagsArray = null;
            if (tags != null) {
                tagsArray = tags.split(",");
            }

            String[] userArray = null;

            if (users != null) {
                userArray = users.split(",");
                int count = userArray.length;
                usuarios = new String[count];
                latitud = new String[count];
                longitud = new String[count];
                for (int i = 0; i < userArray.length; i++) {
                    temp = userArray[i];
                    if (temp != null) {
                        int hit1 = temp.indexOf("[");
                        int hit2 = temp.indexOf(";");
                        int hit3 = temp.indexOf("]");
                        latslongs.put(temp.substring(0, hit1), new LatLon(temp.substring(hit1 + 1, hit2), temp.substring(hit2 + 1, hit3)));
                        /*usuarios[i] = temp.substring(0, hit1);
                        latitud[i] = temp.substring(hit1 + 1, hit2);
                        longitud[i] = temp.substring(hit2 + 1, hit3);*/
                    }
                }
            }

            QueryResult result;

            result = twitter.search(query);

            List<Post> postList = new ArrayList();
            List<PostType> postsList = new ArrayList();

            Post solrPost;
            PostType post;
            //List<LinkType> links = new ArrayList();
            List<ActionType> actions;
            ArrayList<User> toUsers = new ArrayList();

            ArrayList<LinkType> links;
            int d;

            InputStream stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
            Properties props = null;
            if (props == null) {
                props = new Properties();
                props.load(stream);
            }
            ZoneDao zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            PlaceDao placeDao = new PlaceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            Place place = null;
            org.zonales.tagsAndZones.objects.Zone zoneObj = zoneDao.retrieveByExtendedString(zone.replace(", ", ",+").replace(" ", "_").replace("+", " ").toLowerCase());

            for (Tweet tweet : (List<Tweet>) result.getTweets()) {
                d = MAX_TITLE_LENGTH;
                actions = new ArrayList();
                try {
                    actions.add(new ActionType("retweets", twitter.getRetweets(tweet.getId()).size()));
                    actions.add(new ActionType("replies", twitter.getRelatedResults(tweet.getId()).getTweetsWithReply().size()));
                } catch (TwitterException ex) {
                    Logger.getLogger(TwitterRetrieval.class.getName()).log(Level.SEVERE, "Error intentando obtener retweets o replies: {0}", new Object[]{ex});
                }

                solrPost = new Post();
                solrPost.setZone(new Zone(String.valueOf(zoneObj.getId()), zoneObj.getName(), zoneObj.getType().getName(), zoneObj.getExtendedString()));
                solrPost.setSource("Twitter");

                solrPost.setId(String.valueOf(tweet.getId()));

                if (request.getParameter(tweet.getFromUser() + "Place") != null) {
                    place = placeDao.retrieveByExtendedString(request.getParameter(tweet.getFromUser() + "Place"));
                } else {
                    place = null;
                }
                User usersolr = new User(String.valueOf(tweet.getFromUserId()),
                        tweet.getFromUser(),
                        "http://twitter.com/#!/" + tweet.getFromUser(),
                        tweet.getSource(), place != null ? new org.zonales.entities.Place(String.valueOf(place.getId()), place.getName(), place.getType().getName()) : null);

                if (users != null) {
                    /*for (int i = 0; i < usuarios.length; i++) {
                    if (tweet.getFromUser().equals(usuarios[i])) {
                    usersolr.setLatitude(Double.parseDouble(latitud[i]));
                    usersolr.setLongitude(Double.parseDouble(longitud[i]));
                    }
                    }*/
                    //usersolr.setLatitude(Double.parseDouble(latslongs.get(tweet.getFromUser()).latitud));
                    //usersolr.setLongitude(Double.parseDouble(latslongs.get(tweet.getFromUser()).longitud));
                }

                solrPost.setFromUser(usersolr);
                if (tweet.getToUser() != null) {
                    toUsers.add(new User(String.valueOf(tweet.getToUserId()),
                            tweet.getToUser(),
                            null,
                            tweet.getSource(), null));
                    solrPost.setToUsers(toUsers);
                }
                if (tweet.getText().length() > d) {
                    while (d > 0 && tweet.getText().charAt(d - 1) != ' ') {
                        d--;
                    }
                } else {
                    d = tweet.getText().length() - 1;
                }
                solrPost.setTitle(tweet.getText().substring(0, d) + (tweet.getText().length() > MAX_TITLE_LENGTH ? "..." : ""));
                solrPost.setText(tweet.getText());
                //post.setLinks(new LinksType(links));
                solrPost.setActions((ArrayList<ActionType>) actions);
                solrPost.setCreated(tweet.getCreatedAt().getTime());
                solrPost.setModified(tweet.getCreatedAt().getTime());
                solrPost.setRelevance(actions.size() == 2 ? actions.get(0).getCant() * 3 + actions.get(1).getCant() : 0);
                solrPost.setPostLatitude(tweet.getGeoLocation() != null ? tweet.getGeoLocation().getLatitude() : null);
                solrPost.setPostLongitude(tweet.getGeoLocation() != null ? tweet.getGeoLocation().getLongitude() : null);

                links = new ArrayList<LinkType>();
                links.add(new LinkType("avatar", tweet.getProfileImageUrl()));
                if (tweet.getText() != null && getLinks(tweet.getText()) != null) {
                    links.addAll(getLinks(tweet.getText()));
                }

                if (solrPost.getLinks() == null) {
                    solrPost.setLinks(new ArrayList<LinkType>());
                }
                solrPost.setLinks(links);

                if (tagsArray != null && tagsArray.length > 0) {
                    solrPost.setTags(new ArrayList<String>(Arrays.asList(tagsArray)));
                }
                
                solrPost.setExtendedString((solrPost.getFromUser().getPlace() != null ? solrPost.getFromUser().getPlace().getName() + ", " : "") + solrPost.getZone().getExtendedString());
                postList.add(solrPost);

                post = new PostType();
                post.setZone(new Zone(String.valueOf(zoneObj.getId()), zoneObj.getName(), zoneObj.getType().getName(), zoneObj.getExtendedString()));
                post.setSource("Twitter");

                post.setId(String.valueOf(tweet.getId()));
                User user = new User(String.valueOf(tweet.getFromUserId()),
                        tweet.getFromUser(),
                        "http://twitter.com/#!/" + tweet.getFromUser(),
                        tweet.getSource(), place != null ? new org.zonales.entities.Place(String.valueOf(place.getId()), place.getName(), place.getType().getName()) : null);

                if (users != null) {
                    /*for (int i = 0; i < usuarios.length; i++) {
                    if (tweet.getFromUser().equals(usuarios[i])) {
                    user.setLatitude(Double.parseDouble(latitud[i]));
                    user.setLongitude(Double.parseDouble(longitud[i]));
                    }
                    }*/
                    //user.setLatitude(Double.parseDouble(latslongs.get(tweet.getFromUser()).latitud));
                    //user.setLongitude(Double.parseDouble(latslongs.get(tweet.getFromUser()).longitud));
                }

                post.setFromUser(user);

                if (tweet.getToUser() != null) {
                    toUsers.add(new User(String.valueOf(tweet.getToUserId()),
                            tweet.getToUser(),
                            null,
                            tweet.getSource(), null));
                    post.setToUsers(new ToUsersType(toUsers));
                }

                post.setTitle(tweet.getText().substring(0, d) + (tweet.getText().length() > MAX_TITLE_LENGTH ? "..." : ""));
                post.setText(tweet.getText());
                //post.setLinks(new LinksType(links));
                post.setActions(new ActionsType(actions));
                post.setCreated(String.valueOf(tweet.getCreatedAt().getTime()));
                post.setModified(String.valueOf(tweet.getCreatedAt().getTime()));
                post.setRelevance(actions.size() == 2 ? actions.get(0).getCant() * 3 + actions.get(1).getCant() : 0);
                post.setPostLatitude(tweet.getGeoLocation() != null ? tweet.getGeoLocation().getLatitude() : null);
                post.setPostLongitude(tweet.getGeoLocation() != null ? tweet.getGeoLocation().getLongitude() : null);                

                links = new ArrayList<LinkType>();
                links.add(new LinkType("avatar", tweet.getProfileImageUrl()));

                post.setLinks(new LinksType(getLinks(tweet.getText())));


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
                out.println("{post: " + gson.toJson(postList) + "}");
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

    private List<LinkType> getLinks(String text) {
        String pattern = "^http://[a-zA-Z0-9]+(\\.([a-zA-Z0-9])+)+(\\/([a-zA-Z0-9])*)*$";
        StringTokenizer st = new StringTokenizer(text);
        String token;
        List<LinkType> result = new ArrayList<LinkType>();
        while (st.hasMoreTokens()) {
            token = st.nextToken();
            if (token.matches(pattern)) {
                result.add(new LinkType("link", token));
            }
        }
        if (result.size() > 0) {
            return result;
        }
        return null;
    }
}
