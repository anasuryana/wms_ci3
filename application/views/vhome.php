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
                <div class="col">
                    <ul class="nav nav-tabs" id="TabPW" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#TabChildPWWEB" type="button" role="tab" aria-controls="home" aria-selected="true">Web App</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#TabChildPWHT" type="button" role="tab" aria-controls="profile" aria-selected="false"><span class="fas fa-mobile-retro"></span> Handy Terminal App</button>
                        </li>											
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="TabChildPWWEB" role="tabpanel">
                            <div class="container-fluid">
                                <div class="row mt-3">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Old Password</span>
                                            <input type="password" class="form-control" id="txtoldpw" required>
                                        </div>
                                    </div>
                                    <div class="col mb-1">
                                    </div>
                                </div>           
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >New Password</span>
                                            <input type="password" class="form-control" id="txtnewpw" required onkeyup="txtnewpw_eKeyUp(event)">										
                                        </div>
                                    </div>                
                                    <div class="col mb-1" id="newpwweb_div">

                                    </div>                
                                </div>           
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Confirm Password</span>
                                            <input type="password" class="form-control" id="txtconfirmpw" required>
                                        </div>
                                    </div>
                                    <div class="col mb-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-primary" id="btncommit_changepw" onclick="btncommit_changepw_e_click()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="TabChildPWHT" role="tabpanel">
                            <div class="container-fluid">								 
                                <div class="row mt-3">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >New Password</span>
                                            <input type="password" class="form-control" id="txtnewpw_ht" required onkeyup="txtnewpw_ht_eKeyUp(event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Confirm Password</span>
                                            <input type="password" class="form-control" id="txtconfirmpw_ht" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-primary" id="btncommit_changepw_ht" onclick="btncommit_changepw_ht_e_click()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <div class="modal-footer">
            
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
    function dlgExit(){
        if(confirm("Are You sure want to exit ?")){			
            window.open("<?=base_url("pages/logout")?>","_self");
        }
    }
    function dlgChangePW(){
        $("#HOME_MODCHGPW").modal('show')
    }
    function home_btn_task_eCK() {
        $("#home_w_tasks").window('open')		
    }

    function smtPWValidator(pvalue) {
        let numberList = [...Array(10).keys()]
        let specialCharList = ['~','!','@','#','$','%','^','&','*','(',')','_','+',':','"','<','>','?','{','}','|']
        if(pvalue.trim().length<8){
            return {cd: '0', msg : 'At least 8 characters'}
        }
        let isFound = false
        for(let i=0; i<numberList.length; i++) {
            if(pvalue.includes(numberList[i])) {
                isFound = true
                break
            }
        }
        if(!isFound) {
            return {cd: '0', msg : 'At least 1 numerical character'}
        }
        isFound = false
        for(let i=0; i<specialCharList.length; i++) {
            if(pvalue.includes(specialCharList[i])) {
                isFound = true
                break
            }
        }
        if(!isFound) {
            return {cd: '0', msg : 'At least 1 special character'}
        }
        return  {cd: '1', msg : 'OK'}
    }

    function txtnewpw_eKeyUp(e){
        let statusPW = smtPWValidator(e.target.value)
        if(statusPW.cd==='1') {
            newpwweb_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            newpwweb_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
        }
     }	

    function btncommit_changepw_e_click(){
        let a = getOldPW()
        if (a=='1'){
            const txtNewPW = document.getElementById('txtnewpw')
            const txtConfirmPW = document.getElementById('txtconfirmpw')
            if(txtNewPW.value === txtConfirmPW.value){
                const statusPW = smtPWValidator(txtNewPW.value)
                if(statusPW.cd === '1') {
                    newpwweb_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
                } else {
                    newpwweb_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
                    statusPW.focus()
                    return
                }
                if(confirm("Are you sure ?")){
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('user/setnewpw')?>",
                        dataType: "text",
                        data: {inUID : uidnya,inPw: txtNewPW.value },
                        async: false,
                        success:function(response) {
                            alert(response);
                        },
                        error:function(xhr,ajaxOptions, throwError) {
                            alert(throwError);
                        }
                    })
                }
            } else {                
                txtConfirmPW.focus()
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
            },
            error:function(xhr,ajaxOptions, throwError) {
            alert(throwError);
            }
            })
        return hasil.responseText;
    }
</script>
