<div style="padding: 10px">
    <div class="container-xxl">			
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="firstName">Userid</label>
                <input type="text" class="form-control" id="txtuserid_reg"required maxlength="10">
            </div>
            <div class="col-md-4 mb-3">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="txtuserfirstname_reg"required maxlength="40">
            </div>
            <div class="col-md-4 mb-3">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="txtuserlastname_reg" placeholder="" value="" required>
            </div>                
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="firstName">Password</label>
                <input type="password" class="form-control" id="txtuserpw_reg"required maxlength="10">
            </div>
            <div class="col-md-6 mb-3">
                <label for="firstName">Confirm password</label>
                <input type="password" class="form-control" id="txtuserpwconf_reg"required maxlength="40">
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="input-group mb-2">                    
                    <span class="input-group-text" >Group</span>                    
                    <select class="form-select" id="cmbGroup_reg" required>
                    <?php foreach ($lusergroup as $r) { ?>
                    <option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
                    <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-3">
            <button id="btnSaveUser_reg" class="btn btn-lg btn-primary btn-sm" type="submit"><i class="fas fa-save"></i> Save</button>
            </div>
        </div>

    </div>
</div>
<script>    
	$('#btnSaveUser_reg').click( function() {		
		var quserid = $("#txtuserid_reg").val();
        var qfirstname = $("#txtuserfirstname_reg").val();
        var qlastname = $("#txtuserlastname_reg").val();
        var qpw1 = $("#txtuserpw_reg").val();
        var qpw2 = $("#txtuserpwconf_reg").val();
        var grp = $("#cmbGroup_reg").val();
        if (quserid==''){
            $("#txtuserid_reg").focus();
            return;
        }
        if(qpw1=='' || qpw2==''){
            alert('password should not be empty');
            $("#txtuserpw_reg").focus();
            return ;
        }
        if(qpw1!=qpw2){
            alert('please confirm password');
            return ;
        }        
        jQuery.ajax({
            type: "get",
            url: "<?php echo base_url();?>" + "index.php/User/register",
            dataType: "text",
            data: {inUsrid: quserid , inNMF : qfirstname, inNML : qlastname, inUsrGrp: grp, inPW: qpw1 },
            success:function(response) {                
                if (response=='1'){
                    swal.fire(
                        'Good',
                        'registered successfully',
                        'success'
                    );
                } else if (response=="ada") {
                    alert('the user id is already exist');
                }
            },
            error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });
	});	

</script>