<!--<script language="javascript" type="text/javascript" src="media/system/js/simple-modal.js"></script>-->

<div id="verNuevos" onclick="zTab.verNuevos();">
    <p>
        <a href="#">
            <img src="templates/<?php echo $template; ?>/img/arrow_down.gif" />
        </a>
    </p>
</div>
<div id="tituloZone">
   <label id="tituloSup"></label> 
</div>
<div id="newPostsContainer" style="display:none"></div>
<ol id="postTemplate">
    <li data-template data-if-source="Facebook" style="display: none;" class="{{clase}}" id="{{id}}">
        <div class="post post_fcbk">
            <div class="col1">
                <p>
                    <img src="templates/<?php echo $template; ?>/img/post_fcbk.gif" />
                </p>
                <p>{{avatar}}</p>
                <p data-template="actions">{{type}}<br />{{cant}}</p>
            </div><!-- /#col1 -->
            <div class="col2">
                <div class="padding10">
                    <h2>{{title}}</h2>
                     <p class="images">{{img}}</p>
                    <p class="cuerpo">{{text}}</p>
                    <p class="autor">
                        <span>Publicado por {{fromUser.name}}</span>
                        <span data-template="toUsers"> en {{name}}</span>
                        en {{zone}}
                    </p>
                    <!--<p class="tag">Tags {{tag}}</p>-->
                    <p class="fecha" title="{{modified}}">{{modifiedPretty}}</p>
                    
<!--                    <p class="buttons"><a href="#">Compartir</a> | <a href="#">Comentar</a></p>-->
            
                    <div id="commentsDiv_{{id}}" class="comentarios" style="display:none">
                        <div id="commentsMore_{{id}}" class="verMasComentarios" style="display:none">
                            <p class="comentario"><a id="commentsMoreText_{{id}}" onclick="zTab.verMasComentarios('{{id}}');"></a></p>
                            <hr class="splitter" />
                        </div>
                        <ol id="commentsOl_{{id}}">
                        </ol>
                        <p><input name="" type="text" value="Escribí un comentario" class="input_comment" /></p>
                    </div><!-- /.comentarios -->
                </div><!-- /.padding10 -->
            </div><!-- /#col2 -->
            <div class="col3">
                <div class="contador">
                    <p>{{relevance}}</p>
<!--                    <p><img onclick="zTab.incRelevance('{{id}}', -1)" src="templates/<?php echo $template; ?>/img/contador_down.gif" /><img onclick="zTab.incRelevance('{{id}}', 1)" src="templates/<?php echo $template; ?>/img/contador_up.gif" /></p>-->
                    <p><img src="templates/<?php echo $template; ?>/img/contador_down.gif" /><img src="templates/<?php echo $template; ?>/img/contador_up.gif" /></p>
                </div><!-- /.contador -->
            </div><!-- /#col3 -->
            <div class="clear"></div>
            <hr class="splitter" />
        </div><!-- /.post post_fcbk -->
    </li>
    <li data-template data-if-source="Twitter" style="display: none;" class="{{clase}}" id="{{id}}">
        <div class="post post_twttr">
            <div class="col1">
                <p>
                    <img src="templates/<?php echo $template; ?>/img/post_twttr.gif" />
                </p>
                <p>{{avatar}}</p>
                <p data-template="actions">{{type}}<br />{{cant}}</p>
            </div><!-- /#col1 -->
            <div class="col2">
                <div class="padding10">
                    <h2>{{title}}</h2>
                        <p class="images">{{img}}</p>
                    <p class="cuerpo">{{text}}</p>
                    <p class="autor">Publicado por {{fromUser.name}} en {{zone}}</p>
                    <!--<p class="tag">Tags {{tag}}</p>-->
                    <p class="fecha" title="{{modified}}">{{modifiedPretty}}</p>
<!--                    <p class="buttons"><a href="#">Compartir</a> | <a href="#">Comentar</a></p>-->
            
                    <div id="commentsDiv_{{id}}" class="comentarios" style="display:none">
                        <div id="commentsMore_{{id}}" class="verMasComentarios" style="display:none">
                            <p class="comentario"><a id="commentsMoreText_{{id}}" onclick="zTab.verMasComentarios('{{id}}');"></a></p>
                            <hr class="splitter" />
                        </div>
                        <ol id="commentsOl_{{id}}">
                        </ol>
                        <p><input name="" type="text" value="Escribí un comentario" class="input_comment" /></p>
                    </div><!-- /.comentarios -->
                </div><!-- /.padding10 -->
            </div><!-- /#col2 -->
            <div class="col3">
                <div class="contador">
                    <p>{{relevance}}</p>
                    <p><img src="templates/<?php echo $template; ?>/img/contador_down.gif" /><img src="templates/<?php echo $template; ?>/img/contador_up.gif" /></p>
                </div><!-- /.contador -->
            </div><!-- /#col3 -->
            <div class="clear"></div>
            <hr class="splitter" />
        </div><!-- /.post post_fcbk -->
    </li>
    <li data-template style="display: none;" class="{{clase}}" id="{{id}}">
        <div class="post post_medio">
            <div class="col1">
                <p>
                    <img src="templates/<?php echo $template; ?>/img/post_medio.gif" />
                </p>
                <p>{{avatar}}</p>
                <p data-template="actions">{{type}}<br />{{cant}}</p>
            </div><!-- /#col1 -->
            <div class="col2">
                <div class="padding10">

                    <h2>{{title}}</h2>
                        <p class="images">{{img}}</p>
                    <p class="cuerpo">{{text}}</p>
                    <p class="autor">Publicado por {{fromUser.name}} en {{zone}}</p>
                    <!--<p class="tag">Tags {{tag}}</p>-->
                    <p class="fecha" title="{{modified}}">{{modifiedPretty}}</p>
<!--                    <p class="buttons"><a href="#">Compartir</a> | <a href="#">Comentar</a></p>-->
            
                    <div id="commentsDiv_{{id}}" class="comentarios" style="display:none">
                        <div id="commentsMore_{{id}}" class="verMasComentarios" style="display:none">
                            <p class="comentario"><a id="commentsMoreText_{{id}}" onclick="zTab.verMasComentarios('{{id}}');"></a></p>
                            <hr class="splitter" />
                        </div>
                        <ol id="commentsOl_{{id}}">
                        </ol>
                        <p><input name="" type="text" value="Escribí un comentario" class="input_comment" /></p>
                    </div><!-- /.comentarios -->
                </div><!-- /.padding10 -->
            </div><!-- /#col2 -->
            <div class="col3">
                <div class="contador">
                    <p>{{relevance}}</p>
                    <p><img src="templates/<?php echo $template; ?>/img/contador_down.gif" /><img src="templates/<?php echo $template; ?>/img/contador_up.gif" /></p>
                </div><!-- /.contador -->
            </div><!-- /#col3 -->
            <div class="clear"></div>
            <hr class="splitter" />
        </div><!-- /.post post_fcbk -->
    </li>
</ol>
<div>
    <div class="hidden" style="background-image: url('/templates/z30/img/loading.gif'); position: absolute; background-repeat: no-repeat; background-position: center center; background-color: white; background-size: auto auto; width: 512px; height: 50px;" id="verMasLoading"></div>
    <div id="verMas"><p><a id="verMasAnchor" onclick="$('verMas').addClass('hidden');$('verMasLoading').removeClass('hidden');zTab.loadMorePost();">Ver más <img src="templates/<?php echo $template; ?>/img/arrow_down.gif" /></a></p></div>
</div>
<script language="javascript" type="text/javascript" src="components/com_zonales/utils.js"></script>
<script language="javascript" type="text/javascript" src="components/com_zonales/solr.js"></script>
<script language="javascript" type="text/javascript" src="components/com_zonales/ZContext.js"></script>
<script language="javascript" type="text/javascript" src="components/com_zonales/tempo.js"></script>
<script language="javascript" type="text/javascript" src="components/com_zonales/vistas.js"></script>