<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document No.</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_docno" required readonly disabled>
                    <button class="btn btn-primary" onclick="retfg_e_finddoc()"><i class="fas fa-search"></i></button>                    
                    <button class="btn btn-outline-primary" onclick="retfg_e_save()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-primary" onclick="retfg_e_print()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Notice</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_noserahterima" required readonly disabled placeholder="Autonumber">  
                    <select id="retfg_inc_cmb_consignee" class="form-select">
                        <option value="-">-</option>
                    </select>
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
                    <input type="text" class="form-control" id="retfg_inc_cmb_bg_description" required readonly disabled>
                    <input type="hidden" id="retfg_inc_cmb_bg">                    
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Customer</span>                 
                    <select id="retfg_inc_cmb_customer" class="form-select" onchange="retfg_inc_cmb_customer_e_change()">
                        <option value="-">-</option>
                    </select>
                </div>                
            </div>
            <div class="col-md-4 mb-1">               
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Plant</span>
                    <select id="retfg_inc_cmb_loc" class="form-select" >
                        <option value="-">-</option>
                    </select>                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Return From</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_supnm" required readonly disabled>
                    <button class="btn btn-primary" onclick="retfg_e_finsup()"><i class="fas fa-search"></i></button>
                    <input type="hidden" id="retfg_inc_txt_supcd">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >DO. Number</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_supno" required>                    
                </div>
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
                                <label for="retfg_inc_cmb_itemcode" class="col-mb-2 col-form-label">Item Code</label>
                                <div class="col-mb-10">
                                    <select class="form-select" id="retfg_inc_cmb_itemcode">
                                        <option value="-">-</option>                   
                                    </select>
                                </div>
                                    <input type="hidden" id="retfg_inc_itemname">
                                    <input type="hidden" id="retfg_inc_itemunit">
                                    <input type="hidden" id="retfg_inc_item_max_qt">
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="retfg_inc_txt_qty" class="col-mb-2 col-form-label">Qty</label>
                                <div class="col-mb-10">
                                    <input type="text" id="retfg_inc_txt_qty" class="form-control form-control-sm" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="retfg_inc_txt_remark" class="col-mb-2 col-form-label">Remark</label>
                                <div class="col-mb-10">
                                    <input type="text" id="retfg_inc_txt_remark" class="form-control form-control-sm" />
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <div class="input-group input-group-sm mb-1">                    
                                    <span class="input-group-text" >SPQ</span>
                                    <input type="text" class="form-control" id="retfg_inc_txt_spq">                    
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text" >Notice No</span>
                                    <input type="text" class="form-control" id="retfg_inc_txt_notice">
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text" >Category</span>
                                    <select class="form-select" id="retfg_inc_cmb_category">
                                        <option value="-">-</option>
                                        <option value="0">Non Quality Problem</option>
                                        <option value="1">Quality Problem</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <span class="badge bg-info" id="lblinfo_retfg_inc_tbl">-</span>
                            </div>
                            <div class="col-md-6 mb-1 text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-primary" id="retfg_inc_btn_add" onclick="retfg_inc_btn_add_e_click()"><i class="fas fa-plus"></i></button>
                                    <button type="button" class="btn btn-success" id="retfg_inc_btn_print_lbl" onclick="retfg_inc_btn_print_lbl_e_click()" title="Print selected row"><i class="fas fa-print"></i></button>
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
                                                <th>Notice No</th>
                                                <th>Category</th>
                                                <th class="d-none">CategoryID</th>
                                                <th class="text-center"><input id="retfg_inc_checkall" title="select all" type="checkbox" class="form-check-input"></th>
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
<div id="retfg_inc_context_menu">
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
                                <th>Plant</th>
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
<div class="modal fade" id="retfg_MOD_category">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit Category</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Category</span>
                        <select class="form-select" id="retfg_inc_cmb_category_edit">
                            <option value="-">-</option>
                            <option value="0">Non Quality Problem</option>
                            <option value="1">Quality Problem</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">            
            <button type="button" class="btn btn-primary" id="retfg_inc_btn_confirmcategory" onclick="retfg_inc_btn_confirmcategory_e_click()">Confirm</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="retfg_MOD_supplier">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">From List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="col" id="retfg_tblsuplier_div">
                        <table id="retfg_tblsuplier" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
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
    var retfg_context_menu = jSuites.contextmenu(retfg_inc_context_menu, {
        items : [
            {
                title : 'Cancel',
                onclick : function() {
                    retfg_inc_e_cancelitem()
                },
                tooltip : 'Method to cancel transaction per row'
            }
        ],
        onclick : function() {
            retfg_context_menu.close(false)
        }
    })

    function retfg_inc_btn_confirmcategory_e_click(){
        let myrowid = document.getElementById('retfg_inc_g_id').value;
        let thetable = document.getElementById('retfg_inc_tbl');
        let newcat = document.getElementById('retfg_inc_cmb_category_edit');
        let newcattext = newcat.options[newcat.selectedIndex].text;
        let savedid = thetable.rows[myrowid].cells[5].innerText;
        let rano = document.getElementById('retfg_inc_txt_docno').value;
        $("#retfg_MOD_category").modal('hide');  
        thetable.rows[myrowid].cells[8].innerText = newcat.value; 
        thetable.rows[myrowid].cells[7].innerText = newcattext;      
        if(savedid.trim().length==0){
            
        } else {
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/updatecategory')?>",
                data: {indoc: rano, incat: newcat.value, inline: savedid },
                dataType: "json",
                success: function (response) {
                    alertify.message(response.status[0].msg)                                        
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                }
            });
        }
        
    }

    function retfg_inc_btn_print_lbl_e_click(){
        let radoc = document.getElementById('retfg_inc_txt_docno').value;
        let tglserahterima = document.getElementById('retfg_inc_txt_tgl_serahterima').value;
        let mtbl = document.getElementById('retfg_inc_tbl');    
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let dataprint = [];
        let aitemcd = [];
        let aqty = [];
        let aline = [];
        for(let i=0; i< ttlrows; i++){
            if(tableku2.rows[i].cells[9].getElementsByTagName('input')[0].checked){
                if(tableku2.rows[i].cells[5].innerText.trim().length==0){                    
                    alertify.message("Please save the data first");
                    return;
                } else {
                    aitemcd.push(tableku2.rows[i].cells[0].innerText);
                    aqty.push(numeral(tableku2.rows[i].cells[2].innerText).value());
                    aline.push(tableku2.rows[i].cells[5].innerText);                    
                }
            }
        }
        if(aitemcd.length==0){
            alertify.message("Select data first");
            return;
        }
        Cookies.set('PRINTLABEL_FG_SIZE', 'A4', {expires:365});
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setfg_return')?>",
            data: {indate: tglserahterima, indoc: radoc, initemcd: aitemcd, inqty: aqty, inline: aline},
            dataType: "json",
            success: function (response) {
                let ser_serprint = [];
                let ttlrows = response.data.length;
                for(let i=0; i < ttlrows; i++){
                    ser_serprint.push(response.data[i].SER_ID);
                }
                Cookies.set('PRINTLABEL_FGRTN', ser_serprint, {expires:365});
                window.open("<?=base_url('printlabel_fgrtnstatus')?>",'_blank');
            }, error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });
    }

    function retfg_inc_cmb_customer_e_change(){
        let cust = document.getElementById('retfg_inc_cmb_customer').value;
        retfg_inc_e_getlocation(cust);
    }

    function retfg_e_get_consign(){
        let bg = document.getElementById('retfg_inc_cmb_bg').value;
        document.getElementById("retfg_inc_cmb_consignee").innerHTML = '<option value="-">Please wait...</option>';
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/get_consign_history')?>",
            data: {inbg: bg},
            dataType: "json",
            success: function (response) {
                let str = '<option value="-">-</option>';
                let ttlrows = response.data.length;
                for(let i=0; i<ttlrows; i++){
                    str+= '<option value="'+response.data[i].DLV_CONSIGN+'">'+response.data[i].DLV_CONSIGN+'</option>'
                }
                document.getElementById("retfg_inc_cmb_consignee").innerHTML = str;
                let noser = document.getElementById('retfg_inc_txt_noserahterima').value;
                if (noser.length >0){
                    let anoser = noser.split("-");
                    document.getElementById("retfg_inc_cmb_consignee").value = anoser[1];
                }
            }, error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });
    }

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
                    let ttlrows = response.data.length;
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.onclick =  function(){
                            document.getElementById('retfg_inc_txt_noserahterima').value = response.data[i].RETFG_STRDOC; 
                            document.getElementById('retfg_inc_txt_docno').value = response.data[i].STKTRND1_DOCNO;
                            document.getElementById('retfg_inc_cmb_bg').value= response.data[i].STKTRND1_BSGRP;
                            document.getElementById('retfg_inc_cmb_bg_description').value= response.data[i].MBSG_DESC;
                            retfg_inc_e_getcustomer(response.data[i].STKTRND1_BSGRP);
                            $("#retfg_MOD_serahterima").modal('hide');
                            retfg_e_get_consign();
                            retfg_e_get_rtn_fg();
                            retfg_inc_e_getfg();
                        };
                        newText = document.createTextNode(response.data[i].RETFG_STRDOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].RETFG_PLANT);
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
        let cuscd = $("#retfg_inc_cmb_customer").val();
        let plant = $("#retfg_inc_cmb_loc").val();
        let consign = document.getElementById('retfg_inc_cmb_consignee').value;
        let supcd = document.getElementById('retfg_inc_txt_supcd').value;
        let supno = document.getElementById('retfg_inc_txt_supno').value;
        let mtbl = document.getElementById('retfg_inc_tbl');    
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let aitem = [];
        let aqty = [] 
        let aremark = [];
        let arowid = [];
        let anoticeno = [];
        let acategory = [];
        if(consign==='-'){
            alertify.message('Consignee is required');
            return;
        }
        if(internal_docno_date.trim().length==0){
            document.getElementById('retfg_inc_txt_tgl_serahterima').focus();
            alertify.warning('Date required');
            return;
        }
        for(let i=0; i<ttlrows; i++){
            aitem.push(tableku2.rows[i].cells[0].innerText);
            aqty.push(numeral(tableku2.rows[i].cells[2].innerText).value());
            aremark.push(tableku2.rows[i].cells[4].innerText);
            arowid.push(tableku2.rows[i].cells[5].innerText);
            anoticeno.push(tableku2.rows[i].cells[6].innerText);
            acategory.push(tableku2.rows[i].cells[8].innerText);            
        }
        if(confirm("Are you sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/set_rtn_fg')?>",
                data: {indoc: docno, incuscd: cuscd, initem: aitem, inqty: aqty, inremark: aremark, inplant: plant
                ,indocinternal : internal_docno, indocinternaldate: internal_docno_date, inrowid: arowid
                ,inconsign: consign, innotice: anoticeno, incat: acategory
                ,insupcd: supcd, insupno:supno},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1') {
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

    function retfg_e_add_validation(){
        let itemcode_array = retfg_inc_cmb_itemcode.value.split('#')
        let itemcode = itemcode_array[0];
        let maxqty = numeral(document.getElementById('retfg_inc_item_max_qt').value).value();
        let addqty = numeral(retfg_inc_txt_qty.value).value();
        let pushqty = addqty + retfg_e_get_added_qty(itemcode);
        if( pushqty > maxqty){
            alertify.warning('addQTY > retQTY');
            retfg_inc_txt_qty.focus()
        } else {
            document.getElementById('retfg_inc_btn_add').focus();
        }
    }
    function retfg_inc_e_ckall_main(pstate){
        let mtbl = document.getElementById('retfg_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let cktemp ;
        let ttlrows = mrows.length;
        for(let x=0;x<ttlrows;x++){
            cktemp = tableku2.rows[x].cells[9].getElementsByTagName('input')[0];            
            cktemp.checked=pstate;            
        }                        
    }

    function retfg_e_get_rtn_fg(){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        document.getElementById('lblinfo_retfg_inc_tbl').innerText = "Please wait...";
        $("#retfg_inc_tbl tbody").empty();
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
                let mckall = myfrag.getElementById("retfg_inc_checkall");  
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                if(ttlrows > 0) {
                    document.getElementById('retfg_inc_txt_noserahterima').value = response.data[0].RETFG_STRDOC
                    document.getElementById('retfg_inc_txt_tgl_serahterima').value = response.data[0].RETFG_STRDT
                    document.getElementById('retfg_inc_txt_supcd').value = response.data[0].RETFG_SUPCD
                    document.getElementById('retfg_inc_txt_supno').value = response.data[0].RETFG_SUPNO
                    document.getElementById('retfg_inc_txt_supnm').value = response.data[0].MCUS_CUSNM
                    $('#retfg_inc_cmb_customer').val(response.data[0].RETFG_CUSCD);
                    retfg_inc_e_getlocation(response.data[0].RETFG_CUSCD);
                    document.getElementById('retfg_inc_g_id').value = response.data[0].RETFG_PLANT;
                    document.getElementById('retfg_inc_g_id2').value = response.data[0].RETFG_CUSCD;
                    document.getElementById('retfg_inc_cmb_consignee').value = response.data[0].RETFG_CONSIGN;
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.oncontextmenu = function(event){
                            event.preventDefault();                                
                            document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                            retfg_context_menu.open(event)
                            event.preventDefault()
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

                        newcell = newrow.insertCell(6);
                        newcell.contentEditable = true;
                        newText = document.createTextNode(response.data[i].RETFG_NTCNO);
                        newcell.appendChild(newText);
                        let categoryname = '' ;                        
                        switch(response.data[i].RETFG_CAT){
                            case '-':
                                categoryname = '-';break;
                            case '0':
                                categoryname = 'Non Quality Problem';break;
                            case '1':
                                categoryname = 'Quality Problem';break;
                        }                     
                        newcell = newrow.insertCell(7);
                        newcell.onclick = function(event){
                            retfg_e_init_editcategory(response.data[i].RETFG_CAT);
                            document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                        };
                        newText = document.createTextNode(categoryname);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(8);
                        newcell.style.cssText = 'display: none;';
                        newText = document.createTextNode(response.data[i].RETFG_CAT);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(9); 
                        newcell.classList.add("text-center");
                        newText = document.createElement("input");
                        newText.setAttribute('type', 'checkbox');
                        newText.classList.add("form-check-input");
                        newcell.appendChild(newText);
                    }
                } else {
                    document.getElementById('retfg_inc_txt_noserahterima').value='';
                }
                mckall.onclick = function(){retfg_inc_e_ckall_main(mckall.checked)};
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
                            retfg_e_get_consign();
                            $("#retfg_DTLMOD").modal('hide');
                            retfg_e_get_rtn_fg();
                            retfg_inc_e_getfg();
                        };
                        newText = document.createTextNode(response.data[i].STKTRND1_DOCNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].MBSG_DESC);
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

    function retfg_e_init_editcategory(pvalue){
        $("#retfg_MOD_category").modal('show');
        document.getElementById('retfg_inc_cmb_category_edit').value = pvalue;
    }
    function retfg_inc_btn_add_e_click(){
        let itemcode_array = retfg_inc_cmb_itemcode.value.split('#')
        let itemcode = itemcode_array[0];
      
        let qty = numeral(retfg_inc_txt_qty.value).value();      
        let maxqty = numeral(document.getElementById('retfg_inc_item_max_qt').value).value();  
        let remark = retfg_inc_txt_remark.value;
        let itemname = document.getElementById('retfg_inc_itemname').value;
        let itemunit = document.getElementById('retfg_inc_itemunit').value;
        let spq = document.getElementById('retfg_inc_txt_spq').value;
        let txtnotice = document.getElementById('retfg_inc_txt_notice').value;
        let cmbcat = document.getElementById('retfg_inc_cmb_category');
        let cmbcattext = cmbcat.options[cmbcat.selectedIndex].text;

        if(itemcode==""){
            alertify.message('Item Code could not be empty');
            retfg_inc_cmb_itemcode.focus()
            return;
        }
        if(qty<=0){
            alertify.message('qty could not be zero');
            retfg_inc_txt_qty.focus()
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
        let mckall = document.getElementById("retfg_inc_checkall");
        mckall.onclick = function(){retfg_inc_e_ckall_main(mckall.checked)};
        if(spq.trim().length==0 || spq.trim()=='0'){
            newrow = tableku2.insertRow(-1);
            newcell = newrow.insertCell(0);
            newcell.oncontextmenu = function(event){
                                    event.preventDefault();                                
                                    document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                                    retfg_context_menu.open(event)
                                    event.preventDefault()
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
            newcell.style.cssText = 'display: none;';
            newcell.appendChild(newText);

            newcell = newrow.insertCell(6);
            newText = document.createTextNode(txtnotice);            
            newcell.appendChild(newText);

            newcell = newrow.insertCell(7);
            newcell.onclick = function(event){ 
                retfg_e_init_editcategory(cmbcat.value); 
                document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
            };
            newText = document.createTextNode(cmbcattext);            
            newcell.appendChild(newText);
            

            newcell = newrow.insertCell(8);
            newcell.style.cssText = 'display: none;';
            newText = document.createTextNode(cmbcat.value);
            newcell.appendChild(newText);

            newcell = newrow.insertCell(9); 
            newcell.classList.add("text-center");
            newText = document.createElement("input");
            newText.setAttribute('type', 'checkbox');
            newText.classList.add("form-check-input");
            newcell.appendChild(newText);
        } else {
            spq = numeral(spq).value();
            let tempqty = qty;
            let lbl = Math.floor(qty / spq);
            for(let i=0; i<lbl; i++){
                tempqty-= spq;
                newrow = tableku2.insertRow(-1);                                                                
                newcell = newrow.insertCell(0);
                newcell.oncontextmenu = function(event){
                    event.preventDefault();                                
                    document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                    retfg_context_menu.open(event)
                    event.preventDefault()
                };
                newText = document.createTextNode(itemcode);
                newcell.appendChild(newText);
                newcell = newrow.insertCell(1);
                newText = document.createTextNode(itemname);
                newcell.appendChild(newText);
                newcell = newrow.insertCell(2);
                newcell.style.cssText = 'text-align: right;';
                newText = document.createTextNode(numeral(spq).format(','));
                newcell.appendChild(newText);
                newcell = newrow.insertCell(3);
                newText = document.createTextNode(itemunit);
                newcell.appendChild(newText);        
                newcell = newrow.insertCell(4);
                newText = document.createTextNode(remark);
                newcell.appendChild(newText);
                
                newcell = newrow.insertCell(5);
                newText = document.createTextNode('');
                newcell.style.cssText = 'display: none;';
                newcell.appendChild(newText);

                newcell = newrow.insertCell(6);
                newText = document.createTextNode(txtnotice);            
                newcell.appendChild(newText);

                newcell = newrow.insertCell(7);
                newcell.onclick = function(event){ 
                    retfg_e_init_editcategory(cmbcat.value); 
                    document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                };
                newText = document.createTextNode(cmbcattext);
                newcell.appendChild(newText);

                newcell = newrow.insertCell(8);
                newcell.style.cssText = 'display: none;';
                newText = document.createTextNode(cmbcat.value);
                newcell.appendChild(newText);

                newcell = newrow.insertCell(9); 
                newcell.classList.add("text-center");
                newText = document.createElement("input");
                newText.setAttribute('type', 'checkbox');
                newText.classList.add("form-check-input");
                newcell.appendChild(newText);
            }
            if(tempqty > 0){
                newrow = tableku2.insertRow(-1);                                                                
                newcell = newrow.insertCell(0);
                newcell.oncontextmenu = function(event){
                                        event.preventDefault();
                                        document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                                        retfg_context_menu.open(event)
                                        event.preventDefault()
                                    };
                newText = document.createTextNode(itemcode);
                newcell.appendChild(newText);
                newcell = newrow.insertCell(1);
                newText = document.createTextNode(itemname);
                newcell.appendChild(newText);
                newcell = newrow.insertCell(2);
                newcell.style.cssText = 'text-align: right;';
                newText = document.createTextNode(numeral(tempqty).format(','));
                newcell.appendChild(newText);
                newcell = newrow.insertCell(3);
                newText = document.createTextNode(itemunit);
                newcell.appendChild(newText);        
                newcell = newrow.insertCell(4);
                newText = document.createTextNode(remark);
                newcell.appendChild(newText);
                
                newcell = newrow.insertCell(5);
                newText = document.createTextNode('');
                newcell.style.cssText = 'display: none;';
                newcell.appendChild(newText);

                newcell = newrow.insertCell(6);
                newText = document.createTextNode(txtnotice);
                newcell.appendChild(newText);

                newcell = newrow.insertCell(7);
                newcell.onclick = function(event){ 
                    retfg_e_init_editcategory(cmbcat.value); 
                    document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                };
                newText = document.createTextNode(cmbcattext);
                newcell.appendChild(newText);

                newcell = newrow.insertCell(8);
                newcell.style.cssText = 'display: none;';
                newText = document.createTextNode(cmbcat.value);
                newcell.appendChild(newText);

                newcell = newrow.insertCell(9); 
                newcell.classList.add("text-center");
                newText = document.createElement("input");
                newText.setAttribute('type', 'checkbox');
                newText.classList.add("form-check-input");
                newcell.appendChild(newText);
            }
        }
    }

    function retfg_inc_e_cancelitem(){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        let mid = document.getElementById('retfg_inc_g_id').value;
        let thetable = document.getElementById('retfg_inc_tbl');
        let itemcode = thetable.rows[mid].cells[0].innerText; 
        let line = thetable.rows[mid].cells[5].innerText;
        
        if(line!=''){
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/remove_rtn_fg')?>",
                data: {indoc: doc, inline: line},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        thetable.deleteRow(mid);
                        thetablebody = thetable.getElementsByTagName("tbody")[0];
                        if(thetablebody.getElementsByTagName("tr").length==0){
                            document.getElementById("retfg_inc_txt_noserahterima").value='';
                        }
                        alertify.success(response.status[0].msg);
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            thetable.deleteRow(mid);
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
                let str = '<option value="-">Choose</option>';
                let ttlrows = response.length;
                for(let i=0; i< ttlrows; i++){
                    str+= '<option value="'+response[i].id+'">'+response[i].text+'</option>';
                }
                document.getElementById('retfg_inc_cmb_loc').innerHTML = str;                
                $('#retfg_inc_cmb_loc').focus();
                $('#retfg_inc_cmb_loc').val(document.getElementById('retfg_inc_g_id').value);               
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
                let str = '<option value="-">Choose</option>';
                let ttlrows = response.length;
                for(let i=0; i< ttlrows; i++){
                    str+= '<option value="'+response[i].id+'">'+response[i].text+'</option>';
                }
                document.getElementById('retfg_inc_cmb_customer').innerHTML = str;                
                
                let mydes = document.getElementById("retfg_tblsuplier_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("retfg_tblsuplier")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("retfg_tblsuplier")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newrow.onclick = () => {
                        document.getElementById('retfg_inc_txt_supcd').value = response[i].id
                        document.getElementById('retfg_inc_txt_supnm').value = response[i].text
                        $("#retfg_MOD_supplier").modal('hide')
                    }
                    newcell = newrow.insertCell(0)                    
                    newcell.innerHTML = response[i].id
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response[i].text
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function retfg_inc_e_getfg(){
        let doc = document.getElementById('retfg_inc_txt_docno').value;
        $.ajax({
            type: "POST",
            url: "<?=base_url("RCV/get_fg")?>",
            data: {indo: doc },
            dataType: "json",
            success: function (response) {
                let strSel = '<option value="_#_#_#_">-</option>'
                response.forEach((ai) => {
                    strSel += `<option value="${ai['id']+'#'+ai['description']+'#'+ai['um']+'#'+ai['maxqty']}">${ai['text']}</option>`
                })

                retfg_inc_cmb_itemcode.innerHTML = strSel
            }
        });
    }

    function retfg_e_finsup() {
        $("#retfg_MOD_supplier").modal('show')
    }
    

    $('#retfg_inc_cmb_itemcode').select2();

    $('#retfg_inc_cmb_itemcode').on('select2:select', function (e) {        
        let data = e.params.data.id.split("#")
        const _max_qty = numeral(data[3]).value()
        retfg_inc_itemname.value = data[1]
        retfg_inc_itemunit.value = data[2]
        retfg_inc_item_max_qt.value = _max_qty
        retfg_inc_txt_qty.value = _max_qty
        retfg_inc_txt_qty.focus()
    });

</script>