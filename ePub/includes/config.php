<?php
	ini_set("display_errors","false");
	ini_set("max_execution_time","18000");

	/*ini_set('upload_max_filesize','5G');   You cannot set upload_max_filesize and post_max_size using ini_set
	ini_set('post_max_size','30M');*/

	//echo ini_get('upload_max_filesize');
	//echo ini_get('display_errors');
	//echo ini_get('post_max_size');
	
	define('ROOT_DIRECTORY', 'ePub');
	//define('ROOT_DIRECTORY', 'epubreader');

	//LOCAL DATABASE CONNECTION
	define('DBNAME', 'epub_publish');                                       //DATABASE NAME
	define('DBUSERNAME', 'root');                                           //DATABASE USERNAME
	define('DBPASS', '');                                                   //DATABASE PASSWORD
	define('SERVERNAME', 'localhost');                                      //SERVER NAME
	
	//LIVE DATABASE CONNECTION
	/*define('DBNAME', 'epub_publish');                                     //DATABASE NAME
	define('DBUSERNAME', 'epub');						//DATABASE USERNAME
	define('DBPASS', 'Epub123');						//DATABASE PASSWORD
	define('SERVERNAME', '74.205.62.215:3307');					//SERVER NAME*/

	define('PRESET_TABLE', 'preset_settings');				//PRESET TABLE NAME
	define('EPUB_SET', 'epubsetting');					//EPUBSETTING TABLE NAME
        define('ACTIVITY_LIBRARY', 'activitylibrary');                          //ACTIVITY LIBRARY TABLE NAME
	
	$absolutepath = $_SERVER['DOCUMENT_ROOT'].ROOT_DIRECTORY;
	$relativepath = 'http://'.$_SERVER['HTTP_HOST'].'/'.ROOT_DIRECTORY;
	
	define('ABSOLUTE_PATH', $absolutepath);
	define('RELATIVE_PATH', $relativepath);
	
	define('DEFAULT_RESOLUTION', '890x640');
	define('DEFAULT_DESITY', '72');
	define('DEFAULT_DPI', '100');
	define('PDF_FOLDER', 'pdf');
	define('EPUB_FOLDER', 'epub');
        define('ACTIVITY_FOLDER', 'activity');
        define('ACTIVITY_TEMP','activity_temp');
        define('PAGE_PER_COUNT',5);
        define('PREVIEW_TEMP','preview_temp');
        //define('ACTIVITY_FOR', 'activityforepub');
	define('FONT_FOLDER', 'fonts');
	define('META_FILE', 'META-INF/com.apple.ibooks.display-options.xml');
	define('EPUB_SOURCE_FOLDER', 'epubsource');
?>