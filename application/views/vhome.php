 <div  data-options="region:'center',title:''" style="background: #D3CCE3;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to right, #E9E4F0, #D3CCE3);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to right, #E9E4F0, #D3CCE3); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
	">  

   
    <div id="tt" class="easyui-tabs" style="width:100%" data-options="tools:'#tab-tools',fit:true,border:false,plain:true">    
    </div>
    
    <div id="tab-tools">
    <a href="#" title="Tasks" class="easyui-linkbutton" onclick="home_btn_task_eCK()"><span class="fas fa-tasks"></span></a>
    <a href="#" title="Change your password" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-change_pw'" onclick="dlgChangePW();"></a>		
    <a href="#" title="Exit" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-exit'" onclick="dlgExit()"></a>
	  </div>        
</div>
<div class="modal fade" id="HOME_MODCHGPW">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Password Changes</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                  <div class="input-group input-group-sm mb-1">
                      <span class="input-group-text" >Old Password</span>
                      <input type="password" class="form-control" id="txtoldpw" required>                      
                  </div>
                </div>
            </div>           
            <div class="row">
                <div class="col mb-1">
                  <div class="input-group input-group-sm mb-1">
                      <span class="input-group-text" >New Password</span>
                      <input type="password" class="form-control" id="txtnewpw" required>                      
                  </div>
                </div>
            </div>           
            <div class="row">
                <div class="col mb-1">
                  <div class="input-group input-group-sm mb-1">
                      <span class="input-group-text" >Confirm Password</span>
                      <input type="password" class="form-control" id="txtconfirmpw" required>                      
                  </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-sm btn-primary" id="btncommit_changepw" onclick="btncommit_changepw_e_click()"><i class="fas fa-save"></i></button>
        </div>              
      </div>
    </div>
</div>
<div id="home_w_tasks"  class="easyui-window" title="Task list" 
    data-options="modal:false,closed:true,iconCls:'icon-tip',collapsible:true, minimizable:false,cls:'c7'" 
    style="width:500px;height:200px;padding:5px;" >
    <div style="padding:1px" >
        <div class="container-fluid">            
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_divrmnull">
                        <table id="txfg_tblrmnull" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>TXID</th>
                                    <th>Assy Code</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var uidnya = '<?php echo $sapaDiaID;?>';
	// const socket = io("http://192.168.0.29:3001")
	// socket.on("serversmt", (val) => {	
	// 	if (val.id === 'exBCMessage') {
	// 		console.log(val)
	// 	} else {
	// 		console.log({sini: 'sini', val: val})
	// 	}
	// })
	function dlgExit(){
		if(confirm("Are You sure want to exit ?")){
			// socket.disconnect()
			window.open("<?=base_url("pages/logout")?>","_self");
		}
	}
	function dlgChangePW(){
		$("#HOME_MODCHGPW").modal('show')
	}
	function home_btn_task_eCK() {
		$("#home_w_tasks").window('open')		
	}

	function btncommit_changepw_e_click(){
		let a = getOldPW()
		if (a=='1'){
			if($('#txtnewpw').val()==$('#txtconfirmpw').val()){
				let pw = $('#txtnewpw').val();
				if(pw.trim().length<4){
					alertify.warning("the password is too weak");
					return;
				}
				if(confirm("Are you sure ?")){
					$.ajax({
						type: "get",
						url: "<?=base_url('user/setnewpw')?>",
						dataType: "text",
						data: {inUID : uidnya,inPw: pw },
						async: false,
						success:function(response) {
							alert(response);
						},
						error:function(xhr,ajaxOptions, throwError) {
							alert(throwError);
						}
					});
				}
			} else {
				alert('Password does not match with the confirmation');
			}
		} else {    
			alertify.message("Invalid old password");
			$('#txtoldpw').focus();    
		}
	}

	function getOldPW(){
		let pw = $('#txtoldpw').val()
		let hasil =  $.ajax({
			type: "get",
			url: "<?=base_url('user/getpw_sts')?>",
			dataType: "text",
			data: {inUID : uidnya,inPw: pw },
			async: false,
			success:function(response) {
			//return response;
			},
			error:function(xhr,ajaxOptions, throwError) {
			alert(throwError);
			}
			})
		return hasil.responseText;
	}
</script>
