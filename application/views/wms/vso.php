<div style="padding: 10px">
	<div class="container-fluid">        
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="so_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="so_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>                                        
                    <button title="Import Template data to System" class="btn btn-outline-success" id="so_btn_import"><i class="fas fa-file-import"></i></button>
                </div>
            </div>
        </div>   
        <div class="row">
            <input type="hidden" id="so_isedit" value="n">            
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="so_sel_bg" data-style="btn-info" onchange="so_e_onchange_bg()">
                        <option value="-">-</option>
                        <?php
                        foreach($lbg as $r){
                            ?>
                            <option value="<?=trim($r->MBSG_BSGRP)?>"><?=$r->MBSG_DESC?></option>
                            <?php
                        }
                        ?>
                    </select>        
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Customer</span>            
                    <select class="form-select" id="so_sel_cus" data-style="btn-info">
                        <option value="-">-</option>                        
                    </select> 
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >SO No</span>                    
                    <input type="text" class="form-control" id="so_txt_doc" required >                    
                    <button class="btn btn-primary" id="so_btn_finddoc"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12 mb-1 p-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text">Consignee</span>                    
                    <select id="so_consignee"	class="form-select">
                        <option value="-">-</option>
                        <?php
                        foreach($ldeliverycode as $r){
                            ?>
                            <option value="<?=trim($r->MDEL_DELCD)?>"><?=$r->MDEL_DELCD?></option>
                            <?php
                        }
                        ?> 
                    </select>                                                                                                              
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6 mb-1">
                <span class="badge bg-info" id="so_lblinfomain"></span>
            </div>
            <div class="col-md-6 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="so_btnplus"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="so_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="so_divku" onpaste="so_e_pastecol1(event)">
                    <table id="so_tbl_main" class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item Code</th>
                                <th title="Format: yyyy-mm-dd">Order Date</th>
                                <th>Qty</th>                 
                                <th title="Format: yyyy-mm-dd">Delivery Schedule Date</th>
                                <th class="d-none"></th>
                            </tr>                        
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>                                
                                <td class="d-none"></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>           
        </div>
    </div>
</div>
<div class="modal fade" id="so_IMPORTDATA">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Updating from xls</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group">                        
                        <button title="Download a Template File (*.xls File)" id="so_btn_download" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>                                                
                        <input type="file" id="so_xlf_new"  class="form-control">                                                                            
                        <button id="so_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>                    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">                
                    <div class="progress">
                        <div id="so_lblsaveprogress" class="progress-bar progress-bar-success progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only">0</span>
                        </div>
                    </div>
                </div>                
            </div>
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="so_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Sales Order List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="so_txtsearch" onkeypress="so_e_search(event)" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="so_itm_srchby" onchange="so_e_fokus()" class="form-select">
                            <option value="doc">SO No</option>
                            <option value="cs">Customer</option>                            
                        </select>                  
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="so_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="so_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Sales Order No</th>
                                    <th class="d-none">Customer ID</th>
                                    <th>Customer</th>
                                    <th class="d-none">BG</th>
                                    <th>Business Group</th>
                                    <th>Consignee</th>
                                    <th>Order Date</th>
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
</div>
<script>
    var so_selected_row = ''
    var so_selected_col = 0
    $("#so_btnsave").click(function (e) {
        let mbg = document.getElementById('so_sel_bg').value;
        let mcust = document.getElementById('so_sel_cus').value;
        let mdoc = document.getElementById('so_txt_doc').value;
        let mconsig = document.getElementById('so_consignee').value;

        if(mbg.trim()=='-'){
            document.getElementById('so_sel_bg').focus();
            alertify.message('Please select a business group');
            return;
        }
        if(mcust.trim()=='-'){
            document.getElementById('so_sel_cus').focus();
            alertify.message('Please select a customer');
            return;
        }
        if(mdoc.trim()=='-'){
            document.getElementById('so_txt_doc').focus();
            alertify.message('SO No could not be empty');
            return;
        }
        if(mconsig.trim()=='-'){
            document.getElementById('so_consignee').focus();
            alertify.message('Please select a consignee');
            return;
        }
        let tabel_PLOT = document.getElementById("so_tbl_main");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length
        if(ttlrows>0){
            let aitem =[];
            let aord_dt =[];
            let aord_qty =[];
            let adel_sch_dt=[];
            let aline =[];           
            for(let i=0;i<ttlrows;i++){                
                let citem = tabel_PLOT_body0.rows[i].cells[0].innerText.trim()                
                let corderdate = tabel_PLOT_body0.rows[i].cells[1].innerText.trim();
                let cqty= tabel_PLOT_body0.rows[i].cells[2].innerText.trim();
                let cdelsch = tabel_PLOT_body0.rows[i].cells[3].innerText.trim();
                let cline = tabel_PLOT_body0.rows[i].cells[4].innerText.trim();
                if(citem.trim()!=''){
                    if(corderdate.includes('-') && corderdate.length==10){
                        if(cdelsch.includes('-') && cdelsch.length==10){
                            aitem.push(citem);
                            aord_dt.push(corderdate);
                            aord_qty.push(numeral(cqty).value());
                            adel_sch_dt.push(cdelsch);
                            aline.push(cline);
                        } else {
                            alertify.message('invalid delivery schedule date , line '+i);
                            return;
                        }
                    } else {
                        alertify.message('invalid order date , line '+i);
                        return;
                    }
                }
            }
            if(aitem.length>0){
                if(confirm("Are you sure want to save ?")){
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('SO/set')?>",
                        data: {inbg: mbg, incust: mcust, indoc: mdoc, inconsig: mconsig
                        , ina_item : aitem, ina_ord_dt: aord_dt, ina_ord_qty: aord_qty
                        , ina_sch_dt: adel_sch_dt, ina_line: aline},
                        dataType: "json",
                        success: function (response) {
                            let mbg = document.getElementById('so_sel_bg').value;
                            let mcust = document.getElementById('so_sel_cus').value;
                            let mdoc = document.getElementById('so_txt_doc').value;
                            let mconsig = document.getElementById('so_consignee').value;
                            alertify.message(response.status[0].msg);
                            so_getsodetail(mdoc,mbg, mcust,mconsig)
                        }, error:function(xhr,xopt,xthrow){
                            alertify.error(xthrow);
                        }
                    });
                }
            }
        }
    })

    function so_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html')
        const mcona_tbllength = document.getElementById('so_tbl_main').getElementsByTagName('tbody')[0].rows.length
        const columnLength = document.getElementById('so_tbl_main').getElementsByTagName('tbody')[0].rows[0].cells.length        
        if(datapas===""){
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++
                }
            }
            let table = $(`#so_tbl_main tbody`)
            let incr = 0
            if ((mcona_tbllength-so_selected_row)<ttlrowspasted) {       
                const needRows = ttlrowspasted - (mcona_tbllength-so_selected_row)
                for (let i = 0;i<needRows;i++) {
                    so_addrow(mcona_selected_table)
                }
            }            
            for(let i=0;i<ttlrowspasted;i++){                
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length                
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){             
                    table.find('tr').eq((i+so_selected_row)).find('td').eq((k+so_selected_col)).text(mcol[k].trim())
                }                
            }                            
        } else {            
            let tmpdom = document.createElement('html')
            tmpdom.innerHTML = datapas
            let myhead = tmpdom.getElementsByTagName('head')[0]
            let myscript = myhead.getElementsByTagName('script')[0]
            let mybody = tmpdom.getElementsByTagName('body')[0]
            let mytable = mybody.getElementsByTagName('table')[0]
            let mytbody = mytable.getElementsByTagName('tbody')[0]
            let mytrlength = mytbody.getElementsByTagName('tr').length
            let table = $(`#so_tbl_main tbody`)
            let incr = 0
            let startin = 0
            
            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin = 3
            }
            if((mcona_tbllength-so_selected_row)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (mcona_tbllength-so_selected_row);                
                for(let i = 0;i<needRows;i++){
                    so_addrow('so_tbl_main');
                }
            }
            
            let b = 0
            for(let i=startin;i<(mytrlength);i++){
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                    table.find('tr').eq((b+so_selected_row)).find('td').eq((k+so_selected_col)).text(dkol.trim())
                } 
                b++
            }
        }
        event.preventDefault()
    }

    function so_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onkeyup = (event) => {
                                    if(event.keyCode===9) { so_selected_col++ }
                                }
        newrow.onclick = (event) => {so_tbl_tbody_tr_eC(event)}
        newcell = newrow.insertCell(0);
        newText = document.createTextNode(String.fromCharCode(160));
        newcell.appendChild(newText);
        newcell.contentEditable = true;
        newcell = newrow.insertCell(1);        
        newcell.contentEditable = true;
        newcell = newrow.insertCell(2);        
        newcell.contentEditable = true;
        newcell.classList.add('text-right')
        newcell = newrow.insertCell(3);                
        newcell.contentEditable = true;
        newcell = newrow.insertCell(4);                
        newcell.classList.add('d-none')
    }

    function so_tbl_tbody_tr_eC(e){
        so_selected_row = e.srcElement.parentElement.rowIndex - 1
        so_selected_col = e.srcElement.cellIndex
    }

    $("#so_btnplus").click(function (e) { 
        so_addrow('so_tbl_main') 
        let mytbody = document.getElementById('so_tbl_main').getElementsByTagName('tbody')[0]
        so_selected_row = mytbody.rows.length - 1
        so_selected_col = 1
    });

    $("#so_btnmins").click(function (e) {       
        if(confirm('Are You sure ?')){
            let tabel_PLOT = document.getElementById("so_tbl_main");                    
            let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
            let mline = tabel_PLOT_body0.rows[so_selected_row].cells[4].innerText;        
            if(mline.trim()==''){
                tabel_PLOT_body0.deleteRow(so_selected_row);                
                alertify.message('Deleted');
            } else {                
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SO/remove')?>",
                    data: {inline: mline},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            let tabel_PLOT = document.getElementById("so_tbl_main");                    
                            let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];                                                       
                            tabel_PLOT_body0.deleteRow(so_selected_row);
                            alertify.success(response.status[0].msg);
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });                
            }
        } 
    });    
    function so_e_fokus(){
        document.getElementById('so_txtsearch').focus();
    }

    function so_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('so_txtsearch').value;
            let msearchby = document.getElementById('so_itm_srchby').value;
            $("#so_tblsaved tbody").empty();
            document.getElementById('so_lblinfo').innerText ="Please wait..";
            $.ajax({
                type: "get",
                url: "<?=base_url('SO/search')?>",
                data: {insearch: msearch, insearchby: msearchby },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        let ttlrows = response.data.length;
                        document.getElementById('so_lblinfo').innerText =ttlrows+" row(s) found";
                        let tabel_PLOT = document.getElementById("so_tblsaved");                    
                        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                        for(let i =0; i< ttlrows; i++){
                            newrow = tabel_PLOT_body0.insertRow(-1);
                            newrow.onclick = function(){so_getsodetail(response.data[i].SO_NO, response.data[i].SO_BG, response.data[i].SO_CUSCD, response.data[i].SO_DELCD)};
                            newcell = newrow.insertCell(0);
                            newcell.innerHTML = response.data[i].SO_NO                            
                            newcell = newrow.insertCell(1);
                            newcell.innerHTML = response.data[i].SO_CUSCD
                            newcell.classList.add('d-none');                            
                            newcell = newrow.insertCell(2);
                            newcell.innerHTML = response.data[i].MCUS_CUSNM                            
                            newcell = newrow.insertCell(3);
                            newcell.innerHTML = response.data[i].SO_BG
                            newcell.classList.add('d-none');                            
                            newcell = newrow.insertCell(4);
                            newcell.innerHTML = response.data[i].MBSG_DESC                            
                            newcell = newrow.insertCell(5);
                            newcell.innerHTML = response.data[i].SO_DELCD                            
                            newcell = newrow.insertCell(6);
                            newcell.innerHTML =  response.data[i].SO_ORDRDT                            
                        }
                    } else {
                        document.getElementById('so_lblinfo').innerText ="";
                        alertify.message(response.status[0].msg);
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function so_selectcustomer(pbg, pcust){
        $.ajax({
            type: "get",
            url: "<?=base_url('SO/xget_customer_so')?>",
            data: {inbg : pbg},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let strtodis  = '<option value="-">Choose</option>';
                    for(let i=0;i<response.data.length; i++){
                        
                    strtodis += '<option value="'+response.data[i].SSO2_CUSCD.trim()+'">'+response.data[i].MCUS_CUSNM.trim()+'</option>';                        
                    }
                    document.getElementById('so_sel_cus').innerHTML = strtodis;
                    document.getElementById('so_sel_cus').value=pcust;
                } else {
                    document.getElementById('so_sel_cus').innerHTML = '<option value="-">-</option>';
                    alertify.message(response.status[0].msg);
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function so_getsodetail(pso,pbg, pcust,pconsig){
        document.getElementById('so_lblinfomain').innerText='Please wait...';
        document.getElementById('so_txt_doc').value=pso;
        document.getElementById('so_sel_bg').value=pbg; 
        document.getElementById('so_consignee').value=pconsig; 

        so_selectcustomer(pbg,pcust);
        $("#so_MOD").modal('hide');
        $.ajax({
            type: "get",
            url: "<?=base_url('SO/searchsocontent')?>",
            data: {inso: pso},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let ttlrows = response.data.length;
                    document.getElementById('so_lblinfomain').innerText=ttlrows+' row(s) found';
                    let tabel_PLOT = document.getElementById("so_tbl_main");
                    let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                    tabel_PLOT_body0.innerHTML ='';
                    for(let i =0; i< ttlrows; i++){
                        newrow = tabel_PLOT_body0.insertRow(-1);
                        newrow.onkeyup = (event) => {
                                    if(event.keyCode===9) { so_selected_col++ }
                                }
                        newrow.onclick = (event) => {so_tbl_tbody_tr_eC(event)}
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].SO_ITEMCD);
                        newcell.appendChild(newText);
                        newcell.contentEditable = true;
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].SO_ORDRDT);                        
                        newcell.appendChild(newText);
                        newcell.contentEditable = true;
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(numeral(response.data[i].SO_ORDRQT).format(','));  
                        newcell.appendChild(newText);
                        newcell.contentEditable = true;
                        newcell.style.cssText = 'text-align:right';
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].SO_DELSCH);
                        newcell.appendChild(newText);
                        newcell.contentEditable = true;
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].SO_LINE);
                        newcell.appendChild(newText);
                        newcell.style.cssText='display:none';
                    }
                } else {
                    alertify.message('data not found, hmmm');
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#so_MOD").on('shown.bs.modal', function(){
        document.getElementById('so_txtsearch').focus();
    });
    $("#so_btn_finddoc").click(function (e) { 
        $("#so_MOD").modal('show');
    });
    function so_e_onchange_bg(){
        let bg = document.getElementById('so_sel_bg').value;
        document.getElementById('so_sel_cus').innerHTML = '<option value="-">Please wait</option>';
        $.ajax({
            type: "get",
            url: "<?=base_url('SO/xget_customer_so')?>",
            data: {inbg : bg},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let strtodis  = '<option value="-">Choose</option>';
                    for(let i=0;i<response.data.length; i++){
                        
                    strtodis += '<option value="'+response.data[i].SSO2_CUSCD.trim()+'">'+response.data[i].MCUS_CUSNM.trim()+'</option>';                        
                    }
                    document.getElementById('so_sel_cus').innerHTML = strtodis;
                } else {
                    document.getElementById('so_sel_cus').innerHTML = '<option value="-">-</option>';
                    alertify.message(response.status[0].msg);
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#so_btn_import").click(function (e) { 
        let mbg = document.getElementById('so_sel_bg').value;
        let mcus = document.getElementById('so_sel_cus').value;
        let mconsig = document.getElementById('so_consignee').value;
        if(mbg=='-'){
            alertify.warning('Please select business group');
            document.getElementById('so_sel_bg').focus();
            return;
        }
        if(mcus=='-'){
            alertify.warning('Please select customer');
            document.getElementById('so_sel_cus').focus();
            return;
        }
        if(mconsig=='-'){
            alertify.warning('Please select consignee');
            document.getElementById('so_consignee').focus();
            return;
        }
        $("#so_IMPORTDATA").modal('show');
    });

    $("#so_IMPORTDATA").on('shown.bs.modal', function(){
        document.getElementById('so_lblsaveprogress').style.width = "0%";
        document.getElementById('so_lblsaveprogress').innerText = "0%";
        document.getElementById('so_xlf_new').value="";
    });

    $("#so_btnnew").click(function (e) { 
        document.getElementById('so_tbl_main').getElementsByTagName('tbody')[0].innerHTML = ''        
        document.getElementById('so_txt_doc').value='';
    });

    $("#so_btn_download").click(function (e) { 
        $.ajax({
            type: "get",
            url: "<?=base_url('SO/getlinkitemtemplate')?>",
            dataType: "text",                       
            success:function(response) {          
                window.open(response, '_blank');
                alertify.message("<i>Start downloading...</i>");
            },
            error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });        
    });



    $("#so_btn_startimport").click(function (e) {        
        if (document.getElementById('so_xlf_new').files.length==0){
            alert('please select file to upload');
        } else {			
            var fileUpload = $("#so_xlf_new")[0]; 
            //Validate whether File is valid Excel file.
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {                
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            console.log('saya perambaan selain IE');
                            reader.onload = function (e) {
                                so_ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString(fileUpload.files[0]);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                        data += String.fromCharCode(bytes[i]);
                                }
                                so_ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer(fileUpload.files[0]);
                        }
                    } else {
                            alert("This browser does not support HTML5.");
                    }
            } else {
                    alert("Please upload a valid Excel file.");
            }
        }
    });

    var so_ttlxls = 0;
    var so_ttlxls_savd = 0;

    function so_ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];			
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		
		        
        //Add the data rows from Excel file.
        so_ttlxls =excelRows.length;       
        so_ttlxls_savd=0;
        let mbg = document.getElementById('so_sel_bg').value;
        let mcust = document.getElementById('so_sel_cus').value;
        let mconsign = document.getElementById('so_consignee').value;
        for (let i = 0; i < excelRows.length; i++) {
            let mso      = excelRows[i].SO_NUMBER;
            let mitemcd    = excelRows[i].ITEMCODE;       
            let morderdate    = excelRows[i].ORDER_DATE;
            let morderqty    = excelRows[i].ORDER_QTY;
            let mdelschedule    = excelRows[i].DLV_SCHEDULE;
                        
            $.ajax({
                type: "post",
                url: "<?=base_url('SO/import')?>",
                data: {inso: mso, initemcode: mitemcd, inorderdate: morderdate
                ,inqty : morderqty, indelsch: mdelschedule, inbg: mbg, incust: mcust, inconsig: mconsign
                ,inrowid: i},
                dataType: "json",
                success: function (response) {
                    so_ttlxls_savd++;
                    let dis = parseInt(((so_ttlxls_savd)/so_ttlxls)*100) + "%";
                    document.getElementById('so_lblsaveprogress').style.width = dis;
                    document.getElementById('so_lblsaveprogress').innerText = dis;                                       
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });            
        }         
	};
</script>