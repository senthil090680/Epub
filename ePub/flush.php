<?php
header("Content-Type: text/html; charset=utf-8");
function flush_buffers(){
    ob_end_flush();
    ob_flush();
    flush();
    ob_start();
}

ob_start();
flush_buffers();
echo "starting...<br/>\n";
for($i = 0; $i < 5; $i++) {
    flush_buffers();
    print   "$i<br/>\n";
    flush_buffers();
    sleep(2);
}

flush_buffers();

print "DONE!<br/>\n";
?>
