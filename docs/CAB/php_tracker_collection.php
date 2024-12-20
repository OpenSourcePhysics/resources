<?php

  # This script returns an Open Source Physics XML file that defines the current directory as a digital library collection
  # and certain files and subdirectories it contains as library resources and sub-collections, respectively.
  # 
  # Files that are identified as resources include tracker files (.trk, .zip), video files and html files unless their name
  # starts with "_" or "index", or they have the same name as a tracker or video file (see below). 
  # All subdirectories with resources are identified as collections unless their name starts with "_".
  #
  # You can specify which file types to include in the collections, as well as whether to include subdirectories, by 
  # setting the "$includeXXX" variables starting at line 154 to true or false.
  # 
  # You can provide a name and descriptive html page for this collection or any sub-collection by including an html file
  # with the same name as this php file with an added "_info" (e.g., "my_collection_info.html" to describe collections 
  # generated by "my_collection.php" or "my_collection.html") in the directory or subdirectory.
  # The name of the collection is set to the title of the descriptive html page, if any.
  # 
  # You can provide a descriptive html page for any tracker or video resource by including an html file with the same
  # name as the resource in the same directory (e.g., "my_experiment.html" to describe "my_experiment.trk").
  
  # compare end of $FullStr with $EndStr
  function endsWith($FullStr, $EndStr) {
    $StrLen = strlen($EndStr); // get the length of the end string
    $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen); // get the same length substring from end of FullStr
    return $FullStrEnd == $EndStr; // compare
  }
  
  # gets the title from $htmltext (ie string between "<title>" and "</title>")
  function getTitle($htmltext){
	$x = explode('<title>', $htmltext);
	$y = explode('</title>', $x[1]);
	return $y[0];
  }

  # write a LibraryResource for a TRK file
  function writeTRKRecord($name, $dir, $tab, $index, $collectionXML) { 

	$indent = $tab . "  ";
	# write array index and class name
	$collectionXML = $collectionXML . $tab . '<property name="[' . $index . ']" type="object">' . "\n";
  	$collectionXML = $collectionXML . $tab . '<object class="org.opensourcephysics.tools.LibraryResource">' . "\n"; 
  	$collectionXML = $collectionXML . $indent . '<property name="name" type="string">' . $name . '</property>' . "\n"; 
  	if (file_exists($dir . "/" . $name . ".html")) {
		$collectionXML = $collectionXML . $indent . '<property name="html_path" type="string">' . $name . '.html</property>' . "\n";
	}
	
	# write type and target
	$collectionXML = $collectionXML . $indent . '<property name="type" type="string">' . "Tracker" . '</property>' . "\n";  
	$collectionXML = $collectionXML . $indent . '<property name="target" type="string">' . $name . '.trk</property>' . "\n";  
	$collectionXML = $collectionXML . $tab . '</object>' . "\n"; 
	$collectionXML = $collectionXML . $tab . '</property>' . "\n";
	return $collectionXML;
  }

  # write a LibraryResource for a video file
  function writeVideoRecord($filename, $dir, $tab, $index, $collectionXML) { 

	$parts = explode('.', $filename);
    $name = $parts[0];    
	$indent = $tab . "  ";
	
	# write array index and class name
	$collectionXML = $collectionXML . $tab . '<property name="[' . $index . ']" type="object">' . "\n";
  	$collectionXML = $collectionXML . $tab . '<object class="org.opensourcephysics.tools.LibraryResource">' . "\n"; 
  	$collectionXML = $collectionXML . $indent . '<property name="name" type="string">' . $name . '</property>' . "\n"; 
  	if (file_exists($dir . "/" . $name . ".html")) {
		$collectionXML = $collectionXML . $indent . '<property name="html_path" type="string">' . $name . '.html</property>' . "\n";
	}
	
	# write type and target
	$collectionXML = $collectionXML . $indent . '<property name="type" type="string">' . "Video" . '</property>' . "\n";  
	$collectionXML = $collectionXML . $indent . '<property name="target" type="string">' . $filename . '</property>' . "\n";  
	$collectionXML = $collectionXML . $tab . '</object>' . "\n"; 
	$collectionXML = $collectionXML . $tab . '</property>' . "\n";
	return $collectionXML;
  }

  # write a LibraryResource for an HTML file
  function writeHTMLRecord($name, $dir, $tab, $index, $collectionXML) { 

	$indent = $tab . "  ";
	# write array index and class name
	$collectionXML = $collectionXML . $tab . '<property name="[' . $index . ']" type="object">' . "\n";
  	$collectionXML = $collectionXML . $tab . '<object class="org.opensourcephysics.tools.LibraryResource">' . "\n"; 
  	$collectionXML = $collectionXML . $indent . '<property name="name" type="string">' . $name . '</property>' . "\n"; 
  	$collectionXML = $collectionXML . $indent . '<property name="html_path" type="string">' . $name . '.html</property>' . "\n";
	
	# write type and target
	$collectionXML = $collectionXML . $indent . '<property name="type" type="string">' . "HTML" . '</property>' . "\n";  
	$collectionXML = $collectionXML . $indent . '<property name="target" type="string">' . $name . '.html</property>' . "\n";  
	$collectionXML = $collectionXML . $tab . '</object>' . "\n"; 
	$collectionXML = $collectionXML . $tab . '</property>' . "\n";
	return $collectionXML;
  }

  # write a LibraryResource for a ZIP file containing Tracker resources
  function writeZIPRecord($name, $dir, $tab, $index, $collectionXML) { 

	$indent = $tab . "  ";
	# write array index and class name
	$collectionXML = $collectionXML . $tab . '<property name="[' . $index . ']" type="object">' . "\n";
  	$collectionXML = $collectionXML . $tab . '<object class="org.opensourcephysics.tools.LibraryResource">' . "\n"; 
  	$collectionXML = $collectionXML . $indent . '<property name="name" type="string">' . $name . '</property>' . "\n"; 
  	if (file_exists($dir . "/" . $name . ".html")) {
		$collectionXML = $collectionXML . $indent . '<property name="html_path" type="string">' . $name . '.html</property>' . "\n";
	}
	# write type and target
	$collectionXML = $collectionXML . $indent . '<property name="type" type="string">' . "Tracker" . '</property>' . "\n";  
	$collectionXML = $collectionXML . $indent . '<property name="target" type="string">' . $name . '.zip</property>' . "\n";  
	$collectionXML = $collectionXML . $tab . '</object>' . "\n"; 
	$collectionXML = $collectionXML . $tab . '</property>' . "\n";
	return $collectionXML;
  }

  # write a LibraryCollection
  function writeCollection($baseAddress, $dir, $tab) { 
	$indent = $tab . "  ";
    
    # get the name of this script file
	$currentfile = $_SERVER["PHP_SELF"];
	$parts = explode('/', $currentfile);
	$currentfile = $parts[count($parts) - 1];
	$parts = explode('.', $currentfile);
	$currentfilename = $parts[0];
  	$collectioninfofile = $currentfilename . "_info.html";
    
    # look for collection name
    $catname = '';	
	if (file_exists($dir . "/" . $collectioninfofile)) {
		$catname = getTitle(file_get_contents($dir . "/" . $collectioninfofile));
	}
	if ($catname=='' && $dir!='.') {
		$parts = explode('/', $dir);
		$catname = $parts[count($parts) - 1];
	}
	
	# create collection xml string
	$collectionXML = '';
	
	# write class baseAddress, name and info, if any
  	$collectionXML = $collectionXML . $tab . '<object class="org.opensourcephysics.tools.LibraryCollection">' . "\n"; 
    if ($catname!='') $collectionXML = $collectionXML . $indent . '<property name="name" type="string">' . $catname . '</property>' . "\n";
  	$collectionXML = $collectionXML . $indent . '<property name="base_path" type="string">' . $baseAddress . '</property>' . "\n";     
    if (file_exists($dir . "/" . $collectioninfofile)) $collectionXML = $collectionXML . $indent . '<property name="html_path" type="string">' . $collectioninfofile . '</property>' . "\n";
	
	# get the directory listing
    $dh = opendir($dir);
	while (false !== ($filename = readdir($dh))) $files[] = $filename;
	sort($files);
	
	$index = 0;
	$collectionXML = $collectionXML . $indent . '<property name="resources" type="array" class="[Lorg.opensourcephysics.tools.LibraryResource;">' . "\n"; 
    
	# specify which file types to include
	$includeHTML = true;
	$includeTRK = true;
	$includeZIP = true;
	$includeVideos = true;
	$includeSubdirectories = true;
	
    # write html resources
	if ($includeHTML) foreach($files as $filename) {
	  if ($filename=="." || $filename=="..") continue;
	  if ($filename[0]=="_") continue; // ignore files starting with '_'
	  if ($filename==$currentfile) continue; // ignore this script file
	  if ($filename==$collectioninfofile) continue; // ignore info file
	  if (substr($filename,0,5)=="index") continue; // ignore index files
	  
	  $path = $dir . "/" . $filename;
	  if (!is_dir($path) && endsWith($filename,".html")) {
		$name = substr($filename,0,strlen($filename)-5);
		# look for trk or video file with matching name, ignore html if found
		$testfile = $name . ".trk";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".zip";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".mov";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".avi";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".mp4";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".wmv";
		if (file_exists($dir . "/" . $testfile)) continue;
		$testfile = $name . ".flv";
		if (file_exists($dir . "/" . $testfile)) continue;
		$collectionXML = writeHTMLRecord($name, $dir, $indent . "  ", $index++, $collectionXML);
	  }
	}

	# write trk resources
	if ($includeTRK) foreach($files as $filename) {
	  if ($filename=="." || $filename=="..") continue;
	  if ($filename[0]=="_") continue; // ignore files starting with '_'
	  
	  $path = $dir . "/" . $filename;
	  if (!is_dir($path) && endsWith($filename,".trk")) {
		$name = substr($filename,0,strlen($filename)-4);
		$collectionXML = writeTRKRecord($name, $dir, $indent . "  ", $index++, $collectionXML);
	  }
	}
    
	# write zip resources
	if ($includeZIP) foreach($files as $filename) {
	  if ($filename=="." || $filename=="..") continue;
	  if ($filename[0]=="_") continue; // ignore files starting with '_'
	  
	  $path = $dir . "/" . $filename;
	  if (!is_dir($path) && endsWith($filename,".zip")) {
		$name = substr($filename,0,strlen($filename)-4);
		$collectionXML = writeZIPRecord($name, $dir, $indent . "  ", $index++, $collectionXML);
	  }
	}
    
	# write video resources
	if ($includeVideos) foreach($files as $filename) {
	  if ($filename=="." || $filename=="..") continue;
	  if ($filename[0]=="_") continue; // ignore files starting with '_'
	  
	  $path = $dir . "/" . $filename;
	  if (!is_dir($path)) {
		if (endsWith($filename,".mov") || endsWith($filename,".avi") || endsWith($filename,".wmv") || endsWith($filename,".mp4") || endsWith($filename,".flv")) {
		  $collectionXML = writeVideoRecord($filename, $dir, $indent . "  ", $index++, $collectionXML);
		}
	  }
	  
	}
    
	# write collections (subdirectories)
	if ($includeSubdirectories) foreach($files as $filename) {
	  if ($filename=="." || $filename=="..") continue;
	  if ($filename[0]=="_") continue; // ignore files starting with '_'
	  
	  $path = $dir . "/" . $filename;
	  if (is_dir($path)) {
		$nextCollection = writeCollection ($baseAddress . $filename . "/", $path, $indent . "  ");
				
		# add entry for the directory only if not empty
		if ($nextCollection!='') {
			$collectionXML = $collectionXML .  $indent . '  <property name="[' . $index++ . ']" type="object">' . "\n";
			$collectionXML = $collectionXML . $nextCollection;
			$collectionXML = $collectionXML .  $indent . '  </property>' . "\n";
		}
	  }
	}
	
	$elementZero = "[0]";
	$pos = strpos($collectionXML,$elementZero);

	if($pos===false && $tab!="") {
	 	return '';
	}
	
	$collectionXML = $collectionXML .  $indent . '</property>' . "\n";
	 
	$collectionXML = $collectionXML .  $tab . '</object>' . "\n"; 
	
	return $collectionXML;
	
  }

  $baseAddress =  "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  $baseAddress = substr($baseAddress,0,strlen($baseAddress)-strlen(strrchr($baseAddress, "/"))+1);
  
  print '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  print writeCollection ($baseAddress,".","");
	
?>
