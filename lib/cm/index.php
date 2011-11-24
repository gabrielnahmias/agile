<?php

include "config.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Glide - [filename/dir]</title>

<link href="./css/styles.css" media="screen" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./lib/ext/resources/css/ext-all.css"/>

<script language="javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>

<script src="./lib/cm/lib/codemirror.js"></script>
<link rel="stylesheet" href="./lib/cm/lib/codemirror.css">
<script src="./lib/cm/mode/javascript/javascript.js"></script>
<link rel="stylesheet" href="./lib/cm/theme/cobalt.css">

<script language="javascript">

var intFiles = 0;

var strID = "editor";

var arrEditors = new Array();

function addEditor() {
	
	var newFrame = document.createElement("iframe");
	
	newFrame.id = strID + intFiles;
	newFrame.src = "editor.php";
	
	arrEditors[intFiles] = document.body.appendChild(newFrame);
	
	intFiles++;

}

function getEdtVal(intNum) {
	
	var elFoo = window.frames[strID+intNum].document.getElementsByClassName('CodeMirror');
	elFoo = elFoo[0];
	
	console.log(elFoo);
	
}

</script>

</head>

<body>

<input type="button" onclick="addEditor()" value="Add" />
<input type="button" onclick="getEdtVal(0)" value="Get" />

</body>

</html>