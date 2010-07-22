function enableTab( tabName ){
 
    var selectedBtnId  = 'btn-' + tabName;
    var selectedTabId  = 'tab-' + tabName;
	
    var allTabs    = $$('div.tab-body');
    var allButtons = $$('div.tab-button');
  
    for(i=0;i<allTabs.length;i++){

        if( allTabs[i].get('id') == selectedTabId ){
            allTabs[i].style.display = 'block';
        } else {
            allTabs[i].style.display = 'none';
        }
    }

    for(i=0;i<allButtons.length;i++){
        if( allButtons[i].id == selectedBtnId){
            allButtons[i].addClass('active-tab-button');
        } else {
            allButtons[i].removeClass('active-tab-button');
        }
    }
}

function toHours( months, days, hours){
    return (parseInt(months,10)*720) + (parseInt(days,10)*24) + parseInt(hours,10);
}
function fromHours(hours){

    var months = hours/720;
    if( months < 1 )months = 0;
    else hours = hours%720;
	
    var days = hours/24;
    if( days < 1 )days = 0;
    else hours = hours%24;
	
    return {
        'months':parseInt(months,10),
        'days'  :parseInt(days,10),
        'hours' :hours
    };
	

}

//Source
//http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256BF8004D72D6
function dp(obj, parent) {
    // Go through all the properties of the passed-in object
    for (var i in obj) {
        // if a parent (2nd parameter) was passed in, then use that to
        // build the message. Message includes i (the object's property name)
        // then the object's property value on a new line
        if (parent) {
            var msg = parent + "." + i + "\n" + obj[i];
        } else {
            var msg = i + "\n" + obj[i];
        }
        // Display the message. If the user clicks "OK", then continue. If they
        // click "CANCEL" then quit this level of recursion
        if (!confirm(msg)) {
            return;
        }
        // If this property (i) is an object, then recursively process the object
        if (typeof obj[i] == "object") {
            if (parent) {
                dp(obj[i], parent + "." + i);
            } else {
                dp(obj[i], i);
            }
        }
    }
}


/**
 *  Selects items in Array "value" in given list based
 * 
 * @param {Object} selectObject : <select> object whose selectedIndex is to be set
 * @param {Object} value :  The value to search for
*
 */
function selectByValue( selectObject , v ){
	

    //Error checking
    if(selectObject == null || v == null)return;
    if( typeof( v ) != 'object' ){
        value = new Array();
        value[0]=v;
    }
    else value = v;
	
    //Loop through all <option> elements
    for(i=0;i<selectObject.length;i++){
        selectObject.options[i].selected = false;
        for(j=0;j<value.length;j++){
            //Did we encounter the value?
            if( selectObject[i].value == value[j] ){
                selectObject.options[i].selected = true;
            }
        }
    }
}

function getSelectedElements( selectObject ) {
	
    if(!selectObject.multiple)return selectObject.options[selectObject.selectedIndex].value;
	
    var selectedElements = new Array();
    for(i=0;i<selectObject.length;i++){
        if(selectObject.options[i].selected)
            selectedElements.push(selectObject.options[i].value);
    }
    return selectedElements;
}

function setValue( element, v ){
	
    if(!element)return;
	
    var tagName = $(element).get('tag');
    switch( tagName ){
        case 'input':
            return null;
            break;
        case 'select':
            selectByValue( element, v);
        case 'textarea':
            element.value = v;
        default:
            return null;
    }
}

function getValue( element ){
	
    if(!element)return null;
	
    var tagName = $(element).get('tag');
    switch( tagName ){
        case 'input':
            return null;
            break;
        case 'select':
            return getSelectedElements( element );
        case 'textarea':
            return element.value;
        default:
            return null;
    }
}


String.implement({
 
    addSlashes: function(){
        str = this;
        str = str.replace(/\\/g,'\\\\');
        str = str.replace(/\'/g,'\\\'');
        str = str.replace(/\"/g,'\\"');
        str = str.replace(/\0/g,'\\0');
        return str;
    },

    stripSlashes: function(){
        str = this;
        str = str.replace(/\\0/g,'\0');
        str = str.replace(/\\"/g,'"');
        str = str.replace(/\\'/g,'\'');
        str = str.replace(/\\\\/g,'\\');
        return str;
    }
});

