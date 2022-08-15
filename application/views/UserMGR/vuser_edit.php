<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="txtUSR_nmf_e" placeholder="" value="" required maxlength="30">
            </div>
            <div class="col-md-8 mb-2">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="txtUSR_nml_e" placeholder="" value="" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm mb-2">                    
                    <span class="input-group-text" >Group</span>                    
                    <select class="form-select" id="cmbGroup" required>
                    <?php foreach ($lusergroup as $r) { 
                        if( $r['MSTGRP_ID']=='ADMIN' && $this->session->userdata('gid')=='ADMIN') {?>
                        <option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
                    <?php } else {?>
                        <option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
                        <?php }          }?>
                    </select>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text" >Status</span>
                    <select class="form-select" id="user_cmb_active" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button id="btnEditUser" class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save</button>
                    <button id="btnResetUser" class="btn btn-outline-warning">Reset Password</button>
                    <button id="btnResetUserht" class="btn btn-outline-warning" onclick="btnResetUserht_eClick()">Reset HT Password</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <table id="tblUserInfo" class="table table-sm table-striped table-bordered" style="width:100%;height:100%;cursor:pointer">
                    <thead>
                        <tr>
                            <th>Userid</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Registered Date</th>
                            <th>Ugid</th>
                            <th>Group</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MDHRIS_EMPL_RESET">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Reset Password</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="col-md-12 order-md-1">
                <div class="row">
                    <div class="col-md-9 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" ><i class="fas fa-lock text-warning"></i></span>
                            <input type="password" class="form-control" id="vuser_txtnewpassword" placeholder="Enter new Password here" onkeyup="vuser_txtnewpassword_eKeyUp(event)">
                        </div>
                    </div>
                    <div class="col-md-3 mb-1" id="vuser_newpw_div">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" ><i class="fas fa-lock text-warning"></i></span>
                            <input type="password" class="form-control" id="vuser_txtnewpassword_c" placeholder="Confirm new Password here" >
                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        
                    </div>
                </div>
            </div>         
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer text-center">
        <button class="btn btn-secondary" id="btnresetpasswordnew"><i class="fas fa-check"></i></button>
        </div>
        
      </div>
    </div>
</div>
<div class="modal fade" id="MDHRIS_EMPL_RESET_HT">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Reset Handy Terminal Password</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="col-md-12 order-md-1">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group mb-1">                            
                            <span class="input-group-text" ><i class="fas fa-lock text-warning"></i></span>                            
                            <input type="password" class="form-control" id="vuser_txtnewpassword_ht" placeholder="Enter new Password here">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group mb-1">                            
                            <span class="input-group-text" ><i class="fas fa-lock text-warning"></i></span>                            
                            <input type="password" class="form-control" id="vuser_txtnewpassword_c_ht" placeholder="Confirm new Password here" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer text-center">
            <button class="btn btn-secondary" id="btnresetpasswordnew_ht"><i class="fas fa-check"></i></button>
        </div>
        
      </div>
    </div>
</div>
<script>
	var isEditMode_um=1;
	var sourceArr = [];
    var cuserid = '';    
    var tableusr = $('#tblUserInfo').DataTable();
    initdataUSRList()

    function vuser_txtnewpassword_eKeyUp(e){
        let statusPW = smtPWValidator(e.target.value)
        if(statusPW.cd==='1') {
            vuser_newpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            vuser_newpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
        }
    }
    
    $("#btnResetUser").click(function(){
        if(cuserid!==''){
            $("#MDHRIS_EMPL_RESET").modal('show');
        } else {
            alert('please select user data first');
        }
    })
    function btnResetUserht_eClick() {
        if(cuserid!==''){
            $("#MDHRIS_EMPL_RESET_HT").modal('show');
        } else {
            alert('please select user data first');
        }
    }
    $("#btnresetpasswordnew").click(function(){
        let newpw = $("#vuser_txtnewpassword").val();
        let newpw_c = $("#vuser_txtnewpassword_c").val();
        if(newpw!==newpw_c){
            alert('password does not match');
            $("#vuser_txtnewpassword_c").focus();
        } else {
            let statusPW = smtPWValidator(newpw)
            if(statusPW.cd==='1') {
                vuser_newpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
            } else {
                vuser_newpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
                vuser_txtnewpassword.focus()
                return
            }
            if (confirm('Are you sure ?')){
                jQuery.ajax({
                    type: "post",
                    url: "<?=base_url("User/resetpassword")?>",
                    dataType: "text",
                    data: {innewpw : newpw_c, inid: cuserid , apptype: 'webapp'},
                    success:function(response) {
                        if(response=='1'){
                            $("#MDHRIS_EMPL_RESET").modal('hide');
                            alertify.notify("password was reseted");
                        }
                    },
                    error:function(xhr,ajaxOptions, throwError) {
                        alert(throwError);
                    }
                })
            }
        }
    })   
    $("#btnresetpasswordnew_ht").click(function(){
        let newpw = $("#vuser_txtnewpassword_ht").val();
        let newpw_c = $("#vuser_txtnewpassword_c_ht").val();
        if(newpw!==newpw_c){
            alert('password does not match');
            $("#vuser_txtnewpassword_c_ht").focus();
        } else {
            if(newpw.length<7) {
                alertify.warning('at least 7 character')
                vuser_txtnewpassword_ht.focus()
                return
            }
            if (confirm('Are you sure ?')){
                jQuery.ajax({
                    type: "post",
                    url: "<?=base_url("User/resetpassword")?>",
                    dataType: "text",
                    data: {innewpw : newpw_c, inid: cuserid , apptype: 'htapp'},
                    success:function(response) {
                        if(response=='1'){
                            $("#MDHRIS_EMPL_RESET_HT").modal('hide');
                            alertify.notify("HT password was reseted");
                        }
                    },
                    error:function(xhr,ajaxOptions, throwError) {
                        alert(throwError);
                    }
                })
            }
        }
    })   
    
    function initdataUSRList(){
        tableusr = $('#tblUserInfo').DataTable({
            destroy: true,
            scrollX: true,
            ajax: '<?=base_url("User/viewAll_reged")?>',
            columns:[
                { "data": 'MSTEMP_ID'},
                { "data": 'MSTEMP_FNM'},
                { "data": 'MSTEMP_LNM'},
                { "data": 'MSTEMP_REGTM'},
                { "data": 'MSTEMP_GRP'},
                { "data": 'MSTGRP_NM'},                
                { "data": 'MSTEMP_STS'},                
            ],
            columnDefs : [
                {
                    "targets": [4],
                    "visible": false
                },
                {
                    "targets": [6],
                    "visible": false
                }
            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {                
                if(aData.MSTEMP_STS==0){
                    $('td', nRow).css('background-color', '#FF330B');
                }                
            }
        });
        tableusr.on( 'search.dt', function () {
            document.getElementById('txtUSR_nmf_e').value="";
            document.getElementById('txtUSR_nml_e').value="";
            cuserid = "";
        } );
    }
    
    $('#tblUserInfo tbody').on( 'click', 'tr', function () { 
		if ( $(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#tblUserInfo tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }                
		isEditMode_um=1;
		
        const pos = tableusr.row(this).index();
        const row = tableusr.row(pos).data();
        cuserid = row["MSTEMP_ID"];
        $("#cmbGroup").val(row["MSTEMP_GRP"]);
        $("#txtUSR_nmf_e").val(row["MSTEMP_FNM"]);
        $("#txtUSR_nml_e").val(row["MSTEMP_LNM"]);
        document.getElementById('user_cmb_active').value = row["MSTEMP_STS"];
    });
	
	$('#btnEditUser').click( function() {
		let nmf = $("#txtUSR_nmf_e").val();
		let nml = $("#txtUSR_nml_e").val();
        let grp = $("#cmbGroup").val();
        let sts = document.getElementById('user_cmb_active').value;
        if(cuserid.length===0){
            alertify.warning("Select the data on the table below first");
            return;
        }        
        if(confirm("Are you sure ?")){
            jQuery.ajax({
                type: "get",
                url: "<?=base_url("User/change")?>",
                dataType: "text",
                data: {inUsrid: cuserid , inNMF : nmf, inNML : nml, inUsrGrp: grp, insts: sts },
                success:function(response) {
                    cuserid = "";
                    document.getElementById('txtUSR_nmf_e').value="";
                    document.getElementById('txtUSR_nml_e').value="";
                    initdataUSRList();                
                    swal.fire({
                        title : 'Good',
                        html : response,
                        icon :'success',
                        timer : 1000
                    });
                    
                },
                error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            });
        }
	});	

</script>