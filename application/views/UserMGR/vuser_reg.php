<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="firstName">Userid</label>
                <input type="text" class="form-control" id="txtuserid_reg"required maxlength="9">
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
                <input type="password" class="form-control" id="txtuserpw_reg"required onkeyup="txtuserpw_reg_eKeyUp(event)">
            </div>
            <div class="col-md-6 mb-3" id="txtuserpw_div" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="firstName">Confirm password</label>
                <input type="password" class="form-control" id="txtuserpwconf_reg"required maxlength="40">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="firstName"><span class="fas fa-mobile-retro"></span> Handy Terminal Password</label>
                <input type="password" class="form-control" id="txtuserpw_reg_ht" required maxlength="8">
            </div>
            <div class="col-md-6 mb-3" >
                <label for="firstName"><span class="fas fa-mobile-retro"></span> Confirm Handy Terminal Password</label>
                <input type="password" class="form-control" id="txtuserpwconf_reg_ht" required maxlength="8">
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
                    <button id="btnsync_reg" onclick="btnsync_reg_eClick()" class="btn btn-lg btn-primary btn-sm"><i class="fas fa-sync"></i></button>
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
    function btnsync_reg_eClick() {
        $.ajax({            
            url: "<?=base_url('User/userGroup')?>",
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length
                let strData = ``
                for(let i=0;i<ttlrows; i++) {
                    strData += `<option value="${response.data[i].MSTGRP_ID}">${response.data[i].MSTGRP_NM}</option>`
                }
                cmbGroup_reg.innerHTML = strData
                cmbGroup_reg.focus()
            }, error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        })
    }

    function txtuserpw_reg_eKeyUp(e){
        let statusPW = smtPWValidator(e.target.value)
        if(statusPW.cd==='1') {
            txtuserpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            txtuserpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
        }
    }    
	$('#btnSaveUser_reg').click( function() {		
		const quserid = $("#txtuserid_reg").val();
        const qfirstname = $("#txtuserfirstname_reg").val();
        const qlastname = $("#txtuserlastname_reg").val();
        const qpw1 = $("#txtuserpw_reg").val();
        const qpw2 = $("#txtuserpwconf_reg").val();
        const grp = $("#cmbGroup_reg").val();
        const htpw = txtuserpw_reg_ht.value
        const htpw_c = txtuserpwconf_reg_ht.value
        if (quserid==''){
            $("#txtuserid_reg").focus();
            return;
        }
        if(qpw1==''){
            alert('password should not be empty');
            $("#txtuserpw_reg").focus();
            return ;
        }
        if(qpw2==''){
            alert('password should not be empty');
            $("#txtuserpwconf_reg").focus();
            return ;
        }


        if(qpw1!=qpw2){
            alert('please confirm password')
            txtuserpwconf_reg.focus()
            return ;
        }
        if(htpw=='' || htpw_c==''){
            alert('password should not be empty');
            txtuserpw_reg_ht.focus();
            return ;
        }
        if(htpw!=htpw_c){
            alert('please confirm password')
            txtuserpwconf_reg_ht.focus()
            return ;
        }

        //validate WEBApp password
        let statusPW = smtPWValidator(qpw1)
        if(statusPW.cd==='1') {
            txtuserpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            txtuserpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
            txtuserpw_reg.focus()
            return 
        }

        //validate HTApp password
        if(htpw.length<7) {
            alertify.warning(`at least 7 characters`)
            txtuserpw_reg_ht.focus()
            return
        }
        if(confirm(`Are you sure ?`)) {
            jQuery.ajax({
                type: "POST",
                url: "<?=base_url("User/register")?>" ,
                dataType: "text",
                data: {inUsrid: quserid , inNMF : qfirstname, inNML : qlastname, inUsrGrp: grp, inPW: qpw1
                , inPWHT:htpw },
                success:function(response) {
                    if (response=='1'){
                        swal.fire(
                            'Good',
                            'registered successfully',
                            'success'
                        );
                    } else if (response==="ada") {
                        alert('the user id is already exist');
                    }
                },
                error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            })
        }
	});	

</script>