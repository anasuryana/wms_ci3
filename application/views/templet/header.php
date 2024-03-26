<!DOCTYPE html>
<html lang="en">
<head>	
	<meta charset="UTF-8">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Ana Suryana">
	<title>Warehouse Management System</title>
    <link rel="icon" href="<?=base_url("assets/fiximgs/favicon.png") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/jeui/themes/metro-blue/easyui.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/jeui/themes/icon.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/jeui/themes/color.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/css/home.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap/css/bootstrap.min.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap_dp/css/bootstrap-datepicker.min.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/fontaw/css/all.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/select2/css/select2.min.css") ?>">		
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/DataTables/datatables.min.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/alertify.min.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/themes/semantic.min.css") ?>">	
	<link rel="stylesheet" href="<?=base_url("assets/bootstrap_dt/css/tempus-dominus.min.css")?>">	
	<link rel="stylesheet" href="<?=base_url("assets/tablesorter/css/theme.bootstrap_4.min.css")?>">	
	<link rel="stylesheet" href="<?=base_url("assets/tablesorter/css/theme.blue.css")?>">	
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/jspreadsheet/jspreadsheet.css")?>">	
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/jsuites/jsuites.min.css")?>">	
	<script type="text/javascript" src="<?=base_url("assets/chart/chart.umd.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/jquery/jquery.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/js/sweetalert2.all.min.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/jeui/jquery.easyui.min.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/DataTables/datatables.min.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/bootstrap_dp/js/bootstrap-datepicker.min.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/numeral/numeral.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/js/moment.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/select2/js/select2.full.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/js/js.cookie.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/alertify/alertify.min.js") ?>"></script>				
	<script type="text/javascript" src="<?=base_url("assets/js/clipboard.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/js/onscan.min.js") ?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/js/xlsx.full.min.js") ?>"></script>	
	<script type="text/javascript" src="<?=base_url("assets/js/FileSaver.js") ?>"></script>	
	<script src="<?=base_url("assets/bootstrap_dt/js/tempus-dominus.min.js")?>"></script>
	<script src="<?=base_url("assets/tablesorter/js/jquery.tablesorter.js")?>"></script>
	<script src="<?=base_url("assets/js/raphael.min.js")?>"></script>
	<script src="<?=base_url("assets/js/html5-qrcode.min.js")?>"></script>	
	<script src="<?=base_url("assets/jqmap/js/jquery.mapael.js")?>"></script>
	<script src="<?=base_url("assets/jqmap/js/maps/pt_smt_fg_map.js")?>"></script>
	<script src="<?=base_url("assets/socket/socket.io.min.js")?>"></script>	
	<script src="<?=base_url("assets/jspreadsheet/index.js")?>"></script>	
	<script src="<?=base_url("assets/jsuites/jsuites.min.js")?>"></script>	
	<script src='<?=base_url("assets/js/tesseract.min.js")?>'></script>
	<script src='<?=base_url("assets/js/inputmask.min.js")?>'></script>
	
</head>
<body class="easyui-layout">	
	<div split="true" data-options="region:'west',title:'<i class=\'fas fa-sitemap\'></i> <i>Module List</i>',hideCollapsedContent:false" style="width:15%;min-width:190px;padding:5px;">
    	<ul id="mmenu"></ul>		
    </div>