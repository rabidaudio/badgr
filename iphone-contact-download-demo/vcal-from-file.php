<?php
  # Send correct headers      
  header("Content-type: text/x-vcalendar; charset=utf-8"); 
                    // Alternatively: application/octet-stream
                    // Depending on the desired browser behaviour
                    // Be sure to test thoroughly cross-browser

  header("Content-Disposition: attachment; filename=\"iphonecontact.ics\";");
  # Output file contents 
  echo file_get_contents("iphonecontact.ics");
 ?>