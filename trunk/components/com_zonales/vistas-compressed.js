Element.Properties["data-title"]={get:function(){return this["data-title"]},set:function(a){this["data-title"]=a;this.setAttribute("data-title",a)}};function ZTabs(){this.nodeURL="http://192.168.0.2:4000";this.detalleURL="http://192.168.0.2:82";this.sources=new Array();this.zones=new Array();this.allZones=new Array();this.tab="";var O=new Array();this.zUserGroups=O;this.postInterval=null;this.host="localhost";this.port="38080";this.sessionId=Cookie.read("cfaf9bd7c00084b9c67166a357300ba3");var b=io.connect(this.nodeURL);this.socket=b;var l=new ZContext(this.socket,this.sessionId,this.nodeURL);this.zCtx=l;var L=new ZIRClient(this.socket,this.sessionId);this.zirClient=L;var H;var z=new Array();this.newPosts=z;var s="";var x=5000;var a=null;var t=null;var U=null;this.setComponents=function(Z,Y,X){a=Z;t=Y;U=X;H=Tempo.prepare(a).notify(function(aa){if(aa.type==TempoEvent.Types.RENDER_COMPLETE){if($("loadingDiv")){$("loadingDiv").setStyle("display","none")}}})};b.on("solrPosts",function(X){console.log("SOLR POSTS: ");console.log(X);z.empty();U.setStyle("display","none");m(X.response.docs)});b.on("solrMorePosts",function(X){m(X.response.docs,true)});b.on("solrNewPosts",function(X){console.log("NEW SOLR POSTS: ");console.log(X);z.combine(X.response.docs);if(z.length>0){U.innerHTML=z.length+" nuevo"+(z.length>1?"s":"")+"...";U.setStyle("display","block")}else{U.setStyle("display","none")}});var W=function W(){l.initZCtx(function(){tab=l.zcGetZTab();N(l.zcGetZone());S(l);if(typeof initMapTab=="function"){initMapTab()}if(typeof setActiveTab=="function"){setActiveTab(zTab.zCtx.zcGetZTab())}});O=loguedUser};this.initAll=W;var N=function N(X){if(X&&X!=""){$("zoneExtended").value=X}};this.initZonas=N;var S=function S(X){if(z){z.empty()}U.setStyle("display","none");L.setFirstIndexTime(null);L.setLastIndexTime(null);L.setMinRelevance(null);j();X.setSearchKeyword("")};this.initVista=S;setInterval(function(){h()},10000);var j=function j(){if(this.tab!="geoActivos"&&a){if(this.postInterval){clearInterval(this.postInterval);this.postInterval=null}this.postInterval=setInterval(function(){L.loadNewSolrPost()},60000)}else{if(this.postInterval){clearInterval(this.postInterval);this.postInterval=null}}};this.initPost=j;this.initFilters=function R(X){this.initSourceFilters(X);this.initTagFilters(X);this.initTempFilters(X)};this.initSourceFilters=function u(X){X.zcGetFilters().sources.each(function(ab){var Y=$("chk"+ab.name);if(!Y){var aa=new Element("tr");var Z=new Element("td").inject(aa);new Element("input",{id:"chk"+ab.name,type:"checkbox",checked:(ab.checked?"checked":""),name:ab.name,value:ab.name,onclick:"setSourceVisible(this.value,this.checked);"}).inject(Z);new Element("td",{html:ab.name}).inject(aa);aa.inject($("filtroSources"))}})};this.initTagFilters=function f(X){X.zcGetFilters().tags.each(function(Z){var ab=$("chkt"+Z.name);if(!ab){var aa=new Element("tr");var Y=new Element("td").inject(aa);ab=new Element("input",{id:"chkt"+Z.name,type:"checkbox",checked:(Z.checked?"checked":""),name:Z.name,value:Z.name,onclick:"setTagVisible(this.value,this.checked);"}).inject(Y).addClass(Z.checked?"checked":"");new Element("td",{html:Z.name}).inject(aa);aa.inject($("tagsFilterTable"))}if(Z.checked){ab.addClass("checked")}})};this.initTempFilters=function K(X){$("tempoSelect").value=X.zcGetTemp();$("tempoSelect").addEventListener("change",I,false)};var M=function M(X,Y,aa,Z){if(X==null||typeof(X)=="undefined"){X=""}if(Y==null||typeof(Y)=="undefined"){Y=""}if(aa==null||typeof(aa)=="undefined"){aa=""}if(Z==null||typeof(Z)=="undefined"){Z=""}L.setFirstIndexTime(null);L.setLastIndexTime(null);L.setMinRelevance(null);$("zoneExtended").value=X;if(this.tab!="geoActivos"&&this.tab!="editor"&&this.tab!="list"&&a&&z){z.empty()}l.setSelectedZone(X,Y,aa,Z,function(){})};this.setZone=M;this.onTempoChange=function I(){if(this.tab!="geoActivos"&&a){z.empty()}L.setLastIndexTime(null);l.zcSetTemp($("tempoSelect").value)};this.complete=function p(X){return(X>9?""+X:"0"+X)};this.loadPost=function F(X){};this.loadMorePost=function i(){console.log("loadMorePost");this.zirClient.loadMoreSolrPost()};this.searchPost=function E(Y,X){if(Y!="buscar..."&&Y!=""){L.setFirstIndexTime(null);L.setLastIndexTime(null);L.setMinRelevance(null);l.setSearchKeyword(Y)}};var A=function A(Y,Z){var X="";if((Y.source).toLowerCase()=="twitter"){X="http://twitter.com/#!/"+Y.fromUser.name}else{if((Y.source).toLowerCase()=="facebook"){X=Y.fromUser.url}else{if((Y.source).toLowerCase()=="zonales"){X=this.detalleURL+"/detalle.html?id="+Z}else{if(typeOf(Y.links)=="array"){Y.links.each(function(aa){if(aa.type=="source"){X=aa.url}})}}}}return X};var Q=function Q(aa){if(aa.length>255){var X=255;for(;aa.charAt(X)!=" ";X--){}var Y=aa.substring(0,X);var Z=aa.substring(X);return Y+'<span id="verMasPost" onclick="if (this.getNext()) {this.getNext().innerHTML = unescape(this.getNext().innerHTML); this.getNext().setStyle(\'display\',\'inline\'); this.style.display = \'none\';}" style="display: inline;">... [+]</span><span style="display: none;" id="resto">'+escape(Z)+"</span>"}return aa};var c=function c(){m(z,false,true);U.setStyle("display","none");z.empty()};this.verNuevos=c;this.incRelevance=function P(aa,Z){var X='/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(aa)+'"}&rel='+Z;var Y="/curl_proxy.php";new Request({url:Y,method:"post",data:{host:this.host?this.host:"localhost",port:this.port?this.port:"38080",ws_path:X},onRequest:function(){},onSuccess:function(ab){if(ab&&ab.length!=0){ab=JSON.decode(ab);if(ab.id&&ab.id.length>0&&$("relevance_"+ab.id)){$("relevance_"+ab.id).innerHTML=parseInt($("relevance_"+ab.id).innerHTML)+parseInt(Z)}}},onFailure:function(){}}).send()};var V=function V(Z,Y){var X='<a target="_blank" href="'+Y+'">'+Z+"</a>";return X};var d=function d(X){var Y='<a id="zonePost" onclick="zTab.setZone(\''+X.replace(/_/g," ").capitalize()+"','','','');drawMap('"+X+"');ajustMapToExtendedString('"+X+"');\">"+X.replace(/_/g," ").capitalize()+"</a>";return Y};var C=function(Z,Y){var X="<spam id='relevance_"+Y+"'>"+Z+"</spam>";return X};var G=function(X,Z){var Y;if(X){X.each(function(aa){if(aa.type=="avatar"){Y=aa.url}})}if(!Y){if(Z=="Facebook"){Y="/images/facebook.png"}else{if(Z=="Twitter"){Y="/images/twitter.png"}else{Y="/images/rss.png"}}}return'<img class="avatar" src="'+Y+'"/>'};var r=function(Y){var X;var Z="none";if(Y){Y.each(function(aa){if(aa.type=="picture"){X=aa.url;Z="inline";return}})}if(X){return'<img class="img" style="display:'+Z+'" src="'+X+'"/>'}else{return""}};this.removeNewClass=function(){$$("li.newPost").removeClass("newPost")};var m=function m(ab,X,Z){var Y=[];var aa=true;if(ab&&typeOf(ab)=="array"){ab.each(function(af){var ad=JSON.parse(af.verbatim);if($(ad.id)){$(ad.id).dispose()}ad.modifiedPretty=y(af.modified);ad.modified=af.modified;var ae=A(ad,af.id);ad.title=V(ad.title,ae);ad.text=Q(ad.text?ad.text:"");var ac=d(ad.zone.extendedString);if(aa){s=ad.zone.extendedString;aa=false}ad.zone=ac;ad.actions.each(function(ag){if(ag.type=="comment"){ag.type="comentarios"}if(ag.type=="comments"){ag.type="comentarios"}if(ag.type=="like"){ag.type="me gusta"}if(ag.type=="replies"){ag.type="respuestas"}});ad.relevance=C(ad.relevance,ad.id);ad.img=r(ad.links);ad.avatar=G(ad.links,ad.source);Y.push(ad);if(Z){ad.clase="newPost"}else{ad.clase=""}})}if(X){H.append(Y).notify(function(ac){if(ac.type==TempoEvent.Types.RENDER_STARTING){a.addClass("loading")}if(ac.type==TempoEvent.Types.RENDER_COMPLETE){a.removeClass("loading")}})}else{if(Z){H.prepend(Y).notify(function(ac){if(ac.type==TempoEvent.Types.RENDER_STARTING){a.addClass("loading")}if(ac.type==TempoEvent.Types.RENDER_COMPLETE){a.removeClass("loading")}});setTimeout("zTab.removeNewClass();",x)}else{H.render(Y)}}D(s)};this.updatePosts=m;this.checkTag=function v(Y,X){if(l.zTags.indexOf(Y)!=-1){$(X).setStyle("display","inline")}else{$(X).setStyle("display","none")}};this.show_confirm=function q(X,aa,Y){var Z=confirm("Esta seguro de Agregar el Tag: "+aa);if(Z==true){w(X,Y,aa)}else{alert("Cancelado")}};this.addTagToPost=function w(Z,Y,ab){var X='/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(Z)+'"}&aTags='+Y+","+ab;var aa="/curl_proxy.php";new Request({url:aa,method:"post",data:{host:this.host?this.host:"localhost",port:this.port?this.port:"38080",ws_path:X},onRequest:function(){},onSuccess:function(ad){if(ad.length>0){ad=JSON.decode(ad);if(ad.id){var ac=new Element("span").addClass("cp_tags").inject($("tagsDiv_"+ad.id).getLast().getPrevious(),"before");new Element("a",{html:ab,onclick:'ckeckOnlyTag("'+ab+'");'}).inject(ac);$("tagsDiv_"+ad.id).addClass(ab)}}},onFailure:function(){}}).send()};this.delTagFromPost=function e(Y,aa){var X='/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(Y)+'"}&rTag='+aa;var Z="/curl_proxy.php";new Request({url:Z,method:"post",data:{host:this.host?this.host:"localhost",port:this.port?this.port:"38080",ws_path:X},onRequest:function(){},onSuccess:function(ab){if(ab.length>0){ab=JSON.decode(ab);if(ab.id){$("tagsDiv_"+ab.id).getElements("span.cp_tags a").each(function(ac){if(ac.innerHTML==aa){ac.getParent().dispose()}});$("tagsDiv_"+ab.id).removeClass(aa)}}},onFailure:function(){}}).send()};this.spanishDate=function o(Z){var Y=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];var X=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];return n(Z.getHours())+":"+n(Z.getMinutes())+":"+n(Z.getSeconds())+", "+Y[Z.getDay()]+" "+Z.getDate()+" de "+X[Z.getMonth()]+" de "+Z.getFullYear()};this.fixTime=function n(X){return(X<10?"0"+X:X)};this.setSourceVisible=function g(X,Y){if(Y){zcAddSource(X)}else{zcUncheckSource(X)}};this.ckeckOnlyTag=function k(X){var Z=this.zcGetCheckedTags();Z.each(function(aa){if(aa!=X){zcUncheckTag(aa)}});var Y=$$("table#tagsFilterTable input");Y.each(function(aa){if(aa.id=="chkt"+X){aa.checked=true}else{aa.checked=false}});T(X,true)};this.setTagVisible=function T(X,Y){if(Y){if($("chkt"+X)){$("chkt"+X).addClass("checked")}zcAddTag(X)}else{if($("chkt"+X)){$("chkt"+X).removeClass("checked")}zcUncheckTag(X)}};this.refreshFiltro=function J(){var X=$$("div#postsContainer div.story-item");if(typeOf(X)=="elements"){X.each(function(Y){var Z=false;zCtxChkSource.each(function(aa){if(Y.hasClass(aa)){zCtxChkTags.each(function(ab){if(Y.hasClass(ab)){Z=true}})}});Y.setStyle("display",Z?"block":"none");Y.removeClass(!Z?"visible":"hidden");Y.addClass(Z?"visible":"hidden")})}D(this.tab)};var y=function y(ab){var aa=[[60,"segundos",1],[120," hace 1 minuto","hace 1 minuto"],[3600,"minutos",60],[7200," hace 1 hora","hace 1 hora"],[86400,"horas",3600],[172800,"1 dia","mañana"],[604800,"días",86400],[1209600," en la ultima semana","próxima semana"],[2419200,"semanas",604800],[4838400," ultimo mes","próximo mes"],[29030400,"meses",2419200],[58060800," en el ultimo año","proximo año"],[2903040000,"años",29030400],[5806080000,"ultimo siglo","proximo siglo"],[58060800000,"siglos",2903040000]];var ad=(new Date-new Date(ab))/1000;var Y=" hace",ac=1;if(ad<0){ad=Math.abs(ad);ac=2}var X=0,Z;while(Z=aa[X++]){if(ad<Z[0]){if(typeof Z[2]=="string"){return Z[ac]}else{return(Y+" "+Math.floor(ad/Z[2])+" "+Z[1])}}}return ab};var h=function(){$$("p.fecha").each(function(X){X.innerHTML=y(X.title)})};var D=function D(){var Z=0;var Y=l.zcGetZone();var X=s;$("tituloSup").innerHTML="";if(X!=Y&&Y!=""&&typeof(Y)!="undefined"){$("tituloSup").innerHTML="No se encontraron noticias para la zona seleccionada"}};this.armarTitulo=D;this.popup=function B(X){this.zirClient.loadSolrPost(X,function(Y){updatePost(Y)})}}var zTab=new ZTabs();window.addEvent("domready",function(){console.log("domready antes de initAll");zTab.setComponents($("postTemplate"),$("filtersContainer"),$("verNuevos"));zTab.initAll()});