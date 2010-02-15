/**
* This file contains the events router , elements are required to register their events to point to one of the following functions
 * and these functions will know which element triggered the event and will send the event directly to the element's internal handler "onUpdate,OnDelete,etc.."
*
* @version		$Id: event.js 29 2008-12-09 07:13:01Z dr_drsh $
* @package		Joomla
* @subpackage	JForms
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
 
function dispatch_onDragStart( element ){	
	
	//Get  Array index from container Element (li)
	var index = getIndexFromContainerElement(element);
	
	//Trigger onDragStart
	stationsArray[index].onDragStart();
}

function dispatch_onDrag( element ){	

	//Get  Array index from container Element
	var index = getIndexFromContainerElement(element);

	//Trigger onDrag 
	stationsArray[index].onDrag();
}

function dispatch_onDragEnd( element ){	
	
	//Get  Array index from container Element
	var index = getIndexFromContainerElement(element);

	//Trigger onDragEnd
	stationsArray[index].onDragEnd();
}	
	

function getLiParentRecurse( e ){
	
	e = $(e);
	//Get the current Element's parent
	//if(e.getParent)
	var p = e.getParent();
	
	//Is the current parent "The workspace" area? if so return the current element
	if( p.id == 'stations' )return e;
	//Otherwise ascend up by one level
	else return getLiParentRecurse( p );
}

function dispatch_onClick( e ){

	var element = getTarget( e )
	
	//Go through parents until reaching the container Li
	//This is done so that Any click on any element within the main Li should be considered a click on the Li itself
	element = getLiParentRecurse( element );	

	//Get  Array index from container Element
	var index = getIndexFromContainerElement(element);

	//Shorter access
	e = stationsArray[index];

	//Did we select the same element twice?
	if( selectedElement == 	e){
		return;
	}
	
	//Did we have a previosly selected Element?
	if( selectedElement != null ){
	
		saveElement();
		//trigger onBlur event on previously element
		selectedElement.onBlur();
	}
	//Set the element the triggered the event as SelectedElement
	selectedElement = e;
	
	//Highlight selected entry , disable all others
	for(i=0;i<stationsArray.length;i++){
		if(stationsArray[i] != null)stationsArray[i].deselect();
	}
	selectedElement.select();	
	
	//Trigger onFocus on current element
	selectedElement.onFocus();

	//Display properties of the selected element
	displayProperties();
	
	//Show Element properties tab contents
	enableTab( 'properties' );
	
}

function getTarget( e ){
	
	//Did we receive an element as parameter
	if( e.tagName ){
		return $(e);
	} else {
		//Or an event object?
		var event = new Event(e);
		return event.target;
	}

}
