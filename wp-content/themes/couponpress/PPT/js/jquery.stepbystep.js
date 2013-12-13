(function($){
	function openContent(event)
	{
		var options = event.data.options;
		event.preventDefault();
		//contentElement
		var contentElement = null;
		if(jQuery(this).attr("href")!=undefined && jQuery(this).attr("href").substr(0,1)!="#" && jQuery(this).attr("href")!="")
			contentElement = jQuery("#"+jQuery(this).attr("href").split("#")[1]);
		else
			contentElement = jQuery(jQuery(this).attr("href"));
		var currentStepNumber = jQuery("#"+event.data.panelId).children("[href]").index(jQuery(this));
		if(false === options.onOpen(event, jQuery(this), contentElement, currentStepNumber+1))
		{
			jQuery(this).trigger("mouseout");
			return;
		}
		jQuery(this).unbind("mouseout", hoverOut);
		if(jQuery(this).attr("href")!=undefined && jQuery(this).attr("href").substr(0,1)!="#" && jQuery(this).attr("href")!="")
		{
			var url = jQuery(this).attr("href").split("#")[0];
			var contentId = jQuery(this).attr("href").split("#")[1];
			jQuery(event.data.contentsSelector+":not(#"+contentId+")").css("display", "none");
			var self = jQuery(this);
			$.ajax({
				url: url,
				type: "post",
				async: false,
				success: function(data){
					jQuery("#"+contentId).css({"opacity":0, "display":"block"}).html(data).animate({opacity:1}, options.fadeSpeed, function(){if(jQuery.browser.msie)this.style.removeAttribute("filter");/*IE bug fix*/});
				}
			});
		}
		else
		{
			jQuery(event.data.contentsSelector+":not("+jQuery(this).attr("href")+")").css("display", "none");
			jQuery(jQuery(this).attr("href")).css({"opacity":0, "display":"block"}).animate({opacity:1}, options.fadeSpeed, function(){if(jQuery.browser.msie)this.style.removeAttribute("filter");/*IE bug fix*/});
		}
		if(!options.chooseAgain)
		{
			jQuery(this).unbind(options.event, openContent);
			jQuery(this).bind(options.event, function(event){event.preventDefault();});
		}
		if(event.data.options.kind=="stepByStep")
		{
			if(jQuery(this).next().attr("href")!=undefined && $.data(jQuery(this).next().get(0), "events")==null)//check the last step
			{
				jQuery(this).next().bind(options.event, {options:options, hoverBg:event.data.hoverBg, hoverColor:event.data.hoverColor, stepBg:event.data.stepBg, stepColor:event.data.stepColor, panelId:event.data.panelId, contentsSelector:event.data.contentsSelector}, openContent);
				jQuery(this).next().bind("mouseover", {options:options, hoverBg:event.data.hoverBg, hoverColor:event.data.hoverColor}, hoverIn);
				jQuery(this).next().bind("mouseout", {options:options, stepBg:event.data.stepBg, stepColor:event.data.stepColor}, hoverOut);
			}
		}
		if(options.nextPrevButtons)
		{
			//add nextPrevButtons
			var prevStep = null;
			var nextStep = null;
			if(jQuery(this).next().length && jQuery(this).next().attr("href")!=undefined)
				nextStep = jQuery(this).next().attr("href");
			if(jQuery(this).prev().attr("href")!=undefined && (options.chooseAgain || options.kind=="freeChoice"))
				prevStep = jQuery(this).prev().attr("href");
			if(jQuery(this).next().length && ($.data(jQuery(this).next().get(0), "events")==null || $.data(jQuery(this).next().get(0), "events")[options.event]==null))
				nextStep = null;
			if($.data(jQuery(this).prev().get(0), "events")==null || $.data(jQuery(this).prev().get(0), "events")[options.event]==null)
				prevStep = null;
			jQuery("#"+event.data.panelId+" .nextPrevButtons").remove();
			if(options.nextPrevButtonsPosition=="top")
				jQuery("#"+event.data.panelId).prepend("<div class='nextPrevButtons clearfix'><a class='button " + (prevStep!=null ? "activeButton-"+options.style:"inactiveButton") + "' title='" + options.prevButtonTitle + "' " + (prevStep!=null ? "xref='"+prevStep+"'":"xref='#'") + ">" + options.prevButtonTitle + "</a><a class='button " + (nextStep!=null ? "activeButton-"+options.style:"inactiveButton") + "' title='" + options.nextButtonTitle + "' " + (nextStep!=null ? "xref='"+nextStep+"'":"xref='#'") + ">" + options.nextButtonTitle + "</a></div>");
			else
				jQuery("#"+event.data.panelId).append("<div class='nextPrevButtons clearfix'><a class='button " + (prevStep!=null ? "activeButton-"+options.style:"inactiveButton") + "' title='" + options.prevButtonTitle + "' " + (prevStep!=null ? "xref='"+prevStep+"'":"xref='#'") + ">" + options.prevButtonTitle + "</a><a class='button " + (nextStep!=null ? "activeButton-"+options.style:"inactiveButton") + "' title='" + options.nextButtonTitle + "' " + (nextStep!=null ? "xref='"+nextStep+"'":"xref='#'") + ">" + options.nextButtonTitle + "</a></div>");
		}
		options.afterOpen(event, jQuery(this), contentElement, currentStepNumber);
	};
	function hoverIn(event)
	{
		var currentStepNumber = jQuery("#"+event.data.panelId).children("[href]").index(jQuery(this));
		if(false === event.data.options.onHoverIn(event, jQuery(this), currentStepNumber)) return;
		if(jQuery(this).get(0)===jQuery("#"+event.data.panelId).children("[href]").first().get(0))//firstStep
			jQuery("#"+event.data.panelId+" .boxStart").css("background-position", "-10px " + event.data.hoverBg.split(" ")[1]);
		jQuery(this).css({
			"background-position": parseInt(jQuery(this).backgroundPosition().split(" ")[0]) + "px " + event.data.hoverBg.split(" ")[1],
			"color":event.data.hoverColor,
			"cursor":"pointer"
		});
	};
	function hoverOut(event)
	{
		var currentStepNumber = jQuery("#"+event.data.panelId).children("[href]").index(jQuery(this));
		if(false===event.data.options.onHoverOut(event, jQuery(this), currentStepNumber)) return;
		if(jQuery(this).get(0)===jQuery("#"+event.data.panelId).children("[href]").first().get(0))//firstStep
			jQuery("#"+event.data.panelId+" .boxStart").css("background-position", "-10px " + event.data.stepBg.split(" ")[1]);
		jQuery(this).css({
			"background-position": parseInt(jQuery(this).backgroundPosition().split(" ")[0]) + "px " + event.data.stepBg.split(" ")[1],
			"color":event.data.stepColor
		});
	};

	jQuery.fn.backgroundPosition = function() {
		var p = jQuery(this).css("background-position");
		if(typeof(p) === "undefined") 
			return jQuery(this).css("background-position-x") + " " + jQuery(this).css("background-position-y");
		else 
			return p;
	  };

	jQuery.fn.processPanel = function(options){
		var defaults = {
			kind: "stepByStep",
			style: "green-blue",
			firstSelected: true,
			icons: true,
			imgFolder: "img",
			chooseAgain: true,
			nextPrevButtons: true,
			nextPrevButtonsPosition: "bottom",
			nextButtonTitle: "Next step",
			prevButtonTitle: "Previous step",
			fadeSpeed: 1000,
			event: "click",
			beforeLoad: function(){},
			onLoad: function(){},
			onOpen: function(){},
			afterOpen: function(){},
			onOpenPopup: function(){},
			onClosePopup: function(){},
			onHoverIn: function(){},
			onHoverOut: function(){}
		  };
		var options = $.extend(defaults, options);
		this.getOptions = function(){
			return options;
		};
		this.each(function(){
			options.beforeLoad();
			var panelId = jQuery(this).attr("id");
			var steps = jQuery(this).children("[href]").length;
			var contentsId = new Array();
			var contentsSelector = "";
			jQuery(this).children("[href]").each(function(){
				if(jQuery(this).attr("href").substr(0,1)!="#")
				{
					contentsId.push(jQuery(this).attr("href").split("#")[1]);
					contentsSelector+= "#"+jQuery(this).attr("href").split("#")[1]+",";
				}
				else
				{
					contentsId.push(jQuery(this).attr("href").substr(1));
					contentsSelector += jQuery(this).attr("href")+",";
				}
			});
			contentsSelector = contentsSelector.substr(0, contentsSelector.length-1);
			jQuery(this).children("[href]").first().before("<div class='boxStart boxStart-"+options.style+"'>&nbsp;</div>");
			var hoverBg, hoverColor, stepBg, stepColor, firstStep, secondStep;
			jQuery(contentsSelector).each(function(){
				jQuery(this).addClass("content");
			});
			jQuery(this).children("[href]").each(function(index){
				jQuery(this).addClass("step step-"+options.style+" step" + (index+1));
				if(index==0)
					jQuery(this).addClass("step" + (index+1) +"-"+options.style);
				if(!options.icons)
					jQuery(this).css("background-image", "url('" + options.imgFolder + "/sprite_without_icons.png')");
				if(index>0)
					jQuery(this).css("padding-left", parseInt(jQuery(this).css("padding-left"))+20+"px");
				var bgPos;
				if(index+1==steps)
					bgPos = -551+parseInt(jQuery(this).css("width"))-580+parseInt(jQuery(this).css("padding-left"))+"px " + jQuery(this).backgroundPosition().split(" ")[1];
				else
					bgPos = -551+parseInt(jQuery(this).css("width"))-10+parseInt(jQuery(this).css("padding-left"))+"px " + jQuery(this).backgroundPosition().split(" ")[1];
				jQuery(this).css({
					"z-index":steps-index,
					"background-position":bgPos
				});
				if(index==0)
				{
					firstStep = jQuery(this);
					hoverBg = jQuery(this).backgroundPosition();
					hoverColor = jQuery(this).css("color");
					jQuery(this).css("margin-left", "0px");
				}
				else if(index==1)
				{
					secondStep = jQuery(this);
					stepBg = jQuery(this).backgroundPosition();
					stepColor = jQuery(this).css("color");
				}
				if(jQuery(this).attr("label")!=undefined)
					jQuery(this).append("<span class='stepLabel"+(index+1==steps?"Last":"")+"'>" + jQuery(this).attr("label") + "</span>");
			});
			//if options.firstSelected==false change background of first step
			if(!options.firstSelected)
			{
				firstStep.css({
								"background-position":parseInt(firstStep.backgroundPosition().split(" ")[0]) + "px " + stepBg.split(" ")[1],
								"color":stepColor
							});
				jQuery("#"+panelId+" .boxStart").css("background-position", "-10px " + stepBg.split(" ")[1]);
			}
			else
				firstStep.css("cursor", "pointer");
			//bind hover event
			if(options.kind=="stepByStep")
			{
				if(!options.firstSelected || options.chooseAgain)
					firstStep.bind(options.event, {options:options, hoverBg:hoverBg, hoverColor:hoverColor, stepBg:stepBg, stepColor:stepColor, panelId:panelId, contentsSelector:contentsSelector}, openContent);
				else
					firstStep.bind(options.event, function(event){event.preventDefault();});
				if(!options.firstSelected)
				{
					firstStep.bind("mouseover", {options:options, hoverBg:hoverBg, hoverColor:hoverColor, panelId:panelId}, hoverIn);
					firstStep.bind("mouseout", {options:options, stepBg:stepBg, stepColor:stepColor, panelId:panelId}, hoverOut);
				}
				else
				{
					secondStep.bind("mouseover", {options:options, hoverBg:hoverBg, hoverColor:hoverColor}, hoverIn);
					secondStep.bind("mouseout", {options:options, stepBg:stepBg, stepColor:stepColor}, hoverOut);
					secondStep.bind(options.event, {options:options, hoverBg:hoverBg, hoverColor:hoverColor, stepBg:stepBg, stepColor:stepColor, panelId:panelId, contentsSelector:contentsSelector}, openContent);
				}
			}
			else
			{
				//bind hover and openContent to all steps
				jQuery(this).children("[href]").each(function(index){
					if(index>0 || !options.firstSelected)
					{
						jQuery(this).bind("mouseover", {options:options, hoverBg:hoverBg, hoverColor:hoverColor, panelId:panelId}, hoverIn);
						jQuery(this).bind("mouseout", {options:options, stepBg:stepBg, stepColor:stepColor, panelId:panelId}, hoverOut);
						jQuery(this).bind(options.event, {options:options, panelId:panelId, contentsSelector:contentsSelector}, openContent);
					}
					else if(!options.firstSelected || options.chooseAgain)
						jQuery(this).bind(options.event, {options:options, panelId:panelId, contentsSelector:contentsSelector}, openContent);
					else
						jQuery(this).bind(options.event, function(event){event.preventDefault();});
				});
			}
			jQuery(contentsSelector).each(function(index){
				if(!options.firstSelected || jQuery(this).attr("id")!=contentsId[0])
					jQuery(this).css("display", "none");
				else if(jQuery(this).attr("id")==contentsId[0] && options.nextPrevButtons)
				{
					//add nextPrevButtons
					if(options.nextPrevButtonsPosition=="top")
						jQuery("#"+panelId).prepend("<div class='nextPrevButtons clearfix'><a xref='#' class='button inactiveButton' title='" + options.prevButtonTitle + "'>" + options.prevButtonTitle + "</a><a xref='"+secondStep.attr("href")+"' class='button activeButton-"+options.style+"' title='" + options.nextButtonTitle + "'>" + options.nextButtonTitle + "</a></div>");
					else
						jQuery("#"+panelId).append("<div class='nextPrevButtons clearfix'><a xref='#' class='button inactiveButton' title='" + options.prevButtonTitle + "'>" + options.prevButtonTitle + "</a><a xref='"+secondStep.attr("href")+"' class='button activeButton-"+options.style+"' title='" + options.nextButtonTitle + "'>" + options.nextButtonTitle + "</a></div>");
				}
			});
			if(options.firstSelected && firstStep.attr("href")!=undefined && firstStep.attr("href").substr(0,1)!="#" && firstStep.attr("href")!="")
			{
				var url = firstStep.attr("href").split("#")[0];
				var contentId = firstStep.attr("href").split("#")[1];
				$.ajax({
					url: url,
					type: "post",
					async: false,
					success: function(data){
						jQuery("#"+contentId).html(data);
					}
				});
			}
			jQuery("#"+panelId+" .button[xref!='#']").live("click", function(event){
				event.preventDefault();
				jQuery("#"+panelId+" [href="+jQuery(this).attr("xref")+"]:not(.button)").trigger("mouseover").trigger("click");
			});
			jQuery("[href=#"+panelId+"-popup]").click(function(event){
				var panelId = jQuery(this).attr("href").substr(0, jQuery(this).attr("href").length-6);
				if(false===options.onOpenPopup(event, jQuery(this), jQuery(panelId))) return;
				event.preventDefault();
				jQuery(this).after("<div class='overlay'></div>");
				jQuery(panelId).css({"width":jQuery(panelId).width()+"px", "z-index":"101"});
				jQuery(panelId).css("top", ( jQuery(window).height() - jQuery(panelId).height() ) / 2+jQuery(window).scrollTop() + "px");
				jQuery(panelId).css("left", ( jQuery(window).width() - jQuery(panelId).width() ) / 2+jQuery(window).scrollLeft() + "px");
				jQuery(panelId).css("display", "block");
				jQuery(".overlay").css({"width":jQuery(window).width()+"px", "height":jQuery(document).height()+300+"px", "opacity":"0.7"});
				//opera lost width - fix
				var bgPos;
				var steps = jQuery(panelId).children("a").length;
				jQuery(panelId).children("[href]").each(function(index){
					if(index+1==steps)
						bgPos = -551+parseInt(jQuery(this).css("width"))-580+parseInt(jQuery(this).css("padding-left"))+"px " + jQuery(this).backgroundPosition().split(" ")[1];
					else
						bgPos = -551+parseInt(jQuery(this).css("width"))-10+parseInt(jQuery(this).css("padding-left"))+"px " + jQuery(this).backgroundPosition().split(" ")[1];
					jQuery(this).css("background-position", bgPos);
				});
				jQuery(panelId).css("opacity", "0");
				jQuery(panelId).animate({opacity: 1}, 500, function(){if(jQuery.browser.msie)this.style.removeAttribute("filter");/*IE bug fix*/});
				jQuery(".overlay").click(function(event){
					if(false===options.onClosePopup(event, jQuery(panelId))) return;
					jQuery(panelId).animate({opacity:0},500,function(){jQuery(this).css("display", "none")});
					jQuery(this).remove();
				});
				jQuery(window).resize(function(){
					jQuery(".overlay").css("height", jQuery(document).height()+"px");
				});
			});
			options.onLoad();
		});
		return this;
	};
	jQuery(".inactiveButton").live("click", function(event){
		event.preventDefault();
	});
})(jQuery);