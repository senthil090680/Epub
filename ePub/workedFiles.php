


+++++++++
09052012
+++++++++

common.js
style.css
epub_activity.php
unziprearrange.php
header.php
activitylibrary.php
activitylibraryadd.php
activitylibraryview.php
activitylibraryview.php

+++++++++
10052012
+++++++++

header.php
style.css
epub_activity.php


+++++++++
11052012
+++++++++

activitylibraryadd.php
activitylibraryedit.php
activitylibraryview.php
header.php
index.php
common.js

In Images folder, btn_save.jpg to be added
Fonts folder to be created in the epubsource folder under OPS subfolder

+++++++++
12052012
+++++++++

common.js

+++++++++
14052012
+++++++++

In Images folder, preview.png to be added
common.js
style.css
epub_activity.php
epubeditview.php
epublibrary.php
activitylibraryview.php
unziprearrange.php





+++++++++
01062012
+++++++++


style.css
common.js
ajaxfileupload.js
epubeditview.js
epubeditview.php
gethtmlandactivity.php
header.php
epublibrary.php
converepub.php
InstantAddActivity.php
epub_activity.php
getselectedactivity.php
epubhtmldelete.php
updateactivitydetails.php


++++++
images
++++++

pop-close-small1.png
pop-close-small.png
previous.png
next.png


+++++++++
12062012
+++++++++

common.js
epubeditview.js
style.css
config.php
activitylibrarypaging.php
activitylibraryview.php
convertepub.php
epub_activity.php
epubeditview.php
epublibrary.php
epublibrarypaging.php
index.php
publish.php

Create New Folder activity_temp in the root folder

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
config.php
importnewcolm.php
importnewcolm1.php
deletecolumn.php
delcolajax.php
common.css

CREATE TABLE newseverncol (colId INT PRIMARY KEY AUTO_INCREMENT, 
colName VARCHAR(30) NOT NULL)

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+++++++++
13062012
+++++++++

Create New Folder preview_temp in the root folder
common.js
config.php

functions.php
header.php
epub_activity.php
epubeditview.php
epublibrary.php
publish.php
unziprearrange.php


add file -----------  ajaxmoveepubfile.php
add file -----------  ajaxremovetempfiles.php
add file -----------  ajaxmovehtmlfile.php


+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+++++++++
14062012
+++++++++

activitylibraryview.php
epublibrary.php
epublibrarypaging.php
getepubeditactivitydetails.php
style.css
common.js


ajax-loader.gif


        
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+++++++++
15062012
+++++++++

common.js
header.js
epub_activity.php
style.css


+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+++++++++
20062012
+++++++++

index.php
common.js
activitylibraryview.php
epub_activity.php
bring_preset_values.php


+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+++++++++
22062012
+++++++++

common.js
style.css
index.php
bring_preset_values.php
publish.php
epub_activity.php
epublibrary.php
epublibrarypaging.php
activitylibraryview.php
getepubeditactivitydetails.php




//STORED PROCEDURE


//The IN and OUT LENGTH SIZE SHOULD BE VERY LESS

DELIMITER $$
DROP PROCEDURE IF EXISTS epub_publish.getSettingId $$
CREATE PROCEDURE epub_publish.getSettingId(
IN setids INT,
OUT setid INT,OUT prename VARCHAR(200),OUT preset INT,
OUT epubver VARCHAR(50),OUT output VARCHAR(50),
OUT booktit VARCHAR(200),OUT coverim VARCHAR(200),
OUT reso INT,OUT supportd VARCHAR(50),OUT fixedlay VARCHAR(50),
OUT openspr VARCHAR(50),OUT interac VARCHAR(50),
OUT specifi VARCHAR(50),OUT fontname VARCHAR(50),
OUT orilock VARCHAR(50),OUT epubfol VARCHAR(250),
OUT pdffile VARCHAR(200), OUT pdfpage INT,
OUT activity LONGBLOB, OUT credate DATETIME
)
BEGIN
SELECT settingId,presetName,presetSet,epubVersion,outputType,
bookTitle,coverImage,resol,supportDevice,fixedLay,
openSpread,interActive,specificFont,fontName,oriLock,epubFolder,
pdfFile,pdfPages,activityFolder,createdDate INTO setid,prename,preset,
epubver,output,booktit,coverim,reso,supportd,fixedlay,
openspr,interac,specifi,fontname,orilock,epubfol,
pdffile,pdfpage,activity,credate FROM epubsetting WHERE settingId = 
setids;
END $$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS epub_publish.activity_desc $$
CREATE TRIGGER activity_desc BEFORE INSERT ON activitylibrary FOR EACH ROW
BEGIN
IF (new.activityDesc = '') THEN
SET new.activityDesc= '-';
END IF;
END $$
DELIMITER ;







