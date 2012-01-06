//var zctx = new ZContext();

function Filters(){
    this.sources = new Array();
    this.temp = "";
    this.tags = new Array();
}

function ZContext(){
    this.filters = new Filters();
    this.zTabs = new Array();
    this.zTab = "";
    this.selZone = "";
    this.efZone = "";
}

function zcAddSource(source){
    if(zctx.filters.sources.contains(source)){
        zctx.filters.sources.push(source); 
    }
}

function zcAddTag(tag){
    if(zctx.filters.tags.contains(tag)){
        zctx.filters.tags.push(tag); 
    }
}

function zcRemoveSource(source){
    zctx.filters.sources.erase(source); 
}

function zcRemoveTag(tag){
    zctx.filters.tags.erase(tag); 
}

function zcAddTab(tab){
    if(zctx.zTabs.contains(tab)){
        zctx.zTabs.push(tab);
    }
}

function zcRemoveTab(tab){
    zctx.zTabs.erase(tab); 
}