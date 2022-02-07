<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document No.</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_docno" required readonly>
                    <button class="btn btn-primary" onclick="retfg_e_finddoc()"><i class="fas fa-search"></i></button>                    
                    <button class="btn btn-outline-primary" onclick="retfg_e_save()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-primary" onclick="retfg_e_print()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >No</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_noserahterima" required readonly placeholder="Autonumber">  
                    <button class="btn btn-primary" onclick="retfg_e_finddoc_serahterima()"><i class="fas fa-search"></i></button>                                      
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="retfg_inc_txt_tgl_serahterima" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Business Group</span>
                    <input type="text" class="form-control" id="retfg_inc_cmb_bg_description" required readonly>
                    <input type="hidden" id="retfg_inc_cmb_bg">                    
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <input id="retfg_inc_cmb_customer" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_customer")?>', label: 'Customer', editable: false
                    , onSelect: function(p1){ 
                        retfg_inc_e_getfg(p1.id);
                        retfg_inc_e_getlocation(p1.id);
                    }">
            </div>
            <div class="col-md-4 mb-1">
                <input id="retfg_inc_cmb_loc" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_strlocation")?>', label: 'Plant', editable: false
                    ">
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="card">
                <div class="card-header">
                    Item Detail
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <input id="retfg_inc_cmb_itemcode" class="easyui-combobox" name="dept" style="width:100%;" 
                                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_fg")?>', label: 'Item Code :', limitToList: true
                                    , onSelect: function(p1){                        
                                        document.getElementById('retfg_inc_itemname').value = p1.description;
                                        document.getElementById('retfg_inc_itemunit').value = p1.um;
                                        document.getElementById('retfg_inc_item_max_qt').value = p1.maxqty;
                                        $('#retfg_inc_txt_qty').numberbox('setValue', p1.maxqty);
                                        $('#retfg_inc_txt_qty').numberbox('textbox').focus(); 
                                    }">
                                    <input type="hidden" id="retfg_inc_itemname">
                                    <input type="hidden" id="retfg_inc_itemunit">
                                    <input type="hidden" id="retfg_inc_item_max_qt">
                            </div>
                            <div class="col-md-4 mb-1">
                                <input id="retfg_inc_txt_qty" value="0" style="text-align: right;">
                            </div>
                            <div class="col-md-4 mb-1">
                                <input id="retfg_inc_txt_remark" label="Remark" style="width:100%;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <span class="badge bg-info" id="lblinfo_retfg_inc_tbl"></span>
                            </div>
                            <div class="col-md-6 mb-1 text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-primary" id="retfg_inc_btn_add" onclick="retfg_inc_btn_add_e_click()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="table-responsive" id="retfg_inc_divku">
                                    <table id="retfg_inc_tbl" class="table table-striped table-bordered table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>                                                 
                                                <th >Item Id</th>
                                                <th >Item Name</th>
                                                <th class="text-end">Qty</th>
                                                <th title="Unite Measurement">UM</th>                                                
                                                <th >Remark</th>                                                
                                                <th class="d-none">idrow</th>
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
        </div>        
    </div>
</div>
<div id="retfg_inc_context_menu" class="easyui-menu" style="width:120px;">         
    <div data-options="iconCls:'icon-cancel'" onclick="retfg_inc_e_cancelitem()">Cancel</div>     
</div>
<input type="hidden" id="retfg_inc_g_id">
<input type="hidden" id="retfg_inc_g_id2">
<div class="modal fade" id="retfg_DTLMOD">
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
                        <input type="text" class="form-control" id="retfg_txt_search" title="press enter to start searching" onkeypress="retfg_e_mod_searchingdoc(event)">
                    </div>
                </div>
            </div>                                
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="lblinfo_retfg_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="retfg_divku_search">
                    <table id="retfg_tbldono" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>DO No</th>
                                <th>Business Group</th>                                
                                <th class="text-center">HSCODE</th>
                                <th class="text-end">BM</th>
                                <th class="text-end">PPN</th>
                                <th class="text-end">PPH</th>
                                <th class="d-none">bg</th>
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
<div class="modal fade" id="retfg_MOD_serahterima">
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
                        <input type="text" class="form-control" id="retfg_txt_search_serahterima" title="press enter to start searching" onkeypress="retfg_e_mod_searching_serahterima_doc(event)">
                    </div>
                </div>
            </div>                                
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="lblinfo_retfg_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="retfg_divku_search_serahterima">
                    <table id="retfg_tbldono_serahterima" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>Serah Terima Doc.</th>
                                <th>Business Group</th>                                
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

    $("#retfg_MOD_serahterima").on('shown.bs.modal', function(){
        $("#retfg_txt_search_serahterima").focus();
    });

    function retfg_e_mod_searching_serahterima_doc(e){
        if(e.which==13){
            let keys = document.getElementById('retfg_txt_search_serahterima').value;
            $("#retfg_tbldono_serahterima tbody").empty();
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/get_h_serah_terima_doc')?>",
                data: {insearch: keys },
                dataType: "json",
                success: function (response) {
                    let mydes = document.getElementById("retfg_divku_search_serahterima");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("retfg_tbldono_serahterima");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("retfg_tbldono_serahterima");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            });
        }
    }

    function retfg_e_finddoc_serahterima(){
        $("#retfg_MOD_serahterima").modal('show');
    }
    
    function retfg_e_print(){
        let radoc = document.getElementById('retfg_inc_txt_docno').value;
        let internaldoc = document.getElementById('retfg_inc_txt_noserahterima').value;
        if(internaldoc.trim().length==0){
            document.getElementById('retfg_inc_txt_noserahterima').focus();            
        } else {
            Cookies.set('CKPSI_RADOC', radoc, {expires:365});
            window.open("<?=base_url('printserah_terima_return_doc')?>",'_blank');
        }
    }

    $("#retfg_inc_txt_tgl_serahterima").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    function retfg_e_save(){
        let docno = document.getElementById('retfg_inc_txt_docno').value;
        let internal_docno = document.getElementById('retfg_inc_txt_noserahterima').value;
        let internal_docno_date = document.getElementById('retfg_inc_txt_tgl_serahterima').value;
        let cuscd = $("#retfg_inc_cmb_customer").combobox('getValue');
        let plant = $("#retfg_inc_cmb_loc").combobox('getValue');
        let mtbl = document.getElementById('retfg_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let aitem = [];
        let aqty = [] 
        let aremark = [];
        let arowid = [];
        if(internal_docno_date.trim().length==0){
            document.getElementById('retfg_inc_txt_tgl_serahterima').focus();
            alertify.warning('Date required');
            return;
        }
        for(let i=0; i<ttlrows; i++){
            aitem.push(tableku2.rows[i].cells[0].innerText);
            aqty.push(tableku2.rows[i].cells[2].innerText);
            aremark.push(tableku2.rows[i].cells[4].innerText);
            arowid.push(tableku2.rows[i].cells[5].innerText);
        }
        if(confirm("Are you sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/set_rtn_fg')?>",
                data: {indoc: docno, incuscd: cuscd, initem: aitem, inqty: aqty, inremark: aremark, inplant: plant
                ,indocinternal : internal_docno, indocinternaldate: internal_docno_date, inrowid: arowid},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.success(response.status[0].msg);
                        retfg_e_get_rtn_fg();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            });            
        }
    }
    $("#retfg_DTLMOD").on('shown.bs.modal', function(){
        $("#retfg_txt_search").focus();
    });
    function retfg_e_finddoc(){
        $("#retfg_DTLMOD").modal('show');
    }
    $('#retfg_inc_txt_remark').textbox({
        label:'Remark'        
    });
    var retfg_inc_txt_remark = $('#retfg_inc_txt_remark');
	retfg_inc_txt_remark.textbox('textbox').bind('keypress', function(e){
	    if (e.keyCode == 13){
            retfg_e_add_validation();
	    }
	});    
	
    $('#retfg_inc_txt_qty').numberbox({
        label:'QTY',
        precision: 0,
        groupSeparator: ',',
        width: '100%'    
    });
    var retfg_inc_txt_qty = $('#retfg_inc_txt_qty');
	retfg_inc_txt_qty.numberbox('textbox').bind('keypress', function(e){
	    if (e.keyCode == 13){
            $('#retfg_inc_txt_remark').textbox('textbox').focus();
	    }
	});	

    function retfg_e_add_validation(){
        let itemcode = $('#retfg_inc_cmb_itemcode').combobox('getValue');
        let maxqty = numeral(document.getElementById('retfg_inc_item_max_qt').value).value();
        let addqty = numeral($('#retfg_inc_txt_qty').numberbox('getValue')).value();
        let pushqty = addqty + retfg_e_get_added_qty(itemcode);
        if( pushqty > maxqty){
            alertify.warning('addQTY > retQTY');
            $('#retfg_inc_txt_qty').numberbox('textbox').focus();
        } else {
            document.getElementById('retfg_inc_btn_add').focus();
        }
    }

    function retfg_e_get_rtn_fg(){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        document.getElementById('lblinfo_retfg_inc_tbl').innerText = "Please wait...";
        $("#retfg_tbldono tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/get_rtn_fg')?>",
            data: {indo: doc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById('lblinfo_retfg_inc_tbl').innerText = ttlrows + " row(s) found";
                let mydes = document.getElementById("retfg_inc_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("retfg_inc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("retfg_inc_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                if(ttlrows > 0) {
                    document.getElementById('retfg_inc_txt_noserahterima').value = response.data[0].RETFG_STRDOC;
                    document.getElementById('retfg_inc_txt_tgl_serahterima').value = response.data[0].RETFG_STRDT;                                        
                    $('#retfg_inc_cmb_customer').combobox('setValue', response.data[0].RETFG_CUSCD);
                    document.getElementById('retfg_inc_g_id').value = response.data[0].RETFG_PLANT;
                    document.getElementById('retfg_inc_g_id2').value = response.data[0].RETFG_CUSCD;
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.oncontextmenu = function(event){
                                event.preventDefault();                                
                                document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                                $('#retfg_inc_context_menu').menu('show', {
                                    left: event.pageX,
                                    top: event.pageY
                                });
                            };
                        newText = document.createTextNode(response.data[i].MITM_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].MITM_ITMD1);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);                        
                        newcell.style.cssText = "text-align: right";
                        newText = document.createTextNode(response.data[i].RETFG_QTY);
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].MITM_STKUOM);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.contentEditable = true;
                        newText = document.createTextNode(response.data[i].RETFG_RMRK);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.style.cssText = "display: none";
                        newText = document.createTextNode(response.data[i].RETFG_LINE);
                        newcell.appendChild(newText);
                    }
                } else {
                    document.getElementById('retfg_inc_txt_noserahterima').value='';
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });
    }

    function retfg_e_mod_searchingdoc(e){
        if(e.which==13){
            let txtsearch = document.getElementById('retfg_txt_search').value;
            document.getElementById('lblinfo_retfg_tbldono').innerText = "Please wait...";
            $("#retfg_tbldono tbody").empty();
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/MGGetDOReturn_status')?>",
                data: {indo: txtsearch},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('lblinfo_retfg_tbldono').innerText = ttlrows+" row(s) found";
                    let mydes = document.getElementById("retfg_divku_search");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("retfg_tbldono");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    let tabell = myfrag.getElementById("retfg_tbldono");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tmpnomor = '';
                    let mnomor =0;                    
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.onclick = function(){
                            document.getElementById('retfg_inc_txt_docno').value = response.data[i].STKTRND1_DOCNO;
                            document.getElementById('retfg_inc_cmb_bg').value= response.data[i].MBSG_BSGRP;
                            document.getElementById('retfg_inc_cmb_bg_description').value= response.data[i].MBSG_DESC;
                            retfg_inc_e_getcustomer(response.data[i].MBSG_BSGRP);
                            $("#retfg_DTLMOD").modal('hide');
                            retfg_e_get_rtn_fg();
                        };
                        newText = document.createTextNode(response.data[i].STKTRND1_DOCNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].MBSG_DESC);
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].RCV_HSCD);
                        newcell.style.cssText= "white-space: nowrap";
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].RCV_BM);
                        newcell.style.cssText= "white-space: nowrap";
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].RCV_PPN);
                        newcell.style.cssText= "white-space: nowrap";
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(response.data[i].RCV_PPH);
                        newcell.style.cssText= "white-space: nowrap";
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(response.data[i].MBSG_BSGRP);
                        newcell.style.cssText= "display: none";
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            });
        }
    }

    function retfg_e_get_added_qty(pitem){        
        let mtbl = document.getElementById('retfg_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let added_Qty = 0;
        for(let i=0;i<ttlrows;i++){
            if(tableku2.rows[i].cells[0].innerText==pitem){
                added_Qty += numeral(tableku2.rows[i].cells[2].innerText).value();
            }
        }
        return added_Qty;
    }
    function retfg_inc_btn_add_e_click(){
        let itemcode = $('#retfg_inc_cmb_itemcode').combobox('getValue');
        let qty = numeral($('#retfg_inc_txt_qty').numberbox('getValue')).value();      
        let maxqty = numeral(document.getElementById('retfg_inc_item_max_qt').value).value();  
        let remark = $('#retfg_inc_txt_remark').textbox('getValue');
        let itemname = document.getElementById('retfg_inc_itemname').value;
        let itemunit = document.getElementById('retfg_inc_itemunit').value;
        if(itemcode==""){
            alertify.message('Item Code could not be empty');
            $('#retfg_inc_cmb_itemcode').combobox('textbox').focus();
            return;
        }
        if(qty<=0){
            alertify.message('qty could not be zero');
            $('#retfg_inc_txt_qty').numberbox('textbox').focus();
            return;
        }
        let pushqty = qty + retfg_e_get_added_qty(itemcode);        
        if( pushqty > maxqty ){
            alertify.warning("addQTY > retQTY...");
            return;
        }
        let mtbl = document.getElementById('retfg_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = mtbl.getElementsByTagName('tr');
        let newrow, newcell, newText;

        newrow = tableku2.insertRow(-1);                                                                
        newcell = newrow.insertCell(0);
        newcell.oncontextmenu = function(event){
                                event.preventDefault();                                
                                document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                                $('#retfg_inc_context_menu').menu('show', {
                                    left: event.pageX,
                                    top: event.pageY
                                });
                            };
        newText = document.createTextNode(itemcode);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(1);
        newText = document.createTextNode(itemname);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(2);
        newcell.style.cssText = 'text-align: right;';
        newText = document.createTextNode(numeral(qty).format(','));
        newcell.appendChild(newText);
        newcell = newrow.insertCell(3);
        newText = document.createTextNode(itemunit);
        newcell.appendChild(newText);        
        newcell = newrow.insertCell(4);
        newText = document.createTextNode(remark);
        newcell.appendChild(newText);
        
        newcell = newrow.insertCell(5);
        newText = document.createTextNode('');
        newcell.appendChild(newText);
        newcell.style.cssText = 'display: none;';
    }

    function retfg_inc_e_cancelitem(){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        let mid = document.getElementById('retfg_inc_g_id').value;
        let thetable = document.getElementById('retfg_inc_tbl');
        let itemcode = thetable.rows[mid].cells[0].innerText; 
        let line = thetable.rows[mid].cells[5].innerText;
        thetable.deleteRow(mid);
        if(line!=''){
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/remove_rtn_fg')?>",
                data: {indoc: doc, inline: line},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg);
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function retfg_inc_e_getlocation(pcust){
        let bg = document.getElementById('retfg_inc_cmb_bg').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_strlocation')?>",
            data: {inbg: bg, incust: pcust },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_loc').combobox('loadData', response); 
                $('#retfg_inc_cmb_loc').combobox('clear'); 
                $('#retfg_inc_cmb_loc').combobox('textbox').focus();
                $('#retfg_inc_cmb_loc').combobox('setValue', document.getElementById('retfg_inc_g_id').value); 
                if( document.getElementById('retfg_inc_g_id2').value!=""){
                    $('#retfg_inc_cmb_customer').combobox('setValue', document.getElementById('retfg_inc_g_id2').value);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function retfg_inc_e_getcustomer(pbg){
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_customer')?>",
            data: {inbg: pbg },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_customer').combobox('loadData', response); 
                $('#retfg_inc_cmb_customer').combobox('clear'); 
                $('#retfg_inc_cmb_customer').combobox('textbox').focus(); 
                 
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function retfg_inc_e_getfg(pcust){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_fg')?>",
            data: {indo: doc },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_itemcode').combobox('loadData', response); 
                $('#retfg_inc_cmb_itemcode').combobox('clear'); 
                $('#retfg_inc_cmb_itemcode').combobox('textbox').focus(); 
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
</script>