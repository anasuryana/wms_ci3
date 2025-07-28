<div style="padding: 10px">
	<div class="container-fluid">         
        <div class="row">
            <div class="col-md-12 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Scan Sub-Assy</span></h3>
            </div>            
        </div>             
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Sub Assy ID</span>
                    <input type="text" class="form-control" id="rcvfgmultilayer_txt_code" onkeypress="rcvfgmultilayer_txt_code_e_keypress(event)" placeholder="scan it here..." required style="text-align:center">                      
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgmultilayer_divsubku">
                    <table id="rcvfgmultilayer_tblsub" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Doc No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Qty</th>
                                <th>Unit Measure</th>
                                <th>ID</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <h3 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Scan Assy</span></h3>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Assy ID</span>
                    <input type="text" class="form-control" id="rcvfgmultilayer_txt_assycode" onkeypress="rcvfgmultilayer_txt_assycode_e_keypress(event)" maxlength="200" placeholder="scan it here..." required style="text-align:center">
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgmultilayer_divku">
                    <table id="rcvfgmultilayer_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Qty</th>
                                <th>Unit Measure</th>
                                <th>User</th>
                                <th>FG Uniquecode</th>
                                <th>Remark</th>
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
<div class="modal fade" id="rcvfgmultilayer_REMARK">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Remark</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text"><i class="fas fa-comments"></i></label>                        
                        <input type="text" class="form-control" id="rcvfgmultilayer_txt_remark" >
                        <input type="hidden" id="rcvfgmultilayer_txt_reff">
                    </div>
                </div>                
            </div>          
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="rcvfgmultilayer_btnsave"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>            
        </div>
      </div>
    </div>
</div>
<div id="rcvfgmultilayer_context_menu"></div>
<input type="hidden" id="rcvfgmultilayer_g_id">
<script>

    var rcvfgmultilayer_rtn_context_menu = jSuites.contextmenu(rcvfgmultilayer_context_menu, {
        items : [
            {
                title : 'Cancel',
                onclick : function() {
                    rcvfgmultilayer_e_cancelitem()
                },
                tooltip : 'Method to cancel transaction per row'
            }
        ],
        onclick : function() {
            rcvfgmultilayer_rtn_context_menu.close(false)
        }
    })
    function rcvfgmultilayer_e_cancelitem(){
        let mid = document.getElementById('rcvfgmultilayer_g_id').value;
        document.getElementById('rcvfgmultilayer_tblsub').deleteRow(mid);
        document.getElementById('rcvfgmultilayer_txt_code').focus();
    }

    function rcvfgmultilayer_e_bothsame(){
        let status = true;
        let mtbl = document.getElementById('rcvfgmultilayer_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let newrow, newcell, newText;
        let ttlrows = tableku2.getElementsByTagName('tr').length;
        let arows = [];
        for(let i=0;i<ttlrows;i++){
            let isfound = false;
            for(let k in arows){
                if(arows[k].itemcode===tableku2.rows[i].cells[1].innerText){
                    isfound = true;
                    break;
                }
            }
            if(!isfound) {
                arows.push({itemcode: tableku2.rows[i].cells[1].innerText, qty: 0});
            }
        }
        for(let k in arows){
            for(let i=0;i<ttlrows;i++){
                if(arows[k].itemcode===tableku2.rows[i].cells[1].innerText){
                    arows[k].qty += numeral(tableku2.rows[i].cells[3].innerText).value();
                }
            }
        }
        if(arows.length>1){
            let befqty = '';
            for(let k in arows){
                befqty = arows[k].qty;break;
            }
            for(let k in arows){
                if(befqty != arows[k].qty){
                    status = false;
                }
            }
        } else {
            status = false;
        }
        return status;
    }

    function rcvfgmultilayer_txt_assycode_e_keypress(e){
        if(e.which==13){
            let aitemcodeDistinct = [];
            let aitemcode = [];
            let aitemJob = [];
            let aqty = [];
            let auniqueCode = [];
            let mtbl = document.getElementById('rcvfgmultilayer_tblsub');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let txt_subassy = document.getElementById('rcvfgmultilayer_txt_code');
            let txt_assy = document.getElementById('rcvfgmultilayer_txt_assycode');
            for(let i=0;i<ttlrows;i++){
                let stemp = tableku2.rows[i].cells[1].innerText;
                aitemcode.push(stemp); 
                aitemJob.push(tableku2.rows[i].cells[0].innerText);
                if(!aitemcodeDistinct.includes(stemp)){
                    aitemcodeDistinct.push(stemp); 
                }
                aqty.push(numeral(tableku2.rows[i].cells[3].innerText).value());
                auniqueCode.push(tableku2.rows[i].cells[5].innerText);
            }
            let aitemcodeCount = aitemcodeDistinct.length;
            if(aitemcodeCount==0){
                alertify.warning("Please scan/add sub assy first");
                txt_subassy.focus();                
            } else if (aitemcodeCount==1){
                txt_subassy.focus();
                txt_assy.value = '';
                alertify.message('at least 2 sub assy required');                
            } else if (aitemcodeCount>=2){
                let mval = txt_assy.value.trim();
                let therealcode = '';
                if(mval.indexOf("|")>-1){
                    let aval = mval.split("|");
                    if (aval.length>5){
                        therealcode = aval[5];
                        therealcode = therealcode.substr(2,therealcode.length);                        
                    } else {
                        alertify.warning("the serial is also not valid");
                    }                    
                } else {
                    therealcode = mval.trim();
                }
                if(therealcode.length==16){
                    if(!rcvfgmultilayer_e_bothsame()){
                        alertify.warning("QTY MUST BE SAME");
                        txt_assy.value = '';
                        return;
                    }                   
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('INCFG/setml')?>",
                        data: {initmcd: aitemcode, initmjob: aitemJob, inuniq: auniqueCode, inqty: aqty
                        ,inuniqassy:therealcode },
                        dataType: "json",
                        success: function (response) {
                            document.getElementById('rcvfgmultilayer_txt_assycode').value='';
                            rcvfgmultilayer_evt_gettodayscan();
                            if(response.status[0].cd==1){
                                rcvfgmultilayer_e_clear();
                                document.getElementById('rcvfgmultilayer_txt_code').focus();                            
                                alertify.success(response.status[0].msg);
                            } else {
                                alertify.warning(response.status[0].msg);
                            }
                        }, error: function(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                            document.getElementById('rcvfgmultilayer_txt_assycode').value='';
                        }
                    });
                } else {
                    alertify.warning('Assy ID is not valid {'+therealcode+'}');
                }                
            }
        }
    }

    function rcvfgmultilayer_e_clear(){
        $("#rcvfgmultilayer_tblsub tbody").empty();
    }

    function rcvfgmultilayer_e_checktempkeys(reffNo){
        let mtbl = document.getElementById('rcvfgmultilayer_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){                    
            if(tableku2.rows[i].cells[5].innerText==reffNo){
                isok = false; break;
            }
        }
        return isok;
    }
    function rcvfgmultilayer_e_checktempbyitemcode(pitemcode){
        let mtbl = document.getElementById('rcvfgmultilayer_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){                    
            if(tableku2.rows[i].cells[1].innerText==pitemcode){
                isok = false; break;
            }
        }
        return isok;
    }

    function rcvfgmultilayer_txt_code_e_keypress(e){
        if(e.which==13){
            let midsub = document.getElementById('rcvfgmultilayer_txt_code').value.trim();
            document.getElementById('rcvfgmultilayer_txt_code').value= "";
            if(midsub.length>9){
                let therealcode = '';
                if(midsub.indexOf("|")>-1){
                    let aid = midsub.split("|");                    
                    if (aid.length>5){
                        therealcode = aid[5];
                        therealcode = therealcode.substr(2,therealcode.length);                        
                    } else {
                        alertify.warning('the data is not valid,');                        
                        return;
                    }
                } else {
                    therealcode = midsub;
                }
                if(!rcvfgmultilayer_e_checktempkeys(therealcode)){
                    alertify.warning("Already added, just check the table below");
                    return;
                }
                if(therealcode.substr(0,1)!='3'){
                    alertify.warning('it was not valid ID sub assy');                    
                    return;
                }
                $.ajax({
                    type: "get",
                    url: "<?=base_url('INCFG/checksubassy')?>",
                    data: {inid: therealcode},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==1){
                            // if(!rcvfgmultilayer_e_checktempbyitemcode(response.data[0].SER_ITMID)){
                            //     alertify.warning("Already added, just check the table below...");
                            //     return;
                            // }
                            let mtbl = document.getElementById('rcvfgmultilayer_tblsub');
                            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                            let newrow, newcell, newText;
                            let ttlrows = tableku2.getElementsByTagName('tr').length;
                            if(ttlrows>0){
                                let ok = true;
                                for(let i=0;i < ttlrows; i++){
                                    if(numeral(tableku2.rows[i].cells[3].innerText).value()!=numeral(response.data[0].SER_QTY).value()){
                                        ok=false;
                                        break;                                        
                                    }
                                }
                                // if(!ok){
                                //     alertify.warning('QTY must be same');
                                //     return;
                                // }
                            }
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.data[0].SER_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[0].SER_ITMID);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data[0].MITM_ITMD1);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newcell.style.cssText = 'text-align: right;';
                            newText = document.createTextNode(numeral(response.data[0].SER_QTY).format(','));
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(response.data[0].MITM_STKUOM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newcell.oncontextmenu = function(event){
                                event.preventDefault();
                                document.getElementById('rcvfgmultilayer_g_id').value = event.target.parentElement.rowIndex;
                                rcvfgmultilayer_rtn_context_menu.open(event)
                                event.preventDefault()
                            };
                            newText = document.createTextNode(response.data[0].SER_ID);
                            newcell.appendChild(newText);
                            ttlrows = tableku2.getElementsByTagName('tr').length;
                            if(rcvfgmultilayer_e_bothsame()){
                                document.getElementById('rcvfgmultilayer_txt_assycode').focus();
                            }
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }
    $("#rcvfgmultilayer_divku").css('height', $(window).height()*65/100); 
    $("#rcvfgmultilayer_REMARK").on('shown.bs.modal', function(){
        $("#rcvfgmultilayer_txt_remark").focus();
        document.getElementById('rcvfgmultilayer_txt_remark').select();
    });
    $("#rcvfgmultilayer_btnsave").click(function (e) { 
        let reffno = document.getElementById('rcvfgmultilayer_txt_reff').value;
        let rmrk = document.getElementById('rcvfgmultilayer_txt_remark').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setremark')?>",
            data: {inid: reffno, inrmrk: rmrk},
            dataType: "json",
            success: function (response) {
                rcvfgmultilayer_evt_gettodayscan();
                $("#rcvfgmultilayer_REMARK").modal('hide');
                document.getElementById('rcvfgmultilayer_txt_remark').value='';
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.warning(response.status[0].msg);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });        
    });   
   
    rcvfgmultilayer_evt_gettodayscan();
    function rcvfgmultilayer_evt_gettodayscan(){       
        $.ajax({
            type: "get",
            url: "<?=base_url('INCFG/gettodayscanqa')?>",            
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfgmultilayer_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfgmultilayer_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfgmultilayer_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let msts='';
                for (let i = 0; i<ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode((i+1));            
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].ITH_DOC);
                    newcell.appendChild(newText);        
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].ITH_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: left';
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';
                    
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_STKUOM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].MSTEMP_FNM);
                    newcell.appendChild(newText);                                        
                    newcell = newrow.insertCell(7);
                    newcell.style = 'cursor:pointer';
                    newText = document.createTextNode(response.data[i].ITH_SER);
                    newcell.appendChild(newText);                                        
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].SER_RMRK);
                    newcell.appendChild(newText);                                        
                }
                let mrows = tableku2.getElementsByTagName("tr");
                for(let x=0;x<mrows.length;x++){
                    tableku2.rows[x].cells[7].onclick = function(){rcvfgmultilayer_e_remark(tableku2.rows[x],x,7)};
                }    
                      
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function rcvfgmultilayer_e_remark(prow, pirow ,pikol){
        let tcell = prow.getElementsByTagName("td")[pikol];
        let tcellremark = prow.getElementsByTagName("td")[pikol+1];
        let curval  = tcell.innerText;
        document.getElementById('rcvfgmultilayer_txt_remark').value=tcellremark.innerText;
        document.getElementById('rcvfgmultilayer_txt_reff').value=curval;
        $("#rcvfgmultilayer_REMARK").modal('show');
    }    
</script>