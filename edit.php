<?php

include "config.php";
include "../browser.php";

$br = new Browser;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=NAME?> - [filename/dir]</title>

<link href="./css/styles.css" media="screen" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./lib/ext/resources/css/ext-all.css" />

<script type="text/javascript" src="./js/functions.js"></script>

<script language="javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript" src="./js/jquery.hotkeys.js"></script>

<script type="text/javascript" src="./lib/ext/ext-all-debug.js"></script>
<script type="text/javascript" src="designer.js"></script>

<script language="javascript" type="text/javascript" src="<?=PATH_LIB?>/edit/edit_area_full.js"></script>

<script language="javascript" type="text/javascript">

/*************** UI ***************/


</script>

<script language="javascript">

/*************** EDITOR ***************/

var intFiles = 0;

var strID = "editor";
var strFrame = "#frame_editor";

editAreaLoader.init( {

 	id: strID,
	
	allow_resize: "no",
	allow_toggle: false,
	autocompletion: true,
	fullscreen: true,
	is_multi_files: true,
	language: "en",
	max_undo: 1000,
	plugins: "autocompletion",
	start_highlight: true,
	syntax: "php",
	toolbar: "search, go_to_line, undo, redo, change_smooth_selection, reset_highlight, highlight, word_wrap, help, save, load, new_document, syntax_selection, select_font",
	
	EA_file_close_callback: "checkFileStatus",
	EA_file_switch_on_callback: "fileSwitch",
	EA_load_callback: "editAreaLoaded"
	
} );

function editAreaLoaded() {
	
	// This is one of the most pivetal functions, as it is where
	// any initial handling of the iFrame is possible.
	
	var jqFrame = $(strFrame).contents();
	var jqTxt = jqFrame.find("textarea");
	
//	var elmFrame = document.getElementById(strFrame);
//	var elmFrame = elmFrame.contentDocument || elmFrame.contentWindow.document;
	
	initText();
	
	jqFrame.click( function() {
		
		// Hide any menu that's open if we click (or focus) inside the editor.
		
		if ( $(".x-menu").is(":visible") ) {
			
			$(".x-menu").hide();
			$(".x-btn").removeClass('x-btn-default-toolbar-small-menu-active x-btn-default-toolbar-small-pressed');
			
		}
		
	} ).focus( function() { jqFrame.click() } );
	
	// This prevents certain special keys from altering the edited status of the current file.
	
	jqTxt.bind("keydown", "Alt Ctrl Meta Shift F1 F2 F3 F4 F5 F6 F7 F8 F9 F10 F11 F12 numlock scroll pause capslock esc pageup pagedown end home left up right down insert", function(e) {
		
		objInfo = editAreaLoader.getCurrentFile(strID);
		
		if (!objInfo.edited)
			editAreaLoader.setFileEditedMode(strID, objInfo.id, false);
		
	} );
	
	// Shortcuts for triggering events.  I have to triple up on them all considering
	// events that trigger for the document do not inside the iFrame.
	
	$(document).bind("keydown", "Alt+X", function(e) { closeCurrentTab(); e.preventDefault(); } );
	jqFrame.bind("keydown", "Alt+X", function(e) { closeCurrentTab(); e.preventDefault(); } );
	jqTxt.bind("keydown", "Alt+X", function(e) { closeCurrentTab(); e.preventDefault(); } );

}

function checkFileStatus(objInfo) {
	
	if (objInfo.edited) {
		
		strFileID = objInfo.id;
		
		var boolSave = false;
		var boolClose = true;
		
		Ext.Msg.show( {
			 
			 fn: function(strBtnID) {
				
				if (strBtnID == "yes")
					boolSave = true;
				else if (strBtnID == "no")
					boolSave = false;
				else
					boolClose = false;
				
				if (boolSave) {
					
					console.log("Save")
					
				}
				
				if (boolClose) {
					
					// The following call prevents a recursive loop when calling closeFile()
					// again.
					
					editAreaLoader.setFileEditedMode(strID, strFileID, false);
					
					editAreaLoader.closeFile(strID, strFileID);
				
				}
				
			},
			 
			 modal: true,
			 
			 title: '<?=NAME?>',
			 
			 msg: 'Save changes to ' + objInfo.title + '?',
			 
			 buttons: Ext.Msg.YESNOCANCEL,
			 
			 icon: Ext.Msg.QUESTION
			 
		} );
		
	} else
		return true;
	
	return false;
	
}

function closeCurrentTab() {
	
	strCurrFileID = editAreaLoader.getCurrentFile(strID);
	strCurrFileID = strCurrFileID.id;
	
	editAreaLoader.closeFile(strID, strCurrFileID);
	
}

function fileSwitch(objInfo) {
	
	// This prevents some bizarre thing where when you close a file,
	// the next one tabbed seems edited.
	
	if (!objInfo.edited)
			editAreaLoader.setFileEditedMode(strID, objInfo.id, false);
	
	// Change menu items to reflect current file's settings.
	
	var intPlace = 0;
	
	var strSyntax = objInfo.syntax;
	
	var objSyntaxList = Ext.getCmp("menu_syntax").items.items;
	
	for	(var intItem in objSyntaxList) {
		
		// I really don't NEED to be changing the actual value of checked for the menu items;
		// however, I feel that for the sake of completeness, I will.
		
		objSyntaxList[intItem].checked = false;
		
//		console.log(intPlace);
		
//		console.log( $("#menu_syntax .x-menu-item").eq(intPlace) );
		
		if (objSyntaxList[intItem].file == strSyntax) {
			
			objSyntaxList[intItem].checked = true;
			
			$("#menu_syntax .x-menu-item").filter(":not(:eq(" + intPlace + "))").addClass("x-menu-item-unchecked");
			$("#menu_syntax .x-menu-item").eq(intPlace).removeClass("x-menu-item-unchecked").addClass("x-menu-item-checked");
			
			return;
			
		}
		
		intPlace++;
		
	}
	
}

function initText() {
	
	openFile();
	openFile();
	openFile();
	openFile();
	
}

function openFile(strFile) {
	
	// later, openFile will actually use AJAX to open local files
	
	strFile = "";
	
	var arrText = new Array();
	
	arrText[0] = "<html>\r\n<head>\r\n<title>Untitled Document</title>\r\n<head>\r\n<body>\r\n\r\n<?="<?php"?> print \"Hello, earth!\"; <?="?>"?>\r\n</body>\r\n<html>";
	arrText[1] = "<?="<?php"?>\r\n\r\nfor ($i = 0; $i < 10; $i++) {\r\n\r\n\tprint \"Been around $i times!\\r\\n<br />\";\r\n\r\n}\r\n\r\n<?="?>"?>";
	arrText[2] = "<html>\r\n<head>\r\n<title>Untitled Document</title>\r\n<head>\r\n<body>\r\n\r\n<?="<?php"?> print \"Hello, earth!\"; <?="?>"?>\r\n</body>\r\n<html>";
	arrText[3] = "<?="<?php"?>\r\n\r\nfor ($i = 0; $i < 10; $i++) {\r\n\r\n\tprint \"Been around $i times!\\r\\n<br />\";\r\n\r\n}\r\n\r\n<?="?>"?>";
	
	var objFile = { id: "file" + intFiles, text: arrText[intFiles], syntax: 'php', title: 'Untitled-' + intFiles };
	
	editAreaLoader.openFile(strID, objFile); 
	
	intFiles++;
	
}

Ext.onReady( function() {
	
	
	
} );

$(document).load( function() {
	
	
	
} );

</script>

</head>

<body>

<div id="edit_area_help" class="x-hidden">
    <div class="x-window-header">Help</div>
    <div><h2>Agile [__EA_VERSION__]</h2><br /> <center><h3><u>{$shortcuts}</u></h3></center><table cellspacing=\"5\" style=\"margin-top: 10px\"><tr><td>{$tab}:</td> <td>{$add_tab}</td></tr><tr><td>{$shift} + {$tab}:</td><td>{$remove_tab}</td></tr><tr><td>{$ctrl} + F:</td><td>{$search_command}</td></tr><tr><td>{$ctrl} + R:</td><td>{$replace_command}</td></tr><tr><td>{$ctrl} + H:</td><td>{$highlight}</td></tr><tr><td>{$ctrl} + G:</td><td>{$go_to_line}</td></tr><tr><td>{$ctrl} + Z:</td><td>{$undo}</td></tr><tr><td>{$ctrl} + Y:</td><td>{$redo}</td></tr><tr><td>{$ctrl} + E:</td><td>{$help}</td></tr><tr><td>{$ctrl} + Q {$esc}:</td><td>{$close_popup}</td></tr><tr><td>{$accesskey} E:</td><td>{$toggle}</td></tr></table> <br /> <div class='copyright'>&copy; Gabriel Nahmias 2010 - 2011</div> </div>
</div>

<div id='area_search_replace' class='editarea_popup x-hidden'>
    <div class="x-window-header">Search</div>
    <table cellspacing='2' cellpadding='0' style='width: 100%'> <tr> <td selec='none'>{$search}</td> <td><input type='text' id='area_search' /></td> <td id='close_area_search_replace'> <a onclick='Javascript:editArea.execCommand(\"hidden_search\")'><img selec='none' src='lib/edit/images/close.png' alt='{$close_popup}' title='{$close_popup}' /></a><br /> </tr><tr> <td selec='none'>{$replace}</td> <td><input type='text' id='area_replace' /></td> <td><img id='move_area_search_replace' onmousedown='return parent.start_move_element(event,\"area_search_replace\", parent.frames[\"frame_\"+editArea.id]);'  src='lib/edit/images/move.gif' alt='{$move_popup}' title='{$move_popup}' /></td> </tr> </table> <div class='button'> <input type='checkbox' id='area_search_match_case' /><label for='area_search_match_case' selec='none'>{$match_case}</label> <input type='checkbox' id='area_search_reg_exp' /><label for='area_search_reg_exp' selec='none'>{$reg_exp}</label> <br /> <a onclick='Javascript:editArea.execCommand(\"area_search\")' selec='none'>{$find_next}</a> <a onclick='Javascript:editArea.execCommand(\"area_replace\")' selec='none'>{$replace}</a> <a onclick='Javascript:editArea.execCommand(\"area_replace_all\")' selec='none'>{$replace_all}</a><br /> </div> <div id='area_search_msg' selec='none'></div> </div>
</div>

</body>

</html>