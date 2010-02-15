var maximumStations   = 15;
var stationsArray     = new Array();
var workspaceSortable = null;
var selectedElement   = null;
var stationCount      = 0;

var selectedACLSystemId = null;

var defaultWorkflow   = new Object();
var adminGroupId      = null;
var hookParameters    = new Array();

function $SI(list){return list.options[list.selectedIndex];}

function selectACLSystemFromList(selectList){


	//Ignore the "Please select" element
	if( selectList.selectedIndex == 0 ) return;
	
		
	//Make selection permenant
	//TODO: Replace this behaviour with a more user friendly one
	selectList.disabled = true;
	
	selectACLSystem( $SI(selectList).value );

}
function selectACLSystem( id ){

	selectedACLSystemId = id;
	var selectedACLSystem = aclLists[selectedACLSystemId];
	
	if(selectedACLSystem == null){
		selectedACLSystemId = null;
		return;
	}
	
	//Show group selection box
	$('groups-container').style.display = 'block';
	
	//Fieldsets in "Groups tab"
	var addStationSelectList  = $('groups_input_acl');
	var propertiesSelectList  = $('properties_input_acl');
	var adminGroupSelectList  = $('paramsadmin_gid');
	var i=0;
	for( var id in selectedACLSystem ){
			addStationSelectList.options[i] = new Option( selectedACLSystem[id], id);
			propertiesSelectList.options[i] = new Option( selectedACLSystem[id], id);
			adminGroupSelectList.options[i] = new Option( selectedACLSystem[id], id);
			i++;
	}
	if(adminGroupId){
		selectByValue(adminGroupSelectList, adminGroupId );
	}
	
	
}

/**
 *  Retrieves the index of an element in  "stationsArray" when given the "li" container element
 * 
 * @param {Object} li : li Container for the element
 * @return{int} the index within the stationsArray list or -1 if the element is not found "deleted"
 */
function getIndexFromContainerElement(e){
	for(i=0;i<stationsArray.length;i++){
		if( stationsArray[i] == null )continue;
		if( stationsArray[i].htmlContainer == e )return i;
	}
	return -1;
}


function displayProperties(){


	if(selectedElement == null)return;

	$('properties-container').style.display = 'block';
	
	$('properties_input_title').value  = selectedElement.title;
	$('properties_input_task').value   = selectedElement.task;

	var allocatedTime = fromHours(selectedElement.allocatedTime)
	selectByValue( $('properties_input_hours')   , allocatedTime.hours  );
	selectByValue( $('properties_input_days')    , allocatedTime.days   );
	selectByValue( $('properties_input_months')  , allocatedTime.months );

	selectByValue( $('properties_input_acl')       , selectedElement.acl.id );
	selectByValue( $('properties_input_fields'),selectedElement.fields.split(','));
        selectByValue( $('properties_input_validations'),selectedElement.activeValidations.split(','));

	
	var hookSettingsForm = $('hook-settings-form');

	var activeHooks = $$('div.search-pane-list-check input.active-hooks');
	for(i=0;i<activeHooks.length;i++)activeHooks[i].checked = false;
	
	
	for(var key in selectedElement.activeHooks ){
		if(typeof(selectedElement.activeHooks[key]) == 'object' && key != '$family'){
			var paramData = selectedElement.activeHooks[key];
			var hookId    = key;
			hookSettingsForm.elements['properties_input_hooks['+hookId+']'].checked = true;
			for(var paramName in paramData){
				setValue( hookSettingsForm.elements['hooks-'+hookId+'['+paramName+']'], paramData[paramName]);
			}
		}
		
	}
}

function saveElement(){

	if(selectedElement == null)return;
	selectedElement.title = $('properties_input_title').value;
	selectedElement.task  = $('properties_input_task').value;

	selectedElement.allocatedTime = toHours( $('properties_input_months').value,
											 $('properties_input_days').value,
											$('properties_input_hours').value);


	selectedElement.acl.id   = $SI($('properties_input_acl')).value;
	selectedElement.acl.name = aclLists[selectedACLSystemId][selectedElement.acl.id];
	selectedElement.fields   = getSelectedElements($('properties_input_fields')).join(',');
        selectedElement.activeValidations   = getSelectedElements($('properties_input_validations')).join(',');

	
	selectedElement.activeHooks = new Array();
	var hookSettingsForm = $('hook-settings-form');
	
	var activeHooks = $$('div.search-pane-list-check input.active-hooks');

	for(i=0;i<activeHooks.length;i++){
		if(activeHooks[i].checked){
			var hookId     = activeHooks[i].value;
			var hookParams = hookParameters[hookId];
			selectedElement.activeHooks[hookId] = new Object();
			for(j=0;j<hookParams.length;j++){
				var paramName = hookParams[j];
				selectedElement.activeHooks[hookId][paramName] = getValue(hookSettingsForm.elements['hooks-'+hookId+'['+paramName+']']);
			}
		}
	}

	selectedElement.onUpdate();
}


function addElement( data ){
		
	if( stationCount  > maximumStations )return;
	if( data == null )return;
	for(i=0;i<stationsArray.length;i++)if(stationsArray[i]==null)break;
	
	stationsArray[i] = new CStation( $('stations'), i, data);
	stationCount++;
	updateOrdering();
}	
function submitbutton(task){
	
	var form = $('adminForm');
	
	if( task == 'save' ){
		
		saveElement();
				
		if( form.elements['params[title]'].value.length == 0 ){
			alert('The form must have a title');
			enableTab('general');
			return false;
		}
		
		if( form.elements['params[category][]'].selectedIndex == -1 ){
			alert('Please select categories');
			enableTab('general');
			return false;
		}
		
		if( form.elements['params[acl]'].selectedIndex == -1 || 
		    form.elements['params[acl]'].selectedIndex == 0 ){
			alert('Please select acl handler');
			enableTab('general');
			return false;
		}

		if(stationCount < 2){
			alert('The workflow MUST contain atleast 2 stations');
			enableTab('acl');
			return false;
		}
		
		var workflowData = new Array();
		for(i=0;i<stationsArray.length;i++){
			if(stationsArray[i] == null)continue;
			workflowData.push(stationsArray[i].serialize());
		}

		
		
		$('workflowData').value = JSON.encode(workflowData);
		$('general_input_acl').disabled = false;
	}

	submitform( task );	
}
	
	
function addEmptyElement(){

	if($('groups_input_acl').selectedIndex == -1)return;
	
	id = stationsArray.length;
	
	var data = {
		acl:{
			name:$('groups_input_acl').options[$('groups_input_acl').selectedIndex].innerHTML,
			id:$('groups_input_acl').options[$('groups_input_acl').selectedIndex].value
		},
		title:'Station_' + id,
		task:'Task for station' + id,
		allocatedTime : 0,
		fields:'comments',
                activeValidations:'test',
		hookHandlers:''
	}
	addElement( data );
}

/**
 *  Retrieves the order of any given element within the WYSIWYG list,
 *
 * @param {Object} li : List item whose order is to be retrieved
 * @return{int} the 0-based order of the element within the list or -1 if the element couldn't be found
 */
function getOrder( li ) {
	
	var a = workspaceSortable.serialize();

	for(i=0;i<a.length;i++){
		if(a[i] == $(li).get('id')){
			return i;
		}
	}
	return -1;
}

function deleteSelectedElement(){
	
	//Show acl tab
	enableTab('acl');
	
	//Hide property pages
	$('properties-container').style.display = 'none';

	var element = selectedElement.htmlContainer;
	var index   = getIndexFromContainerElement( element );
	
	//Some Eye Candy
	var fx = new Fx.Morph(element);
	fx.onComplete = 
	function(e) {	
		
			//Call onDelete event handler on object
			stationsArray[index].onDelete();
			
			//Delete object
			stationsArray[index] = null;
			
			updateOrdering();
			
	}
	
	fx.start({'opacity': '0'});
	
	stationCount--;


}

function updateOrdering(){
	//if the counter variable is renamed to "i" , something wrong happens
	//FF bug maybe?
	for(j=0;j<stationsArray.length;j++){
		if(stationsArray[j] != null ){
			var order = getOrder(stationsArray[j].htmlContainer);
			stationsArray[j].htmlOrder.set({'html': ++order});
		}
	}
}

function resizeWorkarea(){
	var safetyMargin = 50;
	var clientArea  = $('element-box').getSize();
	var sidebarArea = $('side-bar').getSize();
	var workareaWidth = clientArea.x - sidebarArea.x - safetyMargin;
	$('workarea').setStyle('width', workareaWidth+'px');
}

function updateCategoryList(){

	var component = $('general_input_component').options[$('general_input_component').selectedIndex].value;
	$('general_input_category').options.length = 0;

	var i=0;
	for(var id in categoriesData[component]){
		var o = new Option( categoriesData[component][id], id);
		$('general_input_category').options[i++] = o;
	}
}

function selectCategories( categories ){
	selectByValue(  $('general_input_category'), categories );
}



function loadWorkflow()
{
	selectACLSystem( defaultWorkflow.acl );
	selectCategories( defaultWorkflow.category.split(',') );
}

//Resize workarea to match window size
window.addEvent('resize',function(e){resizeWorkarea();});
window.addEvent('load',function(e){resizeWorkarea();updateCategoryList();loadWorkflow()});