<script>
(function($) {
Class = {

    create: function() {
        //figure out if we're creating a static or dynamic class
        var s = (arguments.length > 0 && //if we have arguments...
                arguments[arguments.length - 1].constructor == Boolean) ? //...and the last one is Boolean...
                    arguments[arguments.length - 1] : //...then it's the static flag...
                    false; //...otherwise default to a dynamic class
        
        //static: Object, dynamic: Function
        var c = s ? {} : function() {
            this.init.apply(this, arguments);
        }
        
        //all of our classes have these in common
        var methods = {
            //a basic namespace container to pass objects through
            ns: [],
            
            //a container to hold one level of overwritten methods
            supers: {},
            
            //a constructor
            init: function() {},
            
            //our namespace function
            namespace:function(ns) {
                //don't add nothing
                if (!ns) return null;
                
                //closures are neat
                var _this = this;
                
                //handle ['ns1', 'ns2'... 'nsN'] format
                if(ns.constructor == Array) {
                    //call namespace normally for each array item...
                    $.each(ns, function() {
                        _this.namespace.apply(_this, [this]);
                    });
                    
                    //...then get out of this call to namespace
                    return;
                
                //handle {'ns': contents} format
                } else if(ns.constructor == Object) {
                    //loop through the object passed to namespace
                    for(var key in ns) {
                        //only operate on vanilla Objects and Functions
                        if([Object, Function].indexOf(ns[key].constructor) > -1) {
                            //in case this.ns has been deleted
                            if(!this.ns) this.ns = [];
                            
                            //copy the namespace into an array holder
                            this.ns[key] = ns[key];
                            
                            //apply namespace, this will be caught by the ['ns1', 'ns2'... 'nsN'] format above
                            this.namespace.apply(this, [key]);
                        }
                    }
                    
                    //we're done with namespace for now
                    return;
                }
                
                //note: [{'ns': contents}, {'ns2': contents2}... {'nsN': contentsN}] is inherently handled by the above two cases
                
                var levels = ns.split(".");
                
                //if init && constructor == Object or Function
                var nsobj = this.prototype ? this.prototype : this;
                
                $.each(levels, function() {
                    /* When adding a namespace check to see, in order:
                     * 1) does the ns exist in our ns passthrough object?
                     * 2) does the ns already exist in our class
                     * 3) does the ns exist as a global var?
                        * NOTE: support for this was added so that you can namespace classes
                          into other classes, i.e. MyContainer.namespace('MyUtilClass'). this
                          behaviour is dangerously greedy though, so it may be removed.
                     * 4) if none of the above, make a new static class
                     */
                    nsobj[this] = _this.ns[this] || nsobj[this] || window[this] || Class.create(true);
                    
                    //remove our temp passthrough if it exists
                    delete _this.ns[this];
                    
                    //move one level deeper for the next iteration
                    nsobj = nsobj[this];
                });
                
                //TODO: do we really need to return this? it's not that useful anymore
                return nsobj;
            },
            
            /* create exists inside classes too. neat huh?
                usage differs slightly: MyClass.create('MySubClass', { myMethod: function() }); */
            create: function() {
                //turn arguments into a regular Array
                var args = Array.prototype.slice.call(arguments);
                
                //pull the name of the new class out
                var name = args.shift();
                
                //create a new class with the rest of the arguments
                var temp = Class.create.apply(Class, args);
                
                //load our new class into the {name: class} format to pass it into namespace()
                var ns = {};
                ns[name] = temp;
                
                //put the new class into the current one
                this.namespace(ns);
            },
            
            //call the super of a method
            sup: function() {
                try {
                    var caller = this.sup.caller.name;
                    this.supers[caller].apply(this, arguments);
                } catch(noSuper) {
                    return false;
                }
            }
        }
        
        //static: doesn't need a constructor
        s ? delete methods.init : null;
        
        //put default stuff in the thing before the other stuff
        $.extend(c, methods);
        
        //double copy methods for dynamic classes

        //they get our common utils in their class definition AND their prototype
        if(!s) $.extend(c.prototype, methods);
        
        //static: extend the Object, dynamic: extend the prototype
        var extendee = s ? c : c.prototype;

	indexOf = function(array,value){
		for(var a = 0 ; a<array.length ; a++){
			if(array[a]==value) return a;
		}
		return -1;
	}
        
        //loop through arguments. if they're the right type, tack them on
        $.each(arguments, function() {
            //either we're passing in an object full of methods, or the prototype of an existing class
            if(this.constructor == Object || typeof this.init != undefined) {
                /* here we're going per-property instead of doing $.extend(extendee, this) so that
                we overwrite each property instead of the whole namespace. also: we omit the 'namespace'
                helper method that Class tacks on, as there's no point in storing it as a super */
                for(i in this) {
                    /* if a property is a function (other than our built-in helpers) and it already exists
                    in the class, save it as a super. note that this only saves the last occurrence */
                    if(extendee[i] && extendee[i].constructor == Function && indexOf(['namespace','create','sup'],i) == -1) {
                        //since Function.name is almost never set for us, do it manually
                        this[i].name = extendee[i].name = i;
                        
                        //throw the existing function into this.supers before it's overwritten
                        extendee.supers[i] = extendee[i];
                    }
                    
                    //extend the current property into our class
                    extendee[i] = this[i];
                }
            }
        });
        
        //shiny new class, ready to go
        return c;
    }
};
})(jQuery);
/*
 * Copyright 2009 Don Benjamin
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at 
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License.
 */
CustomSearch = Class.create( {
	init : function (id,maxInput) {
		this.id=id;
		this.maxInput = maxInput;
		me = this;
		this.namesFor = CustomSearch.sharedOptions;
		if(id!='%i%') this.createFlexboxes();
	},
	createFlexboxes: function(){
		if(this.getForm().length<1){
			setTimeout("CustomSearch['"+this.id+"'].createFlexboxes()",100);
			return;
		}
		this.getForm().find('.form-field-title-div').each(function(k,el){
			el = jQuery(el);
			var index = el.attr('id').replace(/.*-/,'')
			me.createFlexbox(index);
		});
	},
	fieldExists: function(id){
		newId = 'config-form-'+this.id+'-'+id;
		return jQuery("#"+newId).attr('id');
	},
	add: function (){
		var html = jQuery('#config-template-'+this.id).html();
		var oldHtml = false;
		var count=0;
		do {
			newId = 'config-form-'+this.id+'-'+(++count);
		} while(this.fieldExists(count));

		html = this.replaceAll(html,'###TEMPLATE_ID###',count);
		html=html.replace('config-template-'+this.id,newId);
		jQuery('<div id="'+newId+'">'+html+"</div>").appendTo('#config-form-'+this.id);
		this.createFlexbox(count);
		if(count>this.maxInput) this.maxInput=count;
		
		return false;
	},
	replaceAll: function(haystack,find,replace){
		do {
			oldHaystack = haystack;
			haystack = haystack.replace(find,replace);
		} while(haystack!=oldHaystack);
		return haystack;
	},

	getForm: function (id){
		var htmId='#config-form-'+this.id;
		if(id) htmId+='-'+id;
		return jQuery(htmId);
	},
	remove: function (id){
		this.getForm(id).remove();
		return false;
	},

	updateAllOptionsFor: function(joiner){
		var i=0;
		for(;i<this.maxInput;i++){
			if(this.fieldExists(i) && (this.getJoinerFor(i)==joiner)){
				this.updateOptions(i,'joiner');
			}
		}
	},
	getJoinerFor: function(id){
		return this.getForm(id).find('.wpcfs-joiner').val();
	},
	updateOptions: function(id,changed) {
		switch(changed){
		case 'input':
		type = this.getForm(id).find('.wpcfs-input').val();
		template = jQuery('#config-input-templates-'+type+'-'+this.id);
		div = jQuery(hid = '#db_customsearch_widget-'+this.id+'-'+id+'-widget-config');
		html = template.html();
		if(!html) html='';

		html = this.replaceAll(html,'###TEMPLATE_ID###',id);
		name='';
		html = this.replaceAll(html,'###TEMPLATE_NAME###',name);
		div.html(html);
		break;
			case 'joiner':
				type=this.getJoinerFor(id);		
				if(this.namesFor[type]){
					this.flexboxData[id].results = this.namesFor[type];
					jQuery('#form-field-dbname-'+this.id+'-'+id).show();
					jQuery('#form-field-dbname-'+this.id+'-'+id);
				} else {
					jQuery('#form-field-dbname-'+this.id+'-'+id).hide();
				}
				break;
		}
	},
	flexboxData : {},
	createFlexbox: function(id){
	
	
	/* THIS IS IT HERE  PREMIUMPRESS */
	
		if(!this.flexboxData[id]) this.flexboxData[id] = {results:[]};
		initVal = jQuery('#form-field-dbname-'+this.id+'-'+id).find("input")[0].value; /*<-- */


		jQuery('#form-field-dbname-'+this.id+'-'+id).find("*").each(function(){jQuery(this).remove()})
		jQuery('#form-field-dbname-'+this.id+'-'+id).flexbox(this.flexboxData[id],{width:210,name:'db_customsearch_widget['+this.id+']['+id+'][name]',maxCacheBytes:0,paging:false,initialValue:initVal})
		this.updateOptions(id,'joiner');
       },
	toggleOptions: function(id){
		el = jQuery('#form-field-advancedoptions-'+this.id+'-'+id);
		if(el) el.toggle();
		return false;
	}
});
if(!CustomSearch.sharedOptions) CustomSearch.sharedOptions={};
CustomSearch.setOptionsFor = function(joiner,options){
	CustomSearch.sharedOptions[joiner] = options;
	var i;
	for(i=0;i<CustomSearch.list.length;i++)
		CustomSearch[CustomSearch.list[i]].updateAllOptionsFor(joiner);
};
CustomSearch.list = [];
CustomSearch.create = function(id,maxInput){
	CustomSearch.list[CustomSearch.list.length]=id;
	CustomSearch[id] = new CustomSearch(id,maxInput);
};
CustomSearch.get = function(id){
	if(!CustomSearch[id]) CustomSearch.create(id);
	return CustomSearch[id];
};

var testing=false;
if(testing)
jQuery(document).ready(function(){
	jQuery('.widget-control-edit').click();
});
	dbg = function(obj){
		output='DEBUG:';
		output+=obj;
		count=0;
		for(prop in obj){
			output+="\n"+(typeof(obj[prop]))+":	"+prop;
			if(count++>=30) {
				if(!confirm(output)) return;
				output="";
				count=0;
			}
		}
		alert(output);
	};

 
(function($) {
    jQuery.flexbox = function(div, o) {
 

        var timeout = false, 	// hold timeout ID for suggestion results to appear
        cache = [], 		    // simple array with cacheData key values, MRU is the first element
        cacheData = [],         // associative array holding actual cached data
        cacheSize = 0, 		    // size of cache in bytes (cache up to o.maxCacheBytes bytes)
        delim = '\u25CA',       // use an obscure unicode character (lozenge) as the cache key delimiter
        scrolling = false,
        pageSize = o.paging.pageSize,
        $div = jQuery(div).css('position', 'relative');
	$div.css('z-index', 10);  

        // The hiddenField MUST be appended to the div before the input, or IE7 does not shift the dropdown below the input field (it overlaps)
	var name = (o.name=='asID') ? $div.attr('id') : o.name;
        var $hdn = jQuery(document.createElement('input'))
            .attr('type', 'hidden')
            .attr('id', $div.attr('id') + '_hidden')
            .attr('name', name)
            .val(o.initialValue)
            .appendTo($div);

        var $input = jQuery(document.createElement('input'))
            .attr('id', $div.attr('id') + '_input')
            .attr('autocomplete', 'off') 
            .addClass(o.inputClass)
            .css('width', o.width + 'px');
	$input.appendTo($div)
            .click(function(e) {
                if (o.watermark !== '' && this.value === o.watermark)
                    this.value = '';
                else
                    this.select();
            })
            .focus(function(e) {
                jQuery(this).removeClass('watermark');
            })
            .blur(function(e) {
                setTimeout(function() { if (!$input.attr('active')) hideResults(); }, 200);
            })
            .keypress(processKey);

        if (o.initialValue !== '')
            $input.val(o.initialValue).removeClass('watermark');
        else
            $input.val(o.watermark).addClass('watermark');

        if (jQuery.browser.msie)
            $input.keydown(processKey);

        var arrowWidth = 0;
        if (o.showArrow && o.showResults) {
            var arrowClick = function() {
                if ($ctr.is(':visible')) {
                    hideResults();
                }
                else {
                    $input.focus();
                    if (o.watermark !== '' && $input.val() === o.watermark)
                        $input.val('');
                    else
                        $input.select();
                    if (timeout)
                        clearTimeout(timeout);
                    timeout = setTimeout(function() { flexbox(1, true, o.arrowQuery); }, o.queryDelay);
                }
            };
            var $arrow = jQuery(document.createElement('span'))
                .attr('id', $div.attr('id') + '_arrow')
                .addClass(o.arrowClass)
                .addClass('out')
                .hover(function() {
                    jQuery(this).removeClass('out').addClass('over');
                }, function() {
                    jQuery(this).removeClass('over').addClass('out');
                })
                .mousedown(function() {
                    jQuery(this).removeClass('over').addClass('active');
                })
                .mouseup(function() {
                    jQuery(this).removeClass('active').addClass('over');
                })
                .click(arrowClick)
                .appendTo($div);
            arrowWidth = $arrow.outerWidth();
            $input.css('width', (o.width - $arrow.width()) + 'px');
        }
        if (!o.allowInput) $input.click(arrowClick); // simulate <select> behavior

        var left = (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) === '6')
            ? -($input.outerWidth() + arrowWidth)
            : 0;
/*	ctr = jQuery(document.createElement('div'));
	ctr.attr('id','testing');
	ctr.css('width',1000);
	ctr.addClass('css_class');
	ctr.appendTo($div);
	ctr.hide();*/
        var $ctr = jQuery(document.createElement('div'))
            .attr('id', $div.attr('id') + '_ctr');
        $ctr.css('width', ($input.outerWidth() + arrowWidth - 2) + 'px'); // TODO: The -2 here might be because of the border... try to fix
        $ctr.css('top', $input.outerHeight());
	$ctr.css('left', left);
        $ctr.addClass(o.containerClass);
        $ctr.appendTo($div);
        $ctr.hide();

		$ctr.oldShow = $ctr.show;
		$ctr.show = function(){
	            $ctr.css('width', ($input.outerWidth() + arrowWidth - 2) + 'px');
            	    $ctr.css('top', $input.outerHeight())
            	    $ctr.css('left', left)
		    $ctr.oldShow();
		};
        var $content = jQuery(document.createElement('div'))
            .addClass(o.contentClass)
            .appendTo($ctr)
            .scroll(function() {
                scrolling = true;
            });

        var $paging = jQuery(document.createElement('div')).appendTo($ctr);

        function processKey(e) {
            // handle modifiers
            var mod = 0;
            if (typeof (e.ctrlKey) !== 'undefined') {
                if (e.ctrlKey) mod |= 1;
                if (e.shiftKey) mod |= 2;
            } else {
                if (e.modifiers & Event.CONTROL_MASK) mod |= 1;
                if (e.modifiers & Event.SHIFT_MASK) mod |= 2;
            }
            // if the keyCode is one of the modifiers, bail out (we'll catch it on the next keypress)
            if (/16$|17$/.test(e.keyCode)) return; // 16 = Shift, 17 = Ctrl

            var tab = e.keyCode === 9;
            var tabWithModifiers = e.keyCode === 9 && mod > 0;
            var backspace = e.keyCode === 8; // we will end up extending the delay time for backspaces...

            // tab is a special case, since we want to bubble events...
            if (tab) if (getCurr()) selectCurr();

            // handling up/down/escape/right arrow/left arrow requires results to be visible
            // handling enter requires that AND a result to be selected
            if ((/27$|38$|39$|37$/.test(e.keyCode) && $ctr.is(':visible')) ||
				(/13$|40$/.test(e.keyCode)) || !o.allowInput) {

                if (e.preventDefault) e.preventDefault();
                if (e.stopPropagation) e.stopPropagation();

                e.cancelBubble = true;
                e.returnValue = false;

                switch (e.keyCode) {
                    case 38: // up
                        prevResult();
                        break;
                    case 40: // down
                        if ($ctr.is(':visible')) nextResult();
                        else flexboxDelay(true);
                        break;
                    case 13: // enter
                        if (getCurr()) selectCurr();
                        else flexboxDelay(true);
                        break;
                    case 27: //	escape
                        hideResults();
                        break;
                    case 39: // right arrow
                        jQuery('#' + $div.attr('id') + 'n').click();
                        break;
                    case 37: // left arrow
                        jQuery('#' + $div.attr('id') + 'p').click();
                        break;
                    default:
                        if (!o.allowInput) { return; }
                }
            } else if (!tab && !tabWithModifiers) { // skip tab key and any modifiers
                flexboxDelay(false, backspace);
            }
        }

        function flexboxDelay(simulateArrowClick, increaseDelay) {
            if (timeout) clearTimeout(timeout);
            var delay = increaseDelay ? o.queryDelay * 5 : o.queryDelay;
            timeout = setTimeout(function() { flexbox(1, simulateArrowClick, ''); }, delay);
        }

        function flexbox(p, arrowOrPagingClicked, prevQuery) {
            var q = prevQuery && prevQuery.length > 0 ? prevQuery : jQuery.trim($input.val());

            if (q.length >= o.minChars || arrowOrPagingClicked) {
                $content.html('').attr('scrollTop', 0);
                var cached = checkCache(q, p);
                if (cached) {
                    displayItems(cached.data, q);
                    showPaging(p, cached.t);
                }
                else {
                    pageSize = pageSize === undefined ? 0 : pageSize;

                    var params = { q: q, p: p, s: pageSize, contentType: 'application/json; charset=utf-8' };
                    var callback = function(data, overrideQuery) {
                        if (overrideQuery === true) q = overrideQuery; // must compare to boolean because by default, the string value "success" is passed when the jQuery jQuery.getJSON method's callback is called
                        var totalResults = parseInt(data[o.totalProperty]);

                        // Handle client-side paging, if any paging configuration options were specified
                        if (isNaN(totalResults) && o.paging) {
                            if (o.maxCacheBytes <= 0) alert('The "maxCacheBytes" configuration option must be greater\nthan zero when implementing client-side paging.');
                            totalResults = data.results.length;

                            var pages = totalResults / pageSize;
                            if (totalResults % pageSize > 0) pages = parseInt(++pages);

                            for (var i = 1; i <= pages; i++) {
                                var pageData = {};
                                pageData[o.totalProperty] = totalResults;
                                pageData[o.resultsProperty] = data.results.splice(0, pageSize);
                                if (i === 1) totalSize = displayItems(pageData, q);
                                updateCache(q, i, pageSize, totalResults, pageData, totalSize);
                            }
                        }
                        else {
                            var totalSize = displayItems(data, q);
                            updateCache(q, p, pageSize, totalResults, data, totalSize);
                        }
                        showPaging(p, totalResults);
                    };
                    if (typeof (o.source) === 'object') callback(o.source, '');
                    else if (o.method.toUpperCase() == 'POST') jQuery.post(o.source, params, callback, "json");
                    else jQuery.getJSON(o.source, params, callback);
                }
            } else
                hideResults();
        }

        function showPaging(p, totalResults) {
            $paging.html('').removeClass(o.paging.cssClass); // clear out for threshold scenarios
            $content.css('height', 'auto');
            if (o.showResults && o.paging && totalResults > pageSize) {
                var pages = totalResults / pageSize;
                if (totalResults % pageSize > 0) pages = parseInt(++pages);
                outputPagingLinks(pages, p, totalResults);
            }
        }

        function handleKeyPress(e, page, totalPages) {
            if (/^13$|^39$|^37$/.test(e.keyCode)) {
                if (e.preventDefault)
                    e.preventDefault();
                if (e.stopPropagation)
                    e.stopPropagation();

                e.cancelBubble = true;
                e.returnValue = false;

                switch (e.keyCode) {
                    case 13: // Enter
                        if (/^\d+$/.test(page) && page <= totalPages)
                            flexbox(page, true);
                        else
                            alert('Please enter a page number less than or equal to ' + totalPages);
                        // TODO: make this alert a function call, and a customizable parameter
                        break;
                    case 39: // right arrow
                        jQuery('#' + $div.attr('id') + 'n').click();
                        break;
                    case 37: // left arrow
                        jQuery('#' + $div.attr('id') + 'p').click();
                        break;
                }
            }
        }

        function handlePagingClick(e) {
            $input.attr('active', true);
            flexbox(parseInt(jQuery(this).attr('page')), true, $input.attr('pq')); // pq == previous query
            return false;
        }

        function outputPagingLinks(totalPages, currentPage, totalResults) {
            // TODO: make these configurable images
            var first = '&lt;&lt;',
            prev = '&lt;',
            next = '&gt;',
            last = '&gt;&gt;',
            more = '...';

            $paging.addClass(o.paging.cssClass);

            // set up our base page link element
            var $link = jQuery(document.createElement('a'))
                .attr('href', '#')
                .addClass('page')
                .click(handlePagingClick),
            $span = jQuery(document.createElement('span')).addClass('page'),
            divId = $div.attr('id');

            // show first page
            if (currentPage > 1) {
                $link.clone(true).attr('id', divId + 'f').attr('page', 1).html(first).appendTo($paging);
                $link.clone(true).attr('id', divId + 'p').attr('page', currentPage - 1).html(prev).appendTo($paging);
            }
            else {
                $span.clone(true).html(first).appendTo($paging);
                $span.clone(true).html(prev).appendTo($paging);
            }

            if (o.paging.style === 'links') {
                var maxPageLinks = o.paging.maxPageLinks;
                // show page numbers
                if (totalPages <= maxPageLinks) {
                    for (var i = 1; i <= totalPages; i++) {

                        if (i === currentPage) {
                            $span.clone(true).html(currentPage).appendTo($paging);
                        }
                        else {
                            $link.clone(true).attr('page', i).html(i).appendTo($paging);
                        }
                    }
                }
                else {
                    if ((currentPage + parseInt(maxPageLinks / 2)) > totalPages) {
                        startPage = totalPages - maxPageLinks + 1;
                    }
                    else {
                        startPage = currentPage - parseInt(maxPageLinks / 2);
                    }

                    if (startPage > 1) {
                        $link.clone(true).attr('page', startPage - 1).html(more).appendTo($paging);
                    }
                    else {
                        startPage = 1;
                    }

                    for (var i = startPage; i < startPage + maxPageLinks; i++) {
                        if (i === currentPage) {
                            $span.clone(true).html(i).appendTo($paging);
                        }
                        else {
                            $link.clone(true).attr('page', i).html(i).appendTo($paging);
                        }
                    }

                    if (totalPages > (startPage + maxPageLinks)) {
                        $link.clone(true).attr('page', i).html(more).appendTo($paging);
                    }
                }
            }
            else if (o.paging.style === 'input') {
                var $pagingBox = jQuery(document.createElement('input'))
                    .addClass('box')
                    .click(function(e) {
                        $input.attr('active', true);
                        this.select();
                    })
                    .keypress(function(e) {
                        return handleKeyPress(e, this.value, totalPages);
                    })
                    .val(currentPage)
                    .appendTo($paging);
            }

            if (currentPage < totalPages) {
                var blort = $link.clone(true).attr('id', divId + 'n').attr('page', +currentPage + 1).html(next).appendTo($paging);
                $link.clone(true).attr('id', divId + 'l').attr('page', totalPages).html(last).appendTo($paging);
                // prevents flashing dropdown when retrieving data between pages
                $content.css('height', ($row.outerHeight() * pageSize) + 'px');
            }
            else {
                $span.clone(true).html(next).appendTo($paging);
                $span.clone(true).html(last).appendTo($paging);
                $content.css('height', 'auto');
            }

            var startingResult = (currentPage - 1) * pageSize + 1;
            var endingResult = (startingResult > (totalResults - pageSize)) ? totalResults : startingResult + pageSize - 1;

            if (o.paging.showSummary) {
                var summaryData = {
                    "start": startingResult,
                    "end": endingResult,
                    "total": totalResults,
                    "page": currentPage,
                    "pages": totalPages
                };
                var html = o.paging.summaryTemplate.applyTemplate(summaryData);
                jQuery(document.createElement('span'))
                    .addClass(o.paging.summaryClass)
                    .html(html)
                    .appendTo($paging);
            }
        }

        function checkCache(q, p) {
            var key = q + delim + p; // use null character as delimiter
            if (cacheData[key]) {
                for (var i = 0; i < cache.length; i++) { // TODO: is it possible to not loop here?
                    if (cache[i] === key) {
                        // pull out the matching element (splice), and add it to the beginning of the array (unshift)
                        cache.unshift(cache.splice(i, 1)[0]);
                        return cacheData[key];
                    }
                }
            }
            return false;
        }

        function updateCache(q, p, s, t, data, size) {
            if (o.maxCacheBytes > 0) {
                while (cache.length && (cacheSize + size > o.maxCacheBytes)) {
                    var cached = cache.pop();
                    cacheSize -= cached.size;
                }
                var key = q + delim + p; // use null character as delimiter
                cacheData[key] = {
                    q: q,
                    p: p,
                    s: s,
                    t: t,
                    size: size,
                    data: data
                }; // add the data to the cache at the hash key location
                cache.push(key); // add the key to the MRU list
                cacheSize += size;
            }
        }

        function displayItems(d, q) {
            var totalSize = 0;

            if (!d)
                return;

            if (parseInt(d[o.totalProperty]) === 0 && o.noResultsText && o.noResultsText.length > 0) {
                $content.addClass(o.noResultsClass).html(o.noResultsText);
                $ctr.show();
                return;
            } else $content.removeClass(o.noResultsClass);

            for (var i = 0; i < d[o.resultsProperty].length; i++) {
                var data = d[o.resultsProperty][i],
                result = o.resultTemplate.applyTemplate(data),
                exactMatch = q === result,
                selectedMatch = false,
                hasHtmlTags = false;

                if (!exactMatch && o.highlightMatches && q !== '') {
                    var pattern = q,
                    replaceString = '<span class="' + o.matchClass + '">' + q + '</span>';
                    if (result.match('<(.|\n)*?>')) { // see if the content contains html tags
                        hasHtmlTags = true;
                        pattern = '(>)([^<]*?)(' + q + ')((.|\n)*?)(<)'; // TODO: look for a better way
                        replaceString = '$1$2<span class="' + o.matchClass + '">$3</span>$4$6';
                    }
                    result = result.replace(new RegExp(pattern, o.highlightMatchesRegExModifier), replaceString);
                }

                // write the value of the first match to the input box, and select the remainder,
                // but only if autoCompleteFirstMatch is set, and there are no html tags in the response
                if (o.autoCompleteFirstMatch && !hasHtmlTags && i === 0) {
                    var firstMatch = data[o.displayValue];
                    if (q.length > 0 && firstMatch.indexOf(q) === 0) {
                        $input.attr('pq', q); // pq == previous query
                        $input.val(firstMatch);
                        selectedMatch = selectRange(q.length, $input.val().length);
                    }
                }

                if (!o.showResults) return;

                $row = jQuery(document.createElement('div'))
                    .attr('id', data[o.displayValue])
                    .attr('val', data[o.hiddenValue])
                    .addClass('row')
                    .html(result)
                    .click(function(e) { $input.attr('active', true); })
                    .appendTo($content);

                // remove the border from the bottom of the last result if paging is off
                if ((!o.paging || (o.paging && pageSize > d[o.totalProperty])) && i === d[o.resultsProperty].length - 1) {
                    $row.css('border-bottom', 'none');
                }

                if (exactMatch || selectedMatch) {
                    $row.addClass(o.selectClass);
                }
                totalSize += result.length;
            }

            if (totalSize === 0) {
                hideResults();
                return;
            }

            $ctr.parent().css('z-index', 11000);
            $ctr.show();

            $content
				.children('div')
				.mouseover(function() {
				    $content.children('div').removeClass(o.selectClass);
				    jQuery(this).addClass(o.selectClass);
				})
				.click(function(e) {
				    e.preventDefault();
				    e.stopPropagation();
				    selectCurr();
				});

            if (o.maxVisibleRows > 0) {
                //var maxHeight = $row.outerHeight() * o.maxVisibleRows;
                var maxHeight = Math.round($row.parent()[0].scrollHeight / d[o.totalProperty]) * o.maxVisibleRows;
                $content.css('maxHeight', maxHeight);
            }
            else
                $content.css('height', 'auto');

            return totalSize;
        }

        function selectRange(s, l) {
            var tb = $input[0];
            if (tb.createTextRange) {
                var r = tb.createTextRange();
                r.moveStart('character', s);
                r.moveEnd('character', l - tb.value.length);
                r.select();
            } else if (tb.setSelectionRange) {
                tb.setSelectionRange(s, l);
            }
            tb.focus();
            return true;
        }

        String.prototype.applyTemplate = function(d) {
            try {
                if (d === '') return this;
                return this.replace(/{([^{}]*)}/g,
                    function(a, b) {
                        var r;
                        if (b.indexOf('.') !== -1) { // handle dot notation in {}, such as {Thumbnail.Url}
                            var ary = b.split('.');
                            var obj = d;
                            for (var i = 0; i < ary.length; i++)
                                obj = obj[ary[i]];
                            r = obj;
                        }
                        else
                            r = d[b];
                        if (typeof r === 'string' || typeof r === 'number') return r; else throw (a);
                    }
                );
            } catch (ex) {
                alert('Invalid JSON property ' + ex + ' found when trying to apply resultTemplate or paging.summaryTemplate.\nPlease check your spelling and try again.');
            }
        };

        function hideResults() {
            $input.attr('active', false); // for input blur
            $div.css('z-index', 0);
            $ctr.hide();
        }

        function getCurr() {
            if (!$ctr.is(':visible'))
                return false;

            var $curr = $content.children('div.' + o.selectClass);

            if (!$curr.length)
                $curr = false;

            return $curr;
        }

        function selectCurr() {
            $curr = getCurr();

            if ($curr) {
                $input.val($curr.attr('id')).focus();
                $hdn.val($curr.attr('val'));
                hideResults();

                if (o.onSelect) {
                    $input.attr('hiddenValue', $hdn.val());
                    o.onSelect.apply($input[0]);
                }
            }
        }

        function nextResult() {
            $curr = getCurr();

            if ($curr && $curr.next().length > 0) {
                $curr
					.removeClass(o.selectClass)
					.next()
						.addClass(o.selectClass);
                var scrollPos = $content.attr('scrollTop'),
                curr = $curr[0],
                parentBottom, bottom, height;
                if (jQuery.browser.mozilla && parseInt(jQuery.browser.version) <= 2) {
                    parentBottom = document.getBoxObjectFor($content[0]).y + $content.attr('offsetHeight');
                    bottom = document.getBoxObjectFor(curr).y + $curr.attr('offsetHeight');
                    height = document.getBoxObjectFor(curr).height;
                }
                else { // IE and FF3
                    parentBottom = $content[0].getBoundingClientRect().bottom;
                    var rect = curr.getBoundingClientRect();
                    bottom = rect.bottom;
                    height = bottom - rect.top;
                }
                if (bottom >= parentBottom)
                    $content.attr('scrollTop', scrollPos + height);
            }
            else if (!$curr)
                $content.children('div:first-child').addClass(o.selectClass);
        }

        function prevResult() {
            $curr = getCurr();

            if ($curr && $curr.prev().length > 0) {
                $curr
					.removeClass(o.selectClass)
					.prev()
						.addClass(o.selectClass);
                var scrollPos = $content.attr('scrollTop'),
                curr = $curr[0],
                parent = $curr.parent()[0],
                parentTop, top, height;
                if (jQuery.browser.mozilla && parseInt(jQuery.browser.version) <= 2) {
                    height = document.getBoxObjectFor(curr).height;
                    parentTop = document.getBoxObjectFor($content[0]).y - (height * 2); // TODO: this is not working when i add another control...
                    top = document.getBoxObjectFor(curr).y - document.getBoxObjectFor($content[0]).y;
                }
                else { // IE and FF3
                    parentTop = parent.getBoundingClientRect().top;
                    var rect = curr.getBoundingClientRect();
                    top = rect.top;
                    height = rect.bottom - top;
                }
                if (top <= parentTop)
                    $content.attr('scrollTop', scrollPos - height);
            }
            else if (!$curr)
                $content.children('div:last-child').addClass(o.selectClass);
        }
    };

    jQuery.fn.flexbox = function(source, options) {
        if (!source)
            return;

        try {
            var defaults = jQuery.fn.flexbox.defaults;
            var o = jQuery.extend({}, defaults, options);

            for (var prop in o) {
                if (defaults[prop] === undefined) throw ('Invalid option specified: ' + prop + '\nPlease check your spelling and try again.');
            }
            o.source = source;

            if (options) {
                o.paging = (options.paging || options.paging == null) ? jQuery.extend({}, defaults.paging, options.paging) : false;

                for (var prop in o.paging) {
                    if (defaults.paging[prop] === undefined) throw ('Invalid option specified: ' + prop + '\nPlease check your spelling and try again.');
                }

                if (options.displayValue && !options.hiddenValue) {
                    o.hiddenValue = options.displayValue;
                }
            }

            this.each(function() {
                new jQuery.flexbox(this, o);
            });

            return this;
        } catch (ex) {
            if (typeof ex === 'object') alert(ex.message); else alert(ex);
        }
    };

    // plugin defaults - added as a property on our plugin function so they can be set independently
    jQuery.fn.flexbox.defaults = {
        method: 'GET', // One of 'GET' or 'POST'
        queryDelay: 100, // num of milliseconds before query is run.
        allowInput: true, // set to false to disallow the user from typing in queries
        containerClass: 'ffb',
        contentClass: 'content1',
        selectClass: 'ffb-sel',
        inputClass: 'ffb-input',
        arrowClass: 'ffb-arrow',
        matchClass: 'ffb-match',
        noResultsText: 'No matching results', // text to show when no results match the query
        noResultsClass: 'ffb-no-results', // class to apply to noResultsText
        showResults: true, // whether to show results at all, or just typeahead
        autoCompleteFirstMatch: true, // whether to complete and highlight the first matching value
        highlightMatches: true, // whether all matches within the string should be highlighted with matchClass
        highlightMatchesRegExModifier: 'i', // 'i' for case-insensitive, 'g' for global (all occurrences), or combine
        minChars: 1, // the minimum number of characters the user must enter before a search is executed
        showArrow: true, // set to false to simulate google suggest
        arrowQuery: '', // the query to run when the arrow is clicked
        onSelect: false, // function to run when a result is selected.  this.getAttribute('hiddenValue') gets you the value of options.hiddenValue
        maxCacheBytes: 32768, // in bytes, 0 means caching is disabled
        resultTemplate: '{name}', // html template for each row (put json properties in curly braces)
        displayValue: 'name', // json element whose value is displayed on select
        hiddenValue: 'id', // json element whose value submitted when form is submitted
        initialValue: '', // what should the value of the input field be when the form is loaded?
        watermark: '', // text that appears when flexbox is loaded, if no initialValue is specified.  style with css class '.ffb-input.watermark'
        width: 200, // total width of flexbox.  auto-adjusts based on showArrow value
        resultsProperty: 'results', // json property in response that references array of results
        totalProperty: 'total', // json property in response that references the total results (for paging)
        maxVisibleRows: 0, // default is 0, which means it is ignored.  use either this, or paging.pageSize
        paging: {
            style: 'input', // or 'links'
            cssClass: 'paging', // prefix with containerClass (e.g. .ffb .paging)
            pageSize: 10, // acts as a threshold.  if <= pageSize results, paging doesn't appear
            maxPageLinks: 5, // used only if style is 'links'
            showSummary: true, // whether to show 'displaying 1-10 of 200 results' text
            summaryClass: 'summary', // class for 'displaying 1-10 of 200 results', prefix with containerClass
            summaryTemplate: 'Displaying {start}-{end} of {total} results' // can use {page} and {pages} as well
        },
	name: 'asID'
    };

    jQuery.fn.setValue = function(val) {
        var id = '#' + this.attr('id');
        jQuery(id + '_hidden,' + id + '_input').val(val).removeClass('watermark');
    };
})(jQuery);


</script>


<style>
 
.ffb-input {
	float:left;
	color:#000; /* must specify along with watermark color */
 border-right: none;
}
/* Color of watermark, if present */
.ffb-input.watermark { /* added and removed dynamically */
	color:#888; /* must specify along with input color */
}
/* Drop-down arrow, with sprited image */
.ffb-arrow {
	float:left;
	width:18px;
	height:22px;
	background-image:url(<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/PPT/img/sel.gif);
}
.ffb-arrow.out { /* css sprite technique */
	background-position:0;
}
.ffb-arrow.over { /* css sprite technique */
	background-position:-18px 0;
	z-index:1000;
}
.ffb-arrow.active { /* css sprite technique */
	background-position:-36px 0;
}
.ffb-no-results 
{
	padding: 2px;
	color:#888;
	font-style:italic;
}
/* Container for dropdown contents */
.ffb {
	position:absolute; /* this guy's parent div is hard-coded to position:relative */
	 overflow:hidden;
	border-left:1px solid #7B9EBD;
	border-right:1px solid #7B9EBD;
	border-bottom:1px solid #7B9EBD;
	background-color:#fff; /* Give it a background-color, so it's not transparent */
	 
}
/* Inner div for dropdown */
.ffb .content1 {
	overflow:auto;
background:#efefef;
}
.ffb .content1 .row {
	border-bottom:1px solid #7B9EBD;
	color:#555;
	height:20px;
	clear:both;
}
.ffb-sel {
	cursor:pointer;
	cursor:hand;
	background-color:#ddd;
}
.ffb-match {
	background-color:#ff9; /* light yellow */
	text-decoration:underline;
	color:#000;
}

/* Paging */
.ffb .paging {
	margin:2px;
	vertical-align:middle;
}
.ffb .page, .ffb a.page {
	font-size:85%;
	padding:2px;
	 
	background-color:#eef;
	margin:2px;
	float:left;
}
.ffb .box {
	width:18px;
	margin:2px;
	float:left;
}
.ffb .summary {
	font-size:85%;
	float:right;
}

/* Unique IDs */
#ffb8 .row .col1 {
	float:left;
	width:132px;
}
#ffb8 .row .col2 {
	float:left;
	width:232px;
}

.form-title-input {
	width: 100%;
}
.form-field-table {
	width: 100%;
	text-align: center;
}
.form-field-table td {
	text-align: center;
}
.form-field-table select {
	width: 100%;
}
.form-field-title-div {
 

}
 
.field-wrapper {
	border: solid black 1px;
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 3px;
}

.ffb-arrow {
	margin-top: 1px;
	border-top: solid gray 2px;
}
 
.presets-selector {
	clear: both;
}


.form-field-title { font-size:14px; width:240px; }
</style>