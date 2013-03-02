<!DOCTYPE html> 
<html manifest="manifest.php">
	<meta charset="utf-8" />
	<title>iPhone contact download demo app</title>
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="apple-touch-icon-precomposed" href="touch-icon-iphone.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="touch-icon-ipad.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="touch-icon-iphone4.png" />
	<link rel="apple-touch-startup-image" href="startup.png">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
	<script src="offline.js"></script>
	</head> 
	
    <body>
        <div data-role="page">
            <div data-role="header">
                <h2>iPhone Demo</h2>
            </div>
            <div data-role="content">
				<ul data-role="listview" data-inset="true">
					<li data-icon="false"><a rel="external" href="download.php">Download Contact to ANY phone</a></li>
					<li data-icon="false"><a rel="external" href="vcard.php">* Download .vcf direct</a></li>
					<li data-icon="false"><a rel="external" href="vcal.php">* Download .ics to iPhone</a></li>
					<li data-icon="false"><a rel="external" href="tel:+440123456789">Call (demo only)</a></li>
					<li data-icon="false"><a rel="external" href="mailto:iphone@mobicontact.info">Email</a></li>
				</ul>
				<p>Place a VCARD inside a VCALENDAR event to allow Mobile Safari to import it direct to your Contacts.</p>
            </div>
            <div data-role="footer">
                <h2>Sponsored by MobiContact.info</h2>
            </div>
        </div>
    </body>
</html>
