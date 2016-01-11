<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body onload="window.print()">
	<?php if (count($tickets)): ?>
	    <?php foreach ($tickets as $ticket): ?>
	    	<div class="one-page">
				<table class="wrapper">
			        <tr>
						<td class="leftCol">
							<div class="controlInfo">
								<!-- <div class="barcodeText"><?php #echo $ticket->barcode ?></div> -->
								<?php if (isset($ticket->print_name) && mb_strlen($ticket->print_name, 'UTF-8') > 0): ?>
									<div class="title"><?php echo $ticket->print_name ?></div>
									<div class="subTitle"><?php echo $ticket->print_name_small ?></div>
								<?php else: ?>
									<div class="title2"><?php echo $ticket->event_name ?></div>
								<?php endif ?>
								<div class="date"><?php echo $ticket->event_date ?></div>
								<div class="infPlace">
									<?php echo $ticket->place_string ?>
								</div>
								<?php if ($ticket->price > 1): ?>
									<div class="infPlace">Цена: <?php echo $ticket->price ?>  грн.</div>
								<?php else: ?>
									<div class="infPlace">Пригласительный</div>
								<?php endif; ?>
							</div>
						</td>
						<td class="centerCol">
							<div class="contentCenter">
								<?php if (isset($ticket->print_name) && mb_strlen($ticket->print_name, 'UTF-8') > 0): ?>
									<div class="title"><?php echo $ticket->print_name ?></div>
									<div class="subTitle"><?php echo $ticket->print_name_small ?></div>
								<?php else: ?>
									<div class="title2"><?php echo $ticket->event_name ?></div>
								<?php endif ?>
								<div class="nameBuild">Место проведения: <strong><?php echo $ticket->event_place ?></strong></div>
								<div class="nameBuild"><strong><?php echo $ticket->event_address ?></strong></div>
								<div class="date"><?php echo $ticket->event_date ?></div>
								<div class="infPlace">
									<?php echo $ticket->place_string ?>
								</div>
								<?php if ($ticket->price > 1): ?>
									<div class="date">Цена: <?php echo $ticket->price ?>  грн.</div>
								<?php else: ?>
									<div class="date">Пригласительный</div>
								<?php endif; ?>
							</div>
							<div class="barCode">
								<div class="wImg">
									<img src="/Plugins/PhpBarcodeMaster/barcode.php?text=<?php echo urlencode($ticket->barcode) ?>">
								</div>
								<!-- <div class="barcodeText"><?php #echo $ticket->barcode ?></div> -->
							</div>
							<div class="logo">
								<!-- <img src="<?php #echo Core\HTML::bmedia('pic/ck_logo.png') ?>"> -->
								<p><?php echo $ticket->phone ?></p>
							</div>
						</td>
						<td class="rightCol">				
							<div class="controlInfo two">
								<!-- <div class="barcodeText"><?php #echo $ticket->barcode ?></div> -->
								<?php if (isset($ticket->print_name) && mb_strlen($ticket->print_name, 'UTF-8') > 0): ?>
									<div class="title"><?php echo $ticket->print_name ?></div>
									<div class="subTitle"><?php echo $ticket->print_name_small ?></div>
								<?php else: ?>
									<div class="title2"><?php echo $ticket->event_name ?></div>
								<?php endif ?>
								<div class="date"><?php echo $ticket->event_date ?></div>
								<div class="infPlace">
									<?php echo $ticket->place_string ?>
								</div>
								<?php if ($ticket->price > 1): ?>
									<div class="infPlace">Цена: <?php echo $ticket->price ?>  грн.</div>
								<?php else: ?>
									<div class="infPlace">Пригласительный</div>
								<?php endif; ?>
							</div>
							<div class="control">Контроль</div>
						</td>
					</tr>
				</table>
	    	</div>
	    <?php endforeach ?>
	<?php endif ?>
    <style type="text/css">
		/*-------------------------------  reset  ------------------------------------*/
	    a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,
	    fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,
	    p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;font-size:100%;vertical-align:baseline}
	    embed,img,object{max-width:100%}
	    img {height: auto;}
	    table{width:100%;border-collapse:collapse;border-spacing:0}
	    td,td img{vertical-align:top}
	    ::-moz-selection{text-shadow:none;color:#fff;background:#2597ff}
	    ::-ms-selection{text-shadow:none;color:#fff;background:#2597ff}
	    [hidden]{display:none}

	    html, body {height: 100%;margin: 0;padding: 0;}
	    html {display: block;font-family: sans-serif;font-size: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
  		body {
	  		color: #000;
	        background: #fff;
	        position: relative;
  		}
  		@media print {
	        * {
	            text-shadow: none !important;
	            color: #000 !important;
	            background: transparent !important;
	            box-shadow: none !important;
	        }
	        a, a:visited {text-decoration: underline;}
	        a[href]:after {content: " (" attr(href) ") ";}
	        abbr[title]:after {content: " (" attr(title) ") ";}
	        a[href^="javascript:"]:after, a[href^="#"]:after {content: "";}
	        pre, blockquote {border: 1px solid #999; page-break-inside: avoid;}
	        thead {display: table-header-group;}
	        tr, img {page-break-inside: avoid;}
	        img {max-width: 100% !important;}
	        @page {
	            margin: 0.5cm;
	        }
	        p, h2, h3 {orphans: 3; widows: 3;}
	        h2, h3 {page-break-after: avoid;}
	    }

	    .one-page {
	    	position: relative;
	    	height: 730px;
			margin-bottom: 300px;
	    }	
	    .wrapper {
	        width: 730px;
	        height: 243px;
	        margin: 0 24px 0px;
	        table-layout: fixed;
	        color: #000000;
	        font: 14px/1.6em Arial,Helvetica,sans-serif;
	        position: absolute;
    		top: 232px;
	        -webkit-transform: rotate(90deg);
	        -moz-transform: rotate(90deg) ;
	        -ms-transform: rotate(90deg);
	        transform: rotate(90deg);
	    }
	    .wrapper tr td {
	        vertical-align: top;
	    }
	    .wrapper .leftCol,
	    .wrapper .rightCol {
	    	height: 243px;
	        width: 115px;
	        position: relative;
	    }
	    .wrapper .centerCol {
	    	height: 243px;
			padding: 37px 0 0 34px;
		    position: relative;
		    width: 466px;
	    }
	    .title {
	        font-weight: bold;
	        font-size: 21px;
    		line-height: 44px;
	        max-height: 44px;
	        height: 44px;
	        overflow: hidden;
	        max-width: 222px;
	    }
	    .title2 {
	        font-weight: bold;
	        font-size: 21px;
    		line-height: 34px;
	        max-height: 67px;
	        height: 67px;
	        overflow: hidden;
	        max-width: 222px;
	        white-space: pre-wrap;
	    }
	    .subTitle {
	        font-size: 17px;
    		line-height: 17px;
    		max-height: 17px;
    		overflow: hidden;
	        margin: 0 0 10px;
	    }
	    .nameBuild {
	        font-size: 12px;
    		line-height: 21px;
	    }
	    .date {
	        font-size: 17px;
    		line-height: 31px;
	    }
	    .infPlace {
	        font-size: 15.5px;
	        font-size: 14px;
    		line-height: 18px;
    		max-width: 222px;
	    }
	    .contentCenter {
	        float: left;
	        width: 84%;
	    }
	    .barcodeText {
	        text-align: center;
	    }
	    .barCode {
	        position: absolute;
		    top: 134px;
		    right: -84px;
	        -webkit-transform: rotate(90deg);
	        -moz-transform: rotate(90deg) ;
	        -ms-transform: rotate(90deg);
	        transform: rotate(90deg);
	    }
	    .wImg {
	        line-height: 0;
	        text-align: center;
	        max-width: 220px;
	        height: 20px;
	    }
	    .controlInfo {
			position: absolute;
		    top: 69px;
		    left: -57px;
		    width: 238px;
	        -webkit-transform: rotate(-90deg);
	        -moz-transform: rotate(-90deg) ;
	        -ms-transform: rotate(-90deg);
	        transform: rotate(-90deg);
	    }
	    .controlInfo > div  {
			margin: 0 0 2px;
	    }
	    .controlInfo.two {
			left: -51px;
	    }
	    .controlInfo .barcodeText {
	        text-align: left;
	    }
	    .controlInfo .title {
	        font-weight: normal;
	        font-size: 14px;
	        line-height: 14px;
	        height: 14px;
	        white-space: normal;
	    }
	    .controlInfo .title2 {
	        font-weight: normal;
	        font-size: 14px;
	        margin: 0 0 5px;
	        line-height: 18px;
	        height: 39px;
	        white-space: normal;
	    }
	    .controlInfo .subTitle {
	        font-size: 13px;
    		line-height: 20px;
    		max-height: 20px;
	    }
	    .controlInfo .date {
	        font-size: 14px;
	        line-height: 14px;
	    }
	    .controlInfo .infPlace {
	        font-size: 13px;
    		line-height: 13px;
	    }
	    .logo {
	        position: absolute;
			bottom: 2px;
			right: 7px;
	        text-align: center;
	    }
/*	    .logo img {
	        max-width: 60px;
	        width: 60px;
	        height: 60px;
	    }*/
	    .rightCol .control {
			text-transform: uppercase;
			text-align: center;
			position: absolute;
			bottom: 0px;
			padding: 0 0 0 14px;
			width: 100%;
			font: bold 15px Arial, Helvetica, sans-serif;
	    }
	</style>
</body>
</html>