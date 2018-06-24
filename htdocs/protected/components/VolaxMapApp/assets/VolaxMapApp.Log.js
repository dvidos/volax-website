/**
 * Assert the namespace
 */
var VolaxMapApp = VolaxMapApp || {};

/**
 * The Log object. 
 * Singleton, used for debugging.
 */
VolaxMapApp.Log = 
{
	CLASSNAME: 'VolaxMapApp.Log',
	
	buffer: '',
	divId: '',
	
	add: function(val)
	{
		this.messageAvailable(val);
	},
	
	dump: function(val, caption, maxDepth)
	{
		maxDepth = maxDepth || 3;
		var visitedItems = [];
		var msg = this.getDumpOf(val, '', 0, maxDepth, visitedItems, caption);
		
		this.messageAvailable(msg);
	},
	
	getDumpOf: function(val, indentation, recursionLevel, maxDepth, visitedItems, caption)
	{
		var descr = '';
		
		if (caption && caption.length > 0)
			descr += caption + ': ';
		
		try
		{
			if (typeof val == "undefined")
				descr += 'undefined';
				
			else if (typeof val == "string")
				descr += '"' + val + '"';
				
			else if (typeof val == "boolean")
				descr += (val ? 'true' : 'false');
				
			else if (typeof val == "number")
				descr += val;
				
			else if (typeof val == "function")
				descr += 'function()';
			
			else if (typeof val == "object")
			{
				if (val == null)
				{
					descr += 'null';
				}
				else if (Array.isArray(val))
				{
					if (visitedItems.indexOf(val) != -1)
					{
						descr += '(already visited array)';
					}
					else if (val.length == 0)
					{
						descr += '[]';
					}
					else if (recursionLevel >= maxDepth)
					{
						descr += '(Array with ' + val.length + ' items, but recursion too deep to continue)';
					}
					else
					{
						visitedItems.push(val);
						descr += 'array of ' + val.length + ' [' + "\r\n";
						var comma = false;
						for (x in val)
						{
							if (comma)
								descr += ', ' + "\r\n";
							
							descr += indentation + '    ' + this.getDumpOf(val[x], indentation + '    ', recursionLevel + 1, maxDepth, visitedItems, caption + '[' + x + ']');
							comma = true;
						}
						descr += "\r\n" + indentation + ']';
					}
				}
				else // it's an object
				{
					if (visitedItems.indexOf(val) != -1)
					{
						descr += '(already visited object)';
					}
					else if (val.length == 0)
					{
						descr += '{}';
					}
					else if (recursionLevel >= maxDepth)
					{
						descr += val.toString() + ' (non-null object, but recursion too deep to continue)';
					}
					else
					{
						visitedItems.push(val);
						descr += '{' + "\r\n";
						var comma = false;
						for (x in val)
						{
							if (comma)
								descr += ', ' + "\r\n";
							
							descr += indentation + '    ' + this.getDumpOf(val[x], indentation + '    ', recursionLevel + 1, maxDepth, visitedItems, x);
							comma = true;
						}
						descr += "\r\n" + indentation + '}';
					}
				}
			}
			else
			{
				descr += '(unknown)';
			}
		}
		catch (e)
		{
			descr += '(exception happened)';
		}
		
		return descr;
	},
	
	get: function()
	{
		return this.buffer;
	},
	
	clear: function()
	{
		this.buffer = '';
		this.update();
	},

	messageAvailable: function(msg)
	{
		// first keep in buffer
		this.buffer += msg + "\r\n";
		
		// if a div is available, use it.
		if (typeof this.divId == 'string' && this.divId.length > 0)
		{
			var div = document.getElementById(this.divId);
			if (div)
				div.innerHTML = this.buffer;
		}
		
		// if console is available, using again.
		if (typeof console !== 'undefined')
			console.log(msg);
	},
	
	toString: function()
	{
		return this.CLASSNAME;
	},
};
