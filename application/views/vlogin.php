<!doctype html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<link rel="icon" href="<?php echo base_url("assets/fiximgs/favicon.png"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors, Ana Suryana">    
    <title>Warehouse Management System</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url("assets/bootstrap/css/bootstrap.min.css")?>" rel="stylesheet">
    <script src="<?=base_url("assets/jquery/jquery.min.js")?>"></script>

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="<?=base_url("assets/bootstrap/ovrcss/signin.css")?>" rel="stylesheet">
  </head>
<body >
<div class="container">	
		<?=form_open('pages/login', 'class="form-signin"')?>
			<div class="row">				
				<div class="col mb-4">
					<h1 class="display-4 text-primary mb-0">WMS</h1>
					Warehouse Management System
				</div>
			</div>
			<div class="row" id="lnwarning">				
				<div class="col">
					<div class="alert alert-warning" role="alert">
						Please fill <strong>UserID</strong> first
					</div>
				</div>
			</div>
			<div class="row" id="ln1">
				<div class="col">
					<input type="text" name="inputUserid" id="inputUserid" class="form-control" placeholder="UserID" required autocomplete="off" autofocus>
					<div class="d-grid">
						<button type="button" class="btn btn-primary" id="btnnext" >Next</button>
					</div>
				</div>
			</div>
			<div class="row" id ="ln2">
	  			<div class="col">	  				
					<a href="" id="btnback" title="back" >Login as another account ?</a> 	  <br><br>	  				
					<input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required>
					<div class="d-grid">
						<button class="btn btn-primary btn-block" type="submit">Sign in</button>
					</div>
	  			</div>
			</div>
		<?=form_close()?>	
</div>
<script>
$("#lnwarning").hide();
$("#ln2").hide();
$("#inputUserid").keypress(function (e) { 	
	if(e.which==13){	
		if ($(this).val() != ""	) {			
			$("#ln1").slideUp('slow', function(){
				$("#ln2").show();
				$("#inputPassword").focus();
			});	
		} else {
			$("#lnwarning").show();
		}			
		e.preventDefault();
	}	
});
$("#inputUserid").keydown(function (e) { 
	$("#lnwarning").hide();
});
$("#btnback").click(function (e) { 	
	e.preventDefault();
	$("#ln2").hide('slow', function(){
		$("#ln1").show();
		$("#inputUserid").focus();
		$("#inputUserid").select();
	});
});
$("#btnnext").click(function(e){	
	if($("#inputUserid").val()!=""){
		$("#ln1").slideUp('slow', function(){
			$("#ln2").show();
			$("#inputPassword").focus();
		});
	} else {
		$("#lnwarning").show();
	}	
});
</script>
</body>

</html>
