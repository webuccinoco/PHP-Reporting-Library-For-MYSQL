<?php
if(file_exists("../sre_reports")){
    copy("../sre_reports","../../../../sre_reports");
    echo "sre_reports directory published successfully";
}

