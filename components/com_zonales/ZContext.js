
function Filters(){
    this.source = new Array();
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

if(!zctx) {
    var zctx = new ZContext();

}


