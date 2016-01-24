/**
 * Assert the namespace
 */
var VolaxMapApp = VolaxMapApp || {};


/**
 * An Events manager object
 * Allows objects to have subscribed event listeners.
 */
VolaxMapApp.Event = function()
{
	this.subscribers = [];
};

VolaxMapApp.Event.prototype =
{
	CLASSNAME: 'VolaxMapApp.Event',
	
	register: function(context, callback)
	{
		this.subscribers.push({context: context, callback: callback});
	},
	
	fire: function(eventArgs)
	{
		for (x in this.subscribers)
		{
			var sub = this.subscribers[x];
			sub.callback.call(sub.context, eventArgs);
		}
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};
