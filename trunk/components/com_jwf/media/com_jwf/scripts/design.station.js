/**
* CStation Class
* @version		$Id$
* @package		Joomla
* @subpackage	Workflow
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
 *
 * @class The class represents a "Station" in the design views
 *
 * @constructor
 * @param {Object} parent: Reference to the "<ul>" element that acts as the container for the whole WYSIWYG enviornment
 * @param {int}id: index of this CElement instance in the elementArray array
 * @param {Object}beforeObject: Reference to the object before which this element will be added
 * @param {object} params : list of parameters in key:value pairs
 **/
 
var CStation = new Class({
	
  initialize: function( parent, id, params ) {
	

	this.index         = id ;
	
	//Extend with mootools
	this.parentElement = $(parent);
	this.acl    = params.acl;
	this.title  = params.title;
	this.task   = params.task;
	this.allocatedTime = params.allocatedTime;
	this.fields = params.fields;
        this.activeValidations = params.activeValidations;
	this.activeHooks = params.activeHooks;
	
	this.id     = params.id;
	
	
	
	
	
	//Create the li container element
	this.htmlContainer = new Element('li',{
		//Set a meaningful id for the element
		'id': this.parentElement.id + "_" +  this.index,
		'class': 'element',
		//Listen to "onClick" event
		'events': {
			'click':   function(e){dispatch_onClick(e)}	
		}
	});
	
	this.htmlOrder = new Element('div',{
		'class':'element-order'
	});
	
	this.htmlTitle = new Element('div',{
		'class':'element-title',
		'html':this.title
	});
	
	this.htmlTask = new Element('div',{
		'class':'element-task',
		'html':this.task
	});
	
	this.htmlACL = new Element('div',{
		'class':'element-acl',
		'html':this.acl.name
	});
	
	
	
	
	
	//Create the "delete button"
	this.htmlDeleteButton  = new Element('button',{
		'class': 'delete-button',
		'events': {}
	});

	//Add the delete button to this element "li" contianer
	this.htmlOrder.inject(this.htmlContainer);
	this.htmlTitle.inject(this.htmlContainer);
	new Element('div',{'class':'clear'}).inject(this.htmlContainer);
	this.htmlTask.inject(this.htmlContainer);
	this.htmlACL.inject(this.htmlContainer);
	//this.htmlDeleteButton.inject(this.htmlContainer);
	new Element('div',{'class':'clear'}).inject(this.htmlContainer);
	
	
	this.htmlContainer.inject(this.parentElement);
	workspaceSortable = new Sortables(this.parentElement,
	{
			onStart: dispatch_onDragStart,
			onDrag:  dispatch_onDrag,
			onComplete:dispatch_onDragEnd,
			clone:true,
 			revert:{ duration: 300, transition: 'quart:out' },
   			constrain: false
	});
	var order=getOrder(this.htmlContainer);
	
	this.htmlOrder.set({'html':++order});
		
  },
  
  //Javascript event manager use these methods to select/deselect elements, each element can define how it wants to look when selected :P
  deselect: function() { 
	this.htmlContainer.removeClass('selected'); 
    this.htmlDeleteButton.set({'styles':{ 'visibility' : 'hidden' }})
	
  },
  select  : function() { 
	this.htmlContainer.addClass('selected'); 
    this.htmlDeleteButton.set({'styles':{ 'visibility' : 'visible' }})
  },
  
  
  //Called when the user click "save" on a property page
  onUpdate:     function() {
		this.htmlTitle.set({'html':this.title});
		this.htmlTask.set({'html':this.task});
		this.htmlACL.set({'html':this.acl.name});

  },
  
  //Called when the element get focus "becomes selected"
  onFocus:	    function() {;},
  
  //Called when the element loses focus 
  onBlur:	    function() {;},

  //Called on beginning of Dragging
  onDragStart:  function() {;},
 
 //Called with every step of the dragging process
  onDrag:       function() {;},
  
  //Called when dragging ends
  onDragEnd:    function() {updateOrdering();},

  serialize: function(){

		var order = getOrder( this.htmlContainer );	
		this.task = this.task.replace(/\r/g,'').addSlashes().replace(/\n/g,"\\n");

		var activeHooksArray = new Array();var i=0;
		for(var key in this.activeHooks){
			if( typeof(this.activeHooks[key]) != 'function' && key != '$family'){
				this.activeHooks[key].name = key;
				activeHooksArray[i] = this.activeHooks[key];
				i++;
			}
		}

		return JSON.encode({
			id:this.id,
			order:order,
			acl:this.acl,
			title:this.title,
			task:this.task,
			allocatedTime:this.allocatedTime,
			fields:this.fields,
                        activeValidations:this.activeValidations,
			activeHooks: activeHooksArray
		});	
  },
  
  
  //Called when the element is going to be deleted
  onDelete:   function() {
  
    	this.htmlContainer.removeEvents();
	  	this.htmlContainer.empty();
		this.htmlContainer.dispose();
  },
  
  kill: function() {
	this.htmlDeleteButton.fireEvent( 'click' );
  }
});