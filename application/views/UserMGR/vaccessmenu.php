<div style="padding:5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="col-md-12 order-md-1">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="input-group input-group-sm">                                
                                <label class="input-group-text">User Group</label>                                
                                <select class="form-select" id="cmbgroup" required>
                                    <option value="_">Choose...</option>									
                                    <?php foreach ($lusergroup as $r) { 
										 if( $r['MSTGRP_ID']=='ADMIN') {
											 if($this->session->userdata('gid')=='ADMIN') {?>
                                    		<option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
									<?php }} else {?>
										<option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
                                    <?php } }?> 
                                </select>                                
                                <button id="vacc_btn_showmod" class="btn btn-primary" title="Add new group"><i class="fas fa-plus"></i></button>                                
                            </div>																
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1 text-center">
                            <i class="fas fa-universal-access fa-spin fa-9x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="table-responsive" id="divroles">
                    <ul class="easyui-tree" id="mmenu2"  data-options="url:'<?php echo base_url("index.php/menu/setting"); ?>',method:'get',animate:true,lines:true,checkbox:true,onlyLeafCheck:true"></ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">				    										
            </div>
            <div class="col-md-6 mb-1">
                <button id="btnSave" title="Save changes" class="btn btn-primary btn-block" type="submit"><i class="fas fa-save"></i></button>										
            </div>				
        </div>			
	</div>
</div>
<div class="modal fade" id="VACC_MOD">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add a New User Group</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >ID</span>                        
                        <input type="text" class="form-control" id="vacc_txt_id" maxlength="4" required >
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Name</span>                        
                        <input type="text" class="form-control" id="vacc_txt_nm" maxlength="50" required >
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <button type="button" id="vacc_btn_save" class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>             
      </div>
    </div>
</div>
<script>
	var tangkai 		= [];
	var lgroupid 		= [];
	var lmenuid 		= [];
	var lmenudefault	= [];
	$("#divroles").css('height', $(window).height()*76/100);
	$('#cmbgroup').change(function(){
		console.log(lmenudefault)
		var a = $(this).val();		
		for( let i = 0;i<lmenudefault.length;i++){
			//console.log(lmenudefault[i]);
			let node = $('#mmenu2').tree('find', lmenudefault[i]);
			$('#mmenu2').tree('uncheck', node.target);
		}
		for( let i = 1;i<lgroupid.length;i++){
			if (lgroupid[i]==a) {
				let node = $('#mmenu2').tree('find', lmenuid[i]);
				$('#mmenu2').tree('check', node.target);
			}
		}
		$('#mmenu2').tree('expandAll');
	});

	$('#btnSave').click(function(){
		tangkai.length	= 0;
		var nodes 		= $('#mmenu2').tree('getChecked');		
		var s2			= '';
		var grpid		= $('#cmbgroup').val();
		if (grpid=='_'){
			return;
		}
		for(var i=0; i<nodes.length ; i++) {
			s2 = nodes[i].id.toString();			
			insertTangkai(s2);
			if (s2.length>1) {
				var tanda=s2;
				var jalan=true;
				var percobaan=1;
				var cnode = $('#mmenu2').tree('getParent', nodes[i].target);
				
				insertTangkai(cnode.id.toString());
				while(jalan){
					if (cnode != null) {
						tanda = cnode.id.toString();
						insertTangkai(tanda);
						if(tanda.length>0){
							cnode = $('#mmenu2').tree('getParent',cnode.target);
							if(cnode != null) {
								insertTangkai(cnode.id.toString());								
							}						
						} else{
							jalan=false;
						}
					} else {
						jalan=false;
					}
				}
			}		
		}
		// console.log(tangkai);
		// console.log(grpid);
		
		s2='';
		for(var i=0;i<tangkai.length;i++){
			s2 += ''+tangkai[i]+',';
		}
		s2=s2.substr(0,s2.length-1);
		console.log(s2);
	
		jQuery.ajax({
			type: "get",
			url: "<?=base_url('Menu/setaccess')?>",
			dataType: "text",
			data: {inID : grpid, inNM : s2 },
			success:function(response) {
				swal.fire(
					'Good',
					'Saved successfully',
					'success'
				);
				getAllAM();
				//location.reload();
			},
			error:function(xhr,ajaxOptions, throwError) {
			    alertify.error(throwError);
			}
		});
	});

	function insertTangkai(punik){
		var notAdded =true;
		for(var u=0;u<=tangkai.length;u++){
			if (tangkai[u]==punik){
				notAdded=false;break;
			}
		}
		if (notAdded){
			tangkai.push(''+punik+'');
		}
	}
	getAllAM();
	function getAllAM(){
		jQuery.ajax({
			type: "GET",
			url: "<?=base_url('Menu/getall')?>",
			dataType: "json",
			success:function(response) {
				lgroupid.length=0;
				lmenuid.length=0;
				for (var i=0;i<response.length;i++){
					//console.log(response[i].ugid + ' _' +response[i].idmenu);
					lgroupid.push(''+response[i].EMPACCESS_GRPID+'');
					lmenuid.push(''+response[i].EMPACCESS_MENUID+'');
				}
			},
			error:function(xhr,ajaxOptions, throwError) {
                alertify.error(throwError);
			}
		});
	}

	getDefaultMenu();

	function getDefaultMenu(){
		jQuery.ajax({
			type: "GET",
			url: "<?=base_url('Menu/getdefault')?>",
			dataType: "json",
			success:function(response) {
				lmenudefault.length=0;				
				for (var i=0;i<response.length;i++){					
					lmenudefault.push(''+response[i].MENU_ID+'');
				}
			},
			error:function(xhr,ajaxOptions, throwError) {
			    alertify.error(throwError);
			}
		});
	}
	$("#vacc_btn_showmod").click(function (e) { 
        e.preventDefault();
        $("#VACC_MOD").modal('show');
    });
    $("#VACC_MOD").on('shown.bs.modal', function(){
        $("#vacc_txt_id").focus();
    });
    $("#vacc_btn_save").click(function (e) { 
        e.preventDefault();
        $("#VACC_MOD").modal('hide');
        var mid = $("#vacc_txt_id").val();
        var mnm = $("#vacc_txt_nm").val();
        $.ajax({
            type: "post",
            url: "<?=base_url('User/setgroup')?>",
            data: {inid: mid, innm: mnm},
            dataType: "json",
            success: function (response) {
                var mid = $("#vacc_txt_id").val();
                var mnm = $("#vacc_txt_nm").val();                
                if(response[0].cd=='1'){
                    $("#cmbgroup").append(new Option(mnm,mid ));
                }
                alertify.message(response[0].dsc);
                $("#vacc_txt_id").val('');
                $("#vacc_txt_nm").val('');
                
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
</script>