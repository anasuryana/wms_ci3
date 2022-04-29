<style>
    .mycellactive {
        border: 1px solid #00C935;
        border-style:double;
    }
    .mycellactive:hover {
        border: 1px solid #00A22A;
        border-style:double;
    }
    .anastylesel{
        background: red;  
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>
<div style="padding: 10px">    
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <h4 ><span class="badge bg-info">1. <i class="fas fa-hand-point-right" ></i> Select Document of Pending</span> </h4>
            </div>            
        </div>  
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document of Pending</span>                    
                    <input type="text" class="form-control" id="relfg_txt_docpend"  required placeholder="PNDS...">                                   
                    <button class="btn btn-primary btn-sm" id="relfg_finddocpend" ><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary btn-sm" id="relfg_new" onclick="relfg_new_e_click()" ><i class="fas fa-file"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <h4 ><span class="badge bg-info">2. <i class="fas fa-hand-point-right" ></i> Select Reff No</span> </h4>
            </div>            
        </div>
        <div class="row">
            <div class="col">
                <span class="badge bg-info" id="lblinfo_relfg_tblmain"></span>
            </div>
        </div>          
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Reff Number</span>                    
                    <input type="text" class="form-control" id="relfg_txt_reffnopend"  required >                    
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="relfg_divku">
                    <table id="relfg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;font-size:75%">
                        <thead class="table-light">
                            <tr>                                
                                <th rowspan="2" class="align-middle">Reff No</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th rowspan="2" class="align-middle">Job</th>
                                <th colspan="2" class="text-center">Qty</th>                                                                                    
                            </tr>
                            <tr>
                                <th class="text-right">Pend</th>
                                <th class="text-right">Released</th>
                            </tr>
                        </thead>
                        <tbody>                     
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="relfg_hdn_reff">
                <input type="hidden" id="relfg_hdn_itemcd">
                <input type="hidden" id="relfg_hdn_job">
                <input type="hidden" id="relfg_hdn_qty">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <h4 ><span class="badge bg-info">3. <i class="fas fa-hand-point-right" ></i> Split if required</span> </h4>
            </div>            
        </div>
        <div class="d-none" id="relfg_div_ka">            
            <div class="row" >
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>                        
                        <input type="text" class="form-control" id="relfg_txt_itemcode" maxlength="50" required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot No</span>                        
                        <input type="text" class="form-control" id="relfg_txt_lotno" maxlength=50 required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="relfg_txt_qty" maxlength="5" required style="text-align: right">                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff No</span>                        
                        <input type="text" class="form-control" id="relfg_txt_code" maxlength="25" required style="text-align: right">                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
            </div>            
        </div>
        <div id="relfg_div_rnm">            
            <div class="row">
                <div class="col-md-12 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff No</span>                        
                        <input type="text" class="form-control" id="relfg_newreff" required>                   
                    </div>
                </div>                
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <h5>Detail Release</h5>                
                <table id="relfg_tbl_to" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                    <thead class="table-light">
                        <tr>
                            <th  class="align-middle">New Reff No</th>
                            <th  class="align-middle">Reff No [Parent]</th>                            
                            <th  class="align-middle">Assy Code</th>
                            <th  class="align-middle">Lot Number</th>
                            <th  class="text-right">QTY</th>                                    
                            <th  class="d-none">RAWTEXT</th>
                            <th  class="align-middle">...</th>
                            <th  class="d-none">oldjob</th>
                            <th  class="d-none">oldqty</th>
                            <th  >Release</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right">Total</td>
                            <td class="text-right"><span id="relfg_spn_total"></span></td>
                            <td colspan="5"></td>
                        </tr>                        
                    </tfoot>
                </table>                
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <button class="btn btn-primary btn-sm" id="relfg_save" ><i class="fas fa-save"></i></button> 
                <span class="badge bg-info" id="relfg_save_lblinfo"></span>
            </div>            
        </div>
        <div class="row">
            <div class="col">
                <a href="#" id="relfg_show_released">Show released document ?</a>
            </div>
        </div>
        <div class="row">
            <div class="col d-none" id="relfg_div_released">
            <table id="relfg_tbl_released" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                    <thead class="table-light">
                        <tr>
                            <th  class="align-middle">Doc No</th>
                            <th  class="align-middle">Reff No</th>
                            <th  class="align-middle">Assy Code</th>
                            <th  class="align-middle">Lot Number</th>
                            <th  class="text-right">QTY</th>                   
                            <th  class="text-right"></th>                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="relfg_mod_search_docpen">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">              
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Search</label>                        
                        <input type="text" class="form-control" id="relfg_txt_search" onkeypress="relfg_txt_search_e_keypress(event)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                           Search by
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="relfg_rad_do" class="form-check-input" name="optradio" value="doc" checked onclick="relfg_e_rad_click()">
                        <label class="form-check-label" for="relfg_rad_do">
                        Document No
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="relfg_rad_reff" class="form-check-input" name="optradio" value="reffno" onclick="relfg_e_rad_click()">
                        <label class="form-check-label" for="relfg_rad_reff">
                        Reff No
                        </label>
                    </div>
                </div>
            </div>                               
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="lblinfo_relfg_tbldocpend"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="relfg_divku_search">
                    <table id="relfg_tbldocpend" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>Document Number</th>
                                <th>Item Code</th>
                                <th>Job</th>
                                <th>Total Box</th>
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
    function relfg_e_rad_click(){
        document.getElementById('relfg_txt_search').focus();
    }
    $("#relfg_save").click(function (e) { 
        let penddoc = document.getElementById('relfg_txt_docpend').value;        
        
        let aidparent = [];
        let aqty = [];
        let aitemcd = [];
        let ajob = [];
        let aparent_split = [];

        let newid = [];
        let newid_parent = [];
        let newjob = [];        
        let newitem = [];        
        let newqty = [];
        let newqty_parent = [];
        let newlot = [];
        let newrawtext = [];
        let newisrelease = [];
        

        let mtbl = document.getElementById('relfg_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        for(let x=0;x<ttlrows;x++){ 
            if(numeral(tableku2.rows[x].cells[4].innerText).value()>0){
                if(aidparent.length==0){
                    aidparent.push(tableku2.rows[x].cells[0].innerText);
                    aitemcd.push(tableku2.rows[x].cells[1].innerText);
                    aqty.push(numeral(tableku2.rows[x].cells[4].innerText).value());
                    ajob.push(tableku2.rows[x].cells[3].innerText);                         
                    aparent_split.push('n');
                } else {
                    let isfound = false;                
                    for(let i = 0; i< aidparent.length; i++){
                        if(aidparent[i] == tableku2.rows[x].cells[0].innerText) {
                            aqty[i] += numeral(tableku2.rows[x].cells[4].innerText).value();
                            isfound = true;
                            break;
                        }
                    }
                    if(!isfound){
                        aidparent.push(tableku2.rows[x].cells[0].innerText);
                        aitemcd.push(tableku2.rows[x].cells[1].innerText);
                        aqty.push(numeral(tableku2.rows[x].cells[4].innerText).value());
                        ajob.push(tableku2.rows[x].cells[3].innerText);
                        aparent_split.push('n');
                    }
                }
            }
        }
        mtbl = document.getElementById('relfg_tbl_to');
        tableku2 = mtbl.getElementsByTagName("tbody")[0];
        mtbltr = tableku2.getElementsByTagName('tr');
        ttlrows = mtbltr.length; 
        for(let x=0;x<ttlrows;x++){
            newid.push(tableku2.rows[x].cells[0].innerText);
            newid_parent.push(tableku2.rows[x].cells[1].innerText);
            newitem.push(tableku2.rows[x].cells[2].innerText);            
            newqty.push(numeral(tableku2.rows[x].cells[4].innerText).value());
            newqty_parent.push(numeral(tableku2.rows[x].cells[8].innerText).value());            
            newjob.push(tableku2.rows[x].cells[7].innerText);
            newlot.push(tableku2.rows[x].cells[3].innerText);
            newrawtext.push(tableku2.rows[x].cells[5].innerText);
            newisrelease.push(tableku2.rows[x].cells[9].getElementsByTagName('input')[0].checked ? '1' : '0');
            for(let y = 0; y<aidparent.length;y++){
                if(aidparent[y]==tableku2.rows[x].cells[1].innerText){
                    aparent_split[y] = 's';
                }
            }
        }
        let ttldetail = aidparent.length;
        if(ttldetail >0){            
            ttlrows = newid.length;
            let continuu = true;
            for(let x=0;x<ttldetail; x++)  {
                if(aqty[x] >0){
                    for(let h=0;h<ttlrows; h++){
                        if(aidparent[x]==newid_parent[h]){
                            let ttlqtychild = 0;
                            for(let y=0;y<ttlrows;y++){
                                if(newid_parent[y]==newid_parent[h]){
                                    ttlqtychild+= numeral(newqty[y]).value();
                                }                                
                            }
                            if(aqty[x]!=ttlqtychild){
                                continuu=false;
                                alertify.message('total pending for Reff No '+aidparent[x] + ' is not same, '+aqty[x] + ' != '+ttlqtychild );
                                break;
                            }
                        }
                    }
                }
                if (!continuu){
                    break;
                }
            }            
            
            if(continuu){
                let konfir = confirm('Are you sure ?');
                if(!konfir){                    
                    return;
                }
                document.getElementById('relfg_save').disabled=true;
                document.getElementById('relfg_save_lblinfo').innerText = 'Please wait...';
                $.ajax({
                    type: "post",
                    url: "<?=base_url('RLS/save_release')?>",
                    data: {inoldid: aidparent, inolditemcd: aitemcd, inoldqty: aqty, inoldjob: ajob, inoldsplit: aparent_split
                    ,innewid: newid, innewid_parent: newid_parent, innewitem: newitem, innewqty: newqty, innewqty_parent: newqty_parent, innewjob: newjob
                    ,innewlot: newlot, innewrawtxt: newrawtext, innewisrelease: newisrelease
                    ,inpenddoc:  penddoc},
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('relfg_save').disabled=false;
                        document.getElementById('relfg_save_lblinfo').innerText = '';
                        if(response.status[0].cd!='0'){
                            alertify.success(response.status[0].msg);
                            $("#relfg_tbl tbody").empty();
                            $("#relfg_tbl_to tbody").empty();
                        } else {                            
                            alertify.warning(response.status[0].msg);
                        }
                    }, error(xhr, xopt, xthrow){
                        document.getElementById('relfg_save_lblinfo').innerText = xhr.responseText;
                        alertify.error(xthrow);
                    }
                });
            } else {
                
            }
        } else {
            alertify.message('nothing to be procesed');
        }
    });
    function relfg_e_deleterow(pindex){
        let konfr = confirm('Are You sure ?');
        if(konfr){
            let mtbl = document.getElementById('relfg_tbl_to');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            tableku2.deleteRow(pindex);
            let mrows = tableku2.getElementsByTagName("tr");    
            let ttlqty = 0;        
            for(let x=0;x<mrows.length;x++){
                tableku2.rows[x].cells[6].onclick = function(){relfg_e_deleterow(x)};  
                ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();          
            }
            document.getElementById('relfg_spn_total').innerText = numeral(ttlqty).format(',');
        }
    }

    $("#relfg_txt_itemcode").keypress(function (e) { 
        if(e.which==13){
            let olditem = document.getElementById('relfg_hdn_itemcd').value;
            let newitem = document.getElementById('relfg_txt_itemcode').value;
            ///#1 check item code
            if( newitem!=olditem.substr(0,9) ){
                alertify.warning('New Item is not same with old item, please compare the label !');
                document.getElementById('relfg_txt_itemcode').value='';
                return;
            }
            document.getElementById('relfg_txt_lotno').focus();                
            
        }        
    });
    $("#relfg_txt_lotno").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();
            if(cval.trim()==""){
                alertify.warning("Please enter lot no");
            } else {                
                let oldjob = document.getElementById('relfg_hdn_job').value.trim();                                
                let aoldjob = oldjob.split('-');
                let tempoldjob = aoldjob[1];
                if(cval.substr(0,3)=='070'){
                    let tempjob = cval.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4);
                    }                    
                    if(tempoldjob!=tempjob){
                        alertify.warning('Job is not same, please check lot number on the label !');
                        $(this).val('');
                        return;
                    }
                    document.getElementById("relfg_txt_qty").focus();                    
                } else {
                    alertify.warning('Lot Number is not valid');
                    $(this).val('');
                }                
            }
        }
    });
    $("#relfg_txt_qty").keypress(function (e) { 
        if(e.which==13){
            let nreff = document.getElementById('relfg_hdn_reff').value;
            let cval = $(this).val();            
            if(cval.trim()==''){
                alertify.warning("Please enter valid number !");
                $(this).val('');
                return;
            } else {
                if(cval.length<5){
                    cval = numeral(cval).value();                    
                    if(cval>0 ){
                        if(!relfg_e_checkqtytemp(cval)){
                            alertify.warning('new qty >  old qty !');
                            return;
                        }                                                                        
                        document.getElementById('relfg_txt_code').focus();                        
                    } else {
                        document.getElementById("relfg_txt_qty").value="";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    document.getElementById("relfg_txt_qty").value="";
                }                
            }
        }
    });

    $("#relfg_txt_code").keypress(function (e) { 
        if(e.which==13){
            //#1 validateitemcode
            let olditem = document.getElementById('relfg_hdn_itemcd').value;
            let newitem = document.getElementById('relfg_txt_itemcode').value;                                                            

            ///#1 check item code
            if( newitem!=olditem.substr(0,9) ){
                alertify.warning('New Item is not same with old item, please compare the label !');
                document.getElementById('relfg_txt_itemcode').value='';
                return;
            }                
           
            //#1 END

            //#2 validatejob
            let clot = $('#relfg_txt_lotno').val();
            if(clot.trim()==""){
                alertify.warning("Please enter lot no");
            } else {                
                let oldjob = document.getElementById('relfg_hdn_job').value.trim();                                
                let aoldjob = oldjob.split('-');
                let tempoldjob = aoldjob[1];
                if(clot.substr(0,3)=='070'){
                    let tempjob = clot.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4);
                    }                    
                    if(tempoldjob!=tempjob){
                        alertify.warning('Job is not same, please check lot number on the label !');
                        $('#relfg_txt_lotno').val('');
                        return;
                    }
                    document.getElementById("relfg_txt_qty").focus();                    
                } else {
                    alertify.warning('Lot Number is not valid');
                    $('#relfg_txt_lotno').val('');
                }                
            }
            //#2 END
            //#3 validateqty            
            let cqty = $('#relfg_txt_qty').val();            
            if(cqty.trim()==''){
                alertify.warning("Please enter valid number !");
                $('#relfg_txt_qty').val('');
                return;
            } else {
                if(cqty.length<5){
                    cqty = numeral(cqty).value();                    
                    if(cqty>0 ){
                        if(!relfg_e_checkqtytemp(cqty)){
                            alertify.warning('new qty >  old qty !');
                            return;
                        }                                                                        
                        document.getElementById('relfg_txt_code').focus();                        
                    } else {
                        document.getElementById("relfg_txt_qty").value="";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    document.getElementById("relfg_txt_qty").value="";
                }                
            }
            //#3 END


            //#4 validatereff
            let reff_ka =$(this).val();
            if(!relfg_e_checktempkeys(reff_ka)){
                alertify.warning('The label is already added');
                document.getElementById('relfg_txt_code').value='';
                return;
            }
            let oldreff = document.getElementById('relfg_hdn_reff').value;
            if(reff_ka.trim()==''){
                alertify.warning('Reff No could not be empty!');
                return;
            }
            if (reff_ka==oldreff){
                alertify.warning('Reff no could not be same');
                $(this).val('');
                return;
            }
            if(reff_ka.length!=18){
                alertify.warning('Please enter valid Reff No');
                return;
            }
            //#4 END

            
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/validate_newreff')?>",
                data: {inrawtext: reff_ka, initemcd : newitem , inlot: clot , inqty: cqty},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){      
                        let prnt_reff = document.getElementById('relfg_hdn_reff').value;  
                        let mtbl = document.getElementById('relfg_tbl_to');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];                            
                        let newrow, newcell, newText;

                        newrow = tableku2.insertRow(-1);                                    
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.status[0].reffno);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(prnt_reff);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.status[0].assycode);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.status[0].lot);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response.status[0].qty).value());
                        newcell.style.cssText = 'text-align: right;';
                        newcell.appendChild(newText);                                                
                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode('');                            
                        newcell.style.cssText = 'display:none;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newText = document.createElement('I');
                        newText.classList.add("fas", "fa-trash","text-danger");                           
                        newcell.style.cssText = 'text-align: center;cursor:pointer;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);                                                        
                        newText = document.createTextNode(document.getElementById('relfg_hdn_job').value);
                        newcell.style.cssText = 'display:none;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(8);                                                        
                        newText = document.createTextNode(document.getElementById('relfg_hdn_qty').value);
                        newcell.style.cssText = 'display:none;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(9);
                        newText = document.createElement('input');
                        newText.setAttribute('type', 'checkbox');
                        newText.setAttribute('checked', true);
                        newcell.classList.add('text-center');
                        newcell.appendChild(newText);

                        document.getElementById('relfg_txt_itemcode').focus();
                        document.getElementById('relfg_txt_itemcode').value='';
                        document.getElementById('relfg_txt_lotno').value='';
                        document.getElementById('relfg_txt_qty').value='';
                        document.getElementById('relfg_txt_code').value='';                        

                        let mrows = tableku2.getElementsByTagName("tr");
                        let ttlqty = 0;
                        for(let x=0;x<mrows.length;x++){
                            tableku2.rows[x].cells[6].onclick = function(){relfg_e_deleterow(x)};
                            ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();
                        }
                        document.getElementById('relfg_spn_total').innerText = numeral(ttlqty).format(',');
                    } else {
                        alertify.warning(response.status[0].msg);
                        document.getElementById('relfg_txt_code').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    function relfg_e_checktempkeys(reffNo){
        let mtbl = document.getElementById('relfg_tbl_to');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){                    
            if(tableku2.rows[i].cells[0].innerText==reffNo){
                isok = false; break;
            }
        }
        return isok;
    }

    function relfg_e_checktempkeys_parent(reffNo){
        let mtbl = document.getElementById('relfg_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){                    
            if(tableku2.rows[i].cells[0].innerText==reffNo){
                isok = false; break;
            }
        }
        return isok;
    }
    function relfg_e_checkqtytemp(pqty){
        let maxqty =  document.getElementById('relfg_hdn_qty').value;
        let maxREFFNO =  document.getElementById('relfg_hdn_reff').value;
        let mtbl = document.getElementById('relfg_tbl_to');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        ttlrows = mtbltr.length;
       
        let qty = 0;
        for(let i=0;i<ttlrows;i++){ 
            if(maxREFFNO==tableku2.rows[i].cells[1].innerText){
                qty += numeral(tableku2.rows[i].cells[4].innerText).value();                        
            }
        }
        qty += numeral(pqty).value();
        
        let isok = (qty <= maxqty) ? true : false;
        return isok;
    }
    $("#relfg_newreff").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val().toUpperCase();
            let rawtext = nreff;
            let oldreff = document.getElementById('relfg_hdn_reff').value;
            let olditem = document.getElementById('relfg_hdn_itemcd').value;            
            let oldjob = document.getElementById('relfg_hdn_job').value.trim();
            let oldyear = oldjob.substr(0,2);            
            let aoldjob = oldjob.split('-');
            let tempoldjob = aoldjob[1];
            if(oldreff==''){
                document.getElementById('relfg_newreff').value='';
                alertify.message('Please select a Reff No'); return;
            }
            if(nreff.includes("|")){                
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2,ar[0].length-2);
                let newqty = ar[3].substr(2,ar[3].length-2);
                let thelot = ar[2].substr(2,ar[2].length-2);
                let tempjob = thelot.substr(3,5);
                nreff = ar[5].substr(2,ar[5].length-2);

                if(!relfg_e_checktempkeys(nreff)){
                    alertify.warning('The label is already added');
                    document.getElementById('relfg_newreff').value='';
                    return;
                }
                if(numeral(newqty).value()==0){
                    alertify.warning('could not process zero qty');
                    document.getElementById('relfg_newreff').value='';
                    return;
                }
                if(!relfg_e_checkqtytemp(newqty)){
                    alertify.warning('Total Qty > Old QTY !');
                    document.getElementById('relfg_newreff').value='';
                    return;
                }

                if(tempjob.substr(0,1)=='0'){
                    tempjob = tempjob.substr(1,4);
                }  else if( tempjob.substr(0,1)=='C'){
                    alertify.warning('Please split on dedicated menu !');
                    return;
                }
                

                ///#1 check item code
                if( newitem!=olditem.substr(0,9) ){
                    alertify.warning('New Item is not same with old item, please compare the label !');
                    $(this).val('');
                    return;
                }
                

                ///# check job
                if(tempoldjob!=tempjob){
                    alertify.warning('job is not same, please check the label again '+ tempoldjob + ' != ' + tempjob);
                    $(this).val('');
                    return;
                }
                
                if(nreff==oldreff){
                    alertify.warning('could not transfer to the same reff no');
                    $(this).val('');
                    return;
                }
                $(this).val(nreff);
                document.getElementById('relfg_newreff').readOnly=true;
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_newreff')?>",
                    data: {inrawtext: rawtext },
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('relfg_newreff').readOnly=false;
                        if(response.status[0].cd!='0'){                            
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let refno = ar[5].substr(2,ar[5].length-2);
                            let newitem = ar[0].substr(2,ar[0].length-2);
                            let newqty = ar[3].substr(2,ar[3].length-2);
                            let thelot = ar[2].substr(2,ar[2].length-2);                            
                            let mtbl = document.getElementById('relfg_tbl_to');
                            let tableku2 = mtbl.getElementsByTagName("tbody")[0];                            
                            let newrow, newcell, newText;

                            newrow = tableku2.insertRow(-1);                                    
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(refno);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(document.getElementById('relfg_hdn_reff').value);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(document.getElementById('relfg_hdn_itemcd').value);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(thelot);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(newqty).value());
                            newcell.style.cssText = 'text-align: right;';
                            newcell.appendChild(newText);            
                            newcell = newrow.insertCell(5);
                            newText = document.createTextNode(newreff);                            
                            newcell.style.cssText = 'display:none;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);                            
                            newText = document.createElement('I');
                            newText.classList.add("fas", "fa-trash","text-danger");                           
                            newcell.style.cssText = 'text-align: center;cursor:pointer;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(7);                                                        
                            newText = document.createTextNode(document.getElementById('relfg_hdn_job').value);
                            newcell.style.cssText = 'display:none;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(8);                                                        
                            newText = document.createTextNode(document.getElementById('relfg_hdn_qty').value);
                            newcell.style.cssText = 'display:none;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(9);
                            newText = document.createElement('input');
                            newText.setAttribute('type', 'checkbox');
                            newText.setAttribute('checked', true);
                            newcell.classList.add('text-center');
                            newcell.appendChild(newText);

                            document.getElementById('relfg_newreff').value='';

                            let mrows = tableku2.getElementsByTagName("tr");
                            let ttlqty = 0;
                            for(let x=0;x<mrows.length;x++){
                                tableku2.rows[x].cells[6].onclick = function(){relfg_e_deleterow(x)};
                                ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();
                            }
                            document.getElementById('relfg_spn_total').innerText = numeral(ttlqty).format(',');
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('relfg_newreff').value='';
                        }
                    }, error(xhr, xopt, xthrow){
                        document.getElementById('relfg_newreff').readOnly=false;
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('label is not recognized');
            }
        }
    });
    $("#relfg_txt_docpend").keypress(function (e) { 
        let ddoc = document.getElementById('relfg_txt_docpend').value;
        document.getElementById('relfg_txt_docpend').readOnly=true;
        if(e.which==13){
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/checkexist_ser')?>",
                data: {indoc: ddoc},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='0'){
                        alertify.message(response.status[0].msg);
                        document.getElementById('relfg_txt_docpend').readOnly=false;
                    } else {
                        document.getElementById('relfg_txt_reffnopend').focus();

                    }
                }, error(xhr, xopt, xthrow){                    
                    alertify.error(xthrow);
                }
            });
            // relfg_e_loaddoc(ddoc);            
        }
    });
    $("#relfg_divku").css('height', $(window).height()*30/100);
    function relfg_new_e_click(){
        document.getElementById('relfg_txt_docpend').readOnly=false;
        document.getElementById('relfg_save').disabled=false;
        document.getElementById('relfg_txt_docpend').value='';
        document.getElementById('relfg_save_lblinfo').innerText='';

        $("#relfg_tbl tbody").empty();
        $("#relfg_tbl_to tbody").empty();
    }
    $("#relfg_finddocpend").click(function (e) { 
        $("#relfg_mod_search_docpen").modal('show');
        
    });
    $("#relfg_mod_search_docpen").on('shown.bs.modal', function(){
        document.getElementById('relfg_txt_search').focus();
        document.getElementById('relfg_txt_search').select();
    });
    function relfg_txt_search_e_keypress(e){
        if(e.which==13){
            let msearch = document.getElementById('relfg_txt_search').value;
            document.getElementById('lblinfo_relfg_tbldocpend').innerText = 'Please wait...';
            let searchby = document.getElementById('relfg_rad_do').checked ? document.getElementById('relfg_rad_do').value : document.getElementById('relfg_rad_reff').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/searchfg')?>",
                data: {inid: msearch, insearchby: searchby},
                dataType: "json",
                success: function (response) {
                    document.getElementById('lblinfo_relfg_tbldocpend').innerText = '';
                    if(response.status[0].cd!='0'){
                        let ttlrows = response.data.length;
                        document.getElementById('lblinfo_relfg_tbldocpend').innerText=ttlrows+' row(s) found';
                        let mydes = document.getElementById("relfg_divku_search");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("relfg_tbldocpend");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("relfg_tbldocpend");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newrow.style.cssText = 'cursor:pointer';
                            newrow.onclick = function(){ 
                                document.getElementById('relfg_txt_docpend').value=response.data[i].PNDSCN_DOC;
                                document.getElementById('relfg_txt_docpend').readOnly=true;
                                // relfg_e_loaddoc(response.data[i].PNDSCN_DOC);
                                $("#relfg_mod_search_docpen").modal('hide');
                                document.getElementById('relfg_txt_reffnopend').focus();
                                $("#relfg_tbl tbody").empty();
                            };
                            newcell = newrow.insertCell(0);            
                            newText = document.createTextNode(response.data[i].PNDSCN_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].PNDSCN_ITMCD.trim());
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);                            
                            newText = document.createTextNode(response.data[i].PNDSCN_LOTNO.trim());
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);  
                            newcell.classList.add('text-right');
                            newText = document.createTextNode(response.data[i].TTLBOX);
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function relfg_e_loaddoc(pdoc){
        document.getElementById('lblinfo_relfg_tblmain').innerText= 'Please wait...';
        $.ajax({
            type: "get",
            url: "<?=base_url('PND/get_scn_vs_rls')?>",
            data: {indoc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                if(ttlrows==0){document.getElementById('relfg_txt_docpend').value='';}
                document.getElementById('lblinfo_relfg_tblmain').innerText=ttlrows+' row(s) found';
                let mydes = document.getElementById("relfg_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("relfg_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("relfg_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newrow.onclick = function(){
                        document.getElementById('relfg_hdn_reff').value = response.data[i].PNDSCN_SER;
                        document.getElementById('relfg_hdn_itemcd').value = response.data[i].PNDSCN_ITMCD.trim();
                        document.getElementById('relfg_hdn_job').value = response.data[i].PNDSCN_LOTNO;
                        document.getElementById('relfg_hdn_qty').value = numeral(response.data[i].PNDSCN_QTY).value();
                        for (let j = 0; j<ttlrows; j++){
                            tableku2.rows[j].classList.remove('anastylesel');
                        }
                        tableku2.rows[i].classList.add('anastylesel');
                        if(tableku2.rows[i].cells[1].innerText.includes('KD') || tableku2.rows[i].cells[1].innerText.includes('ASP')){
                            document.getElementById('relfg_div_ka').classList.remove('d-none');
                            document.getElementById('relfg_div_rnm').classList.add('d-none');
                            document.getElementById('relfg_txt_itemcode').focus();
                        } else {
                            document.getElementById('relfg_div_rnm').classList.remove('d-none');
                            document.getElementById('relfg_div_ka').classList.add('d-none');
                            document.getElementById('relfg_newreff').focus();
                        }
                    }
                    newrow.style.cssText = 'cursor:pointer';                   
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(response.data[i].PNDSCN_SER);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].PNDSCN_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);                            
                    newText = document.createTextNode(response.data[i].MITM_ITMD1.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);                      
                    newText = document.createTextNode(response.data[i].PNDSCN_LOTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.classList.add('text-right');
                    newText = document.createTextNode(numeral(response.data[i].PNDSCN_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newcell.classList.add('text-right');
                    newText = document.createTextNode(numeral(response.data[i].RLSQTY).format(','));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('relfg_txt_docpend').readOnly = ttlrows > 0 ? true : false;
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#relfg_show_released").click(function (e) { 
        e.preventDefault();
        let pnddoc = document.getElementById('relfg_txt_docpend').value;
        if(pnddoc==''){
            alertify.message('Please select document of pending first');
            return;
        }
        if(document.getElementById('relfg_div_released').classList.contains('d-none')){
            document.getElementById('relfg_div_released').classList.remove('d-none');            
            $.ajax({
                type: "get",
                url: "<?=base_url('RLS/searchby_pnddoc')?>",
                data: {indoc: pnddoc},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        let ttlrows = response.data.length;
                        let mydes = document.getElementById("relfg_div_released");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("relfg_tbl_released");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("relfg_tbl_released");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);  
                            newText = document.createTextNode(response.data[i].RLSSER_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].RLSSER_SER);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);                            
                            newText = document.createTextNode(response.data[i].SER_ITMID);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);                              
                            newText = document.createTextNode(response.data[i].SER_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);                              
                            newText = document.createTextNode(numeral(response.data[i].RLSSER_QTY).format(','));
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newcell.onclick  = function(){
                                let releasedoc = response.data[i].RLSSER_DOC;
                                Cookies.set('CKRLSDOCSER_NO', releasedoc.trim() , {expires:365});
                                window.open("<?=base_url('printreleaseser_doc')?>" ,'_blank');
                            };
                            newText = document.createElement('I');
                            newText.classList.add("fas", "fa-print");                           
                            newcell.style.cssText = 'text-align: center;cursor:pointer;';
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);

                    } else {
                        
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            document.getElementById('relfg_div_released').classList.add('d-none');
            $("#relfg_tbl_released tbody").empty();
        }
    });

    $("#relfg_txt_reffnopend").keypress(function (e) { 
        if(e.which==13){
            let pend_doc = document.getElementById('relfg_txt_docpend').value;
            let reff_doc = document.getElementById('relfg_txt_reffnopend').value;
            if(reff_doc.includes("|")){
                let ar = reff_doc.split("|");                        
                reff_doc = ar[5].substr(2,ar[5].length-2);
            }
            if(!relfg_e_checktempkeys_parent(reff_doc)){
                alertify.warning('Already added');
                document.getElementById('relfg_txt_reffnopend').value='';
                return;
            }
            document.getElementById('relfg_txt_reffnopend').value='';
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/get_scn_vs_rls_with_reffno')?>",
                data: {indoc: pend_doc, inreffno: reff_doc},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        let respndqty = numeral(response.data[0].PNDSCN_QTY).value()-numeral(response.data[0].RLSQTY).value();
                        if(numeral(response.data[0].PNDSCN_QTY).value()==0){
                            alertify.warning("The reff no is already added to a released document");
                            return;
                        } else {
                            if (respndqty==0){
                                alertify.warning("The reff no is also already added to a released document");
                                return;
                            }
                        }
                        let mtbl = document.getElementById('relfg_tbl');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];                            
                        let newrow, newcell, newText;
                        newrow = tableku2.insertRow(-1);
                        let i = tableku2.rows.length-1;
                        newrow.onclick = function(){
                            let ttlrows = tableku2.rows.length;
                            document.getElementById('relfg_hdn_reff').value = response.data[0].PNDSCN_SER;
                            document.getElementById('relfg_hdn_itemcd').value = response.data[0].PNDSCN_ITMCD.trim();
                            document.getElementById('relfg_hdn_job').value = response.data[0].PNDSCN_LOTNO;
                            document.getElementById('relfg_hdn_qty').value = numeral(response.data[0].PNDSCN_QTY).value();
                            for (let j = 0; j<ttlrows; j++){
                                tableku2.rows[j].classList.remove('anastylesel');
                            }
                            tableku2.rows[i].classList.add('anastylesel');
                            if(tableku2.rows[i].cells[1].innerText.includes('KD') || tableku2.rows[i].cells[1].innerText.includes('ASP')){
                                document.getElementById('relfg_div_ka').classList.remove('d-none');
                                document.getElementById('relfg_div_rnm').classList.add('d-none');
                                document.getElementById('relfg_txt_itemcode').focus();
                            } else {
                                document.getElementById('relfg_div_rnm').classList.remove('d-none');
                                document.getElementById('relfg_div_ka').classList.add('d-none');
                                document.getElementById('relfg_newreff').focus();
                            }
                        }
                        
                        newrow.style.cssText = 'cursor:pointer';                   
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(response.data[0].PNDSCN_SER);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[0].PNDSCN_ITMCD.trim());
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);                            
                        newText = document.createTextNode(response.data[0].MITM_ITMD1.trim());
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);                      
                        newText = document.createTextNode(response.data[0].PNDSCN_LOTNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-right');
                        newText = document.createTextNode(numeral(respndqty).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.classList.add('text-right');
                        newText = document.createTextNode(numeral(response.data[0].RLSQTY).format(','));
                        newcell.appendChild(newText);

                    } else {
                        alertify.warning(response.status[0].msg);
                        document.getElementById('relfg_txt_reffnopend').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>
