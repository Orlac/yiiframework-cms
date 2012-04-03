/**
 * jQuery.inlineReport
 * Copyright (c) 2010 Vadim Gabriel - support(at)inlinereport(dot)com | http://inlinereport.com
 * Dual licensed under MIT and GPL.
 * Date: 5/22/2010
 *
 * @projectDescription Inline report bugs and typos in the document.
 * http://inlinereport.com
 * Works with jQuery +1.2.6. Tested on FF 2/3, IE 6/7/8, Opera 9.5/6, Safari 3, Chrome 1 on WinXP, Linux, Mac.
 *
 * @author Vadim Gabriel
 * @version 1.2
 *
 * Modal used here was created by http://www.sohtanaka.com/web-design/inline-modal-window-w-css-and-jquery/#comment-block
 *
 */
inlinereportdisplayed = false;
;(function($){
 $.fn.inlineReport = function(settings) {

	// Defaults in case one of them was not set
  	var defaults = {
	   			overlayBgColor: '#000',
				overlayOpacity:	0.8,
				keyToOpen: 'z',
				keyToClose: 'x',
				textAreaRows: 10,
				textAreaCols: 50,
				language: 'en',
				MissingData: 'Make sure the report field is completed.',
LongText: 'Text Selected Is Too Long!',
HeaderText: 'Report a typo or a bug',
BetterText: 'If you found a bug or a type in the selected text you can either report it or submit a better translation for it.',
ReportSentText: 'Report Sent.',
NoEmailText: 'No Email Specified.',
SubmitText: 'Submit',
CancelText: 'Cancel',
CloseText: 'Close Window',
CopyHeader: 'Powered By InlineReport',
CopyText: '<p>This tool allows your website users and visitors to repot bugs or typos in the articles or any page they see within the website they currently view.</p>' +
		  '<p>Once they have found the bug or type all they need to do is to select the text and press the CTRL + {command} combination keys and the {inlineReport} will be displayed.</p>' +
		  '<p>They can then submit the report or better yet submit a better translation or text for that text they have just selected and would like to report.</p>',

				callback: 'email',
				email: 'vadimg88@gmail.com',
				showButton: true,
				debug: false,
			CloseImageLink: 'inlinereport/close_pop.png'
		};
 		var options = $.extend(defaults, settings);
		// Internal variables do not edit below this line
		var internalCallbackUrl = 'http://inlinereport.com/report?';
		var websiteLink = 'http://inlinereport.com';
		var frameSrc = 'http://inlinereport.com/index/load?';
		   var selectedText = '';
		 	var isCtrl = false; 
			var divcontent = '';

		var maxLength = 255;

		// Run the main constructor
		_run();

		// bin the click event for the copyright div
		$('#inlinereport-copyright').live('click', function() {
			_log('copyright clicked');
		    _setInterface('copyright');
		});

		// iterate and reformat each matched element
		return this.each(function() {
		    var $this = $(this);
			if( !options.showButton )
			{
				return false;
			}
		    $this.html( _showButton() );
		});

		/**
		* Bin the events and wait...
		*/ 
		function _run()
		{
			$(document).keyup(function (e) 
			{ 
				// To ie
				if ( e == null ) 
				{
					keycode = event.keyCode;
				} 
				else 
				{
					// To Mozilla
					keycode = e.keyCode;
				}

				if(keycode == 17 || keycode == 91 || keycode == 224) 
				{
					isCtrl = false; 
				}

			}).keydown(function (e) 
			{ 	
				// To ie
				if ( e == null ) 
				{
					keycode = event.keyCode;
				} 
				else 
				{
					// To Mozilla
					keycode = e.keyCode;
				}

				_log('key: ' +  keycode );

				// Get the key in lower case form
				key = String.fromCharCode(keycode).toLowerCase();

				if(keycode == 17 || keycode == 91 || keycode == 224) 
				{
					isCtrl = true; 
				}

				// open
				if ( isCtrl && ( key == options.keyToOpen )  ) 
				{
					if( !inlinereportdisplayed )
					{
						_setInterface('report');
					}
					return false;
				}

				// close
				if ( isCtrl && ( key == options.keyToClose ) || ( keycode == 27 ) ) 
				{
					_finish();
					return false;
				}

			});
		};

		/**
		* Set the interface
		* @param can be either copyright or report
		*/
		function _setInterface( type )
		{
			//make sure something is selected
			var selectedText = getSelectedText();
			if( type != 'copyright' && selectedText == '' )
			{
				// Nothing so return
				return false;
			}

			var escapedText = escape(selectedText.toString());

			// Too large?
			if( escapedText.length > maxLength )
			{
				alert( options.LongText );
				return false;
			}

			_log('interface');

			_log('selected text type: ' + typeof(selectedText) );
			_log( selectedText.toString() );


			// if we are using the default implementation
			if( options.callback == 'email' )
			{
				_log('email: ' + options.email);

				//alert( 'Sorry, Currently the email callback is unavailable.' );
				//return false;

				if( !options.email )
				{
					alert( options.NoEmailText );
					return false;
				}

				$('#inlinereport-submit').live('click', function() {


					var params = 	'te=' +  encodeURIComponent($('#oldselectedtext').val()) +
									'&re=' + encodeURIComponent(escape($('#inlinereport-value').val())) +
									'&em=' +  encodeURIComponent(options.email) + 
									'&la=' +  encodeURIComponent(options.language) +
									'&ms=' +  encodeURIComponent(options.MissingData) +
									'&rs=' +  encodeURIComponent(options.ReportSentText) +
									'&ur=' +  encodeURIComponent(_getCurrentPage());

					var script_id = null;
					var script = document.createElement('script');
			        script.setAttribute('type', 'text/javascript');
			        script.setAttribute('src', internalCallbackUrl + params);
			        script.setAttribute('id', 'inlineReportScriptId');

			        script_id = document.getElementById('inlineReportScriptId');
			        if(script_id){
			            document.getElementsByTagName('head')[0].removeChild(script_id);
			        }

			        // Insert <script> into DOM
			        document.getElementsByTagName('head')[0].appendChild(script);

					return false;
				});
			}
			else
			{
				_log('callback');
				// Callback specified
				$('#inlinereport-submit').live('click', function() {
				 	options.callback.apply(this, [ $('#oldselectedtext').val(), $('#inlinereport-value').val(), _getCurrentPage() ]);
					_finish();
					return false;								
				});
			}

			// Make that we displayed the div
			inlinereportdisplayed = true;

			// hide some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
			$('embed, object, select').css({ 'visibility' : 'hidden' });

			// Based on the type of the 
			// element we clicked show the correct
			// div content
			if( type == 'copyright' )
			{
				divcontent = _copytext();
			}
			else
			{
				divcontent = _blocktext( escapedText );
			}

			$('body').append( divcontent );

			var popID = 'inlinereport-popup_block';
			var popWidth = $('#' + popID).width();

		    //Fade in the Popup and add close button
		    $('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="'+ options.CloseImageLink +'" id="inlinereport-btn_close" title="'+ options.CloseText +'" alt="'+ options.CloseText +'" /></a>');

		    //Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
		    var popMargTop = ($('#' + popID).height() + 80) / 2;
		    var popMargLeft = ($('#' + popID).width() + 80) / 2;

			//Apply Margin to Popup
			$('#' + popID).css({
			    'margin-top' : -popMargTop,
			    'margin-left' : -popMargLeft
			});

			//Fade in Background
			$('body').append('<div id="inlinereport-fade"></div>'); //Add the fade layer to bottom of the body tag.
			$('#inlinereport-fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies 

				//Close Popups and Fade Layer
			$('a.close, #inlinereport-fade, #inlinereport-close').live('click', function() { //When clicking on the close or fade layer...
			    $('#inlinereport-fade , #inlinereport-popup_block').fadeOut(function() {
					_finish();
				});
			    return false;
			});
		};

		/**
		* Replace html entities
		*/
		function escape(html) {
		  return html.
		    replace(/&/gmi, '&amp;').
		    replace(/"/gmi, '&quot;').
		    replace(/>/gmi, '&gt;').
		    replace(/</gmi, '&lt;');
		};

		/**
		* Return the block text
		*/
		function _blocktext(selectedText)
		{
			return '<div id="inlinereport-popup_block">' +
						 	'<h2>' + options.HeaderText + '</h2>' +
						 	'<div id="inlinereport-better">' + options.BetterText + '</div>' +
							// Hidden input to collect the selected text latter
							//'<input type="hidden" name="oldselectedtext" id="oldselectedtext" value="'+ selectedText +'" />' +
							'<textarea style="display:none;" id="oldselectedtext">'+ selectedText +'</textarea>' + 
						 	'<div id="inlinereport-textarea"><textarea id="inlinereport-value" rows="'+ options.textAreaRows +'" cols="'+ options.textAreaCols +'">'+ selectedText +'</textarea></div>' +
						 	'<div id="inlinereport-buttons"><input id="inlinereport-submit" type="submit" name="submit" value="'+ options.SubmitText +'" />&nbsp;<input id="inlinereport-close" type="button" value="'+ options.CancelText +'" /></div>' +
						 	'</div>';
		};

		/**
		* Return the copyright block text
		*/
		function _copytext()
		{
			// Replace tokens
			options.CopyText = options.CopyText.replace('{command}', options.keyToOpen);
			options.CopyText = options.CopyText.replace('{inlineReport}', _reporterLink());

			return '<div id="inlinereport-popup_block">' +
						 	'<h2>' + options.CopyHeader + '</h2>' +
						 	'<div id="inlinereport-better">' + options.CopyText + '</div>' +
						 	'</div>';
		};

		/**
		* Get the current link
		*/
		function _getCurrentPage()
		{
			return location.href;
		};

		/**
		* Construct the inlinereport link
		*/
		function _reporterLink()
		{
			return '<a href="'+ websiteLink +'" target="_blank">InlineReport</a>';
		};

		/**
		* Display the button/explanation text to the elements matched
		*/
		function _showButton()
		{
			return '<a href="#" id="inlinereport-copyright">InlineReport</a>';
		};

		/**
		* Return the selected text - cross browser
		*/
		function getSelectedText() 
		{
			var str = '';
		    if (window.getSelection) {
		        str = window.getSelection();
		    }
		    else if (document.selection) {
		        str = document.selection.createRange().text;
		    }

			_log('selected text');

		    return str;
		};

		/**
		* Log messages in debug mode
		*/
		function _log(msg)
		{
			if( !options.debug )
				return false;

			if( window.console )
				__log(msg);
				return true;

			return false;	
		};
		};

		})(jQuery);

		/**
		* Log messages in debug mode
		*/
		function __log(msg)
		{
		if( window.console )
			console.log(msg);
		};

		/**
		 * fade, hide and remove the div
		 * make sure we marked it as removed
		 */
		function _finish() {

			inlinereportdisplayed = false;

			$('#inlinereport-fade , #inlinereport-popup_block').fadeOut(function() {
				$('#inlinereport-popup_block').remove();
				$('#inlinereport-fade, a.close').remove();  //fade them both out
		    });

			// Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
			$('embed, object, select').css({ 'visibility' : 'visible' });
		};

		function _inlineReportError( err )
		{
			alert( err );
		};

		function _inlineReportSent( msg )
		{
			$('#inlinereport-popup_block').html( '<h2>' + msg + '</h2>' );

			// Wait before we hide it	
			setTimeout(function (){ _finish() }, 1500);
			return false;
		};