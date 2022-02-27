<div style="padding: 5px">
	<div class="container-xxl">    
        <div class="row">
            <div class="col-md-3 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h3>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="cvtlabel_oldreff" required>                    
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="cvtlabel_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="cvtlabel_olditemcd" required readonly>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-6 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >SPQ</span>                    
                    <input type="text" class="form-control" id="cvtlabel_spq" required readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="cvtlabel_oldqty" required readonly>
                </div>
            </div>            
        </div>        
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h4>Transaction History</h4>
                <div class="table-responsive" id="cvtlabel_divku">
                    <table id="cvtlabel_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>                               
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Warehouse</th>
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-right">Qty</th>                                                              
                                <th  class="align-middle">Time</th>
                                <th  class="align-middle">PIC</th>
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
                <h3 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">To</span></h3>
            </div>
            <div class="col-md-9 mb-1">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cvtlabel_typefg" id="cvtlabel_typereg" value="" checked>
                    <label class="form-check-label" for="cvtlabel_typereg">Regular</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cvtlabel_typefg" id="cvtlabel_typekd" value="KD">
                    <label class="form-check-label" for="cvtlabel_typekd">KD</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cvtlabel_typefg" id="cvtlabel_typeasp" value="ASP" >
                    <label class="form-check-label" for="cvtlabel_typeasp">ASP</label>
                </div>  
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cvtlabel_typefg" id="cvtlabel_typekdasp" value="KDES" >
                    <label class="form-check-label" for="cvtlabel_typekdasp">KDES</label>
                </div>             
            </div>
        </div>      
        <div id="cvtlabel_div_rnm">
            <div class="row" >               
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Remark</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_newmodel" maxlength="5" required style="text-align: right">                          
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff No</span>                        
                        <input type="text" class="form-control" id="cvtlabel_newreff" required>                   
                        <input type="hidden" id="cvtlabel_rawtxt1">
                    </div>
                </div>                
                <div class="col-md-5 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Item Code</span>                        
                        <input type="text" class="form-control" id="cvtlabel_newitem" required readonly>                        
                    </div>
                </div>
                <div class="col-md-2 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="cvtlabel_newqty" required readonly>
                    </div>
                </div>
            </div>           
        </div>        
        <div class="d-none" id="cvtlabel_div_ka">
            <div class="row" >                
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Remark</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_newmodel_ka" maxlength="5" required style="text-align: right">                         
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_itemcode" maxlength="50" required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot No</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_lotno" maxlength="50" required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_qty" maxlength="5" required style="text-align: right">                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Reff Code</span>                        
                        <input type="text" class="form-control" id="cvtlabel_txt_code" maxlength="500" required style="text-align: right">                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="cvtlabel_btn_new"><i class="fas fa-file"></i></button>
                    <button title="Save" type="button" class="btn btn-outline-primary" id="cvtlabel_btn_save"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    $("#cvtlabel_txt_newmodel_ka").keypress(function (e) { 
        if(e.which==13){
            document.getElementById('cvtlabel_txt_itemcode').focus();
        }
    });
    function cvtlabel_e_clear(){
        document.getElementById('cvtlabel_oldreff').value='';
        document.getElementById('cvtlabel_oldjob').value='';
        document.getElementById('cvtlabel_olditemcd').value='';        
        document.getElementById('cvtlabel_spq').value='';
        document.getElementById('cvtlabel_oldqty').value='';
        document.getElementById('cvtlabel_newreff').value='';        
        document.getElementById('cvtlabel_rawtxt1').value='';        
        
        document.getElementById('cvtlabel_newitem').value='';
        document.getElementById('cvtlabel_newqty').value='';

        document.getElementById('cvtlabel_txt_itemcode').value='';
        document.getElementById('cvtlabel_txt_lotno').value='';
        document.getElementById('cvtlabel_txt_qty').value='';
        document.getElementById('cvtlabel_txt_code').value='';

        $("#cvtlabel_tbl tbody").empty();
    }
    $("#cvtlabel_txt_newmodel").keypress(function (e) { 
        if(e.which==13){
            document.getElementById('cvtlabel_newreff').focus();
        }
    });
    $("#cvtlabel_btn_new").click(function (e) {         
        cvtlabel_e_clear();
        document.getElementById('cvtlabel_oldreff').readOnly=false;
        document.getElementById('cvtlabel_txt_newmodel').readOnly=false;
        document.getElementById('cvtlabel_newreff').readOnly=false;

        document.getElementById('cvtlabel_txt_itemcode').readOnly=false;
        document.getElementById('cvtlabel_txt_lotno').readOnly=false;
        document.getElementById('cvtlabel_txt_qty').readOnly=false;
        document.getElementById('cvtlabel_txt_code').readOnly=false;

        document.getElementById('cvtlabel_btn_save').disabled=false;
        
        document.getElementById('cvtlabel_oldreff').focus();
    });
    function cvtlabel_e_clearinput_to(){
        document.getElementById('cvtlabel_txt_itemcode').value='';
        document.getElementById('cvtlabel_txt_lotno').value='';
        document.getElementById('cvtlabel_txt_qty').value='';
        document.getElementById('cvtlabel_txt_code').value='';             
        document.getElementById('cvtlabel_newreff').value='';
        
        document.getElementById('cvtlabel_newqty').value='';        
        document.getElementById('cvtlabel_rawtxt1').value='';        
        
    }
    $("input[name='cvtlabel_typefg']").change(function(){        
        let curv = $(this).val();
        cvtlabel_e_clearinput_to();
        if(curv=='KD' || curv=='ASP' || curv=='KDES'){
            $("#cvtlabel_div_ka").removeClass("d-none");
            $("#cvtlabel_div_rnm").addClass("d-none");
            document.getElementById("cvtlabel_txt_itemcode").focus();
            document.getElementById("cvtlabel_txt_itemcode").readOnly=false;
            document.getElementById("cvtlabel_txt_lotno").readOnly=false;
            document.getElementById("cvtlabel_txt_qty").readOnly=false;
            document.getElementById("cvtlabel_txt_code").readOnly=false;            
        } else if (curv=='') {
            $("#cvtlabel_div_ka").addClass("d-none");
            $("#cvtlabel_div_rnm").removeClass("d-none");
            document.getElementById('cvtlabel_newreff').focus();
            document.getElementById('cvtlabel_newreff').readOnly=false;
        }
    });
    $("#cvtlabel_oldreff").keypress(function (e) { 
        if(e.which==13){
            let moldreff = $(this).val();
            if(moldreff.trim()==''){
                alertify.warning('Please entry reff no !');
                return;
            }
            if(moldreff.includes("|")){
                let ar = moldreff.split("|");
                moldreff = ar[5].substr(2,ar[5].length-2);
                $(this).val(moldreff);
            }
            document.getElementById('cvtlabel_oldreff').readOnly=true;
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/convert_prep')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){                                                
                        
                        document.getElementById('cvtlabel_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('cvtlabel_olditemcd').value=response.data[0].SER_ITMID.trim().toUpperCase();
                        document.getElementById('cvtlabel_spq').value=numeral(response.data[0].MITM_SPQ).format(',');
                        document.getElementById('cvtlabel_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                        
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("cvtlabel_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("cvtlabel_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("cvtlabel_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){                    
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.tx[i].ITH_FORM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.tx[i].ITH_WH);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.tx[i].ITH_LOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.tx[i].ITH_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(response.tx[i].ITH_QTY).format(','));
                            newcell.style.cssText = 'text-align: right';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newText = document.createTextNode(response.tx[i].ITH_LUPDT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            newText = document.createTextNode(response.tx[i].PIC);
                            newcell.appendChild(newText);
                        }
                        let ilength = response.data[0].SER_ITMID.trim().length;
                        let theremark = response.data[0].SER_ITMID.trim().substr(ilength-2,ilength);
                        if(theremark=='ES'){
                            document.getElementById('cvtlabel_txt_newmodel_ka').value=theremark;
                            document.getElementById('cvtlabel_txt_newmodel').value=theremark;                            
                        } else {
                            document.getElementById('cvtlabel_txt_newmodel_ka').value='';
                            document.getElementById('cvtlabel_txt_newmodel').value='';                            
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                        ///end load               
                        document.getElementById('cvtlabel_newreff').focus();
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('cvtlabel_oldreff').value='';
                        document.getElementById('cvtlabel_oldreff').readOnly=false;
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    $("#cvtlabel_newreff").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val();
            let rawtext = nreff;
            let oldreff = document.getElementById('cvtlabel_oldreff').value;
            let olditem = document.getElementById('cvtlabel_olditemcd').value;
            let oldqty = document.getElementById('cvtlabel_oldqty').value;
            let oldjob = document.getElementById('cvtlabel_oldjob').value.trim();
            let aoldjob = oldjob.split('-');            
            aoldjob = aoldjob[1];
            let remark_nmod = document.getElementById('cvtlabel_txt_newmodel').value;
            let oldyear = oldjob.substr(0,2);
            oldqty = numeral(oldqty).value(); 
            if(remark_nmod.includes("KD")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry KD here');
                document.getElementById('cvtlabel_txt_newmodel').focus();
                return;
            }
            if(remark_nmod.includes("ASP")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry ASP here');
                document.getElementById('cvtlabel_txt_newmodel').focus();
                return;
            }
            if(olditem.includes('ES')){

            } else {                
                if(remark_nmod.includes("ES")){
                    alertify.warning('Could not split from current label to ES');
                    document.getElementById('cvtlabel_txt_newmodel').value='';
                    document.getElementById('cvtlabel_newreff').value='';
                    document.getElementById('cvtlabel_txt_newmodel').focus();
                    return;
                }
                if(remark_nmod.includes("WS")){
                    alertify.warning('Could not split from current label to WS');
                    document.getElementById('cvtlabel_txt_newmodel').value='';
                    document.getElementById('cvtlabel_newreff').value='';
                    document.getElementById('cvtlabel_txt_newmodel').focus();
                    return;
                }
            }
            if(nreff.includes("|")){
                document.getElementById('cvtlabel_rawtxt1').value=nreff;                
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2,ar[0].length-2);
                let newqty = ar[3].substr(2,ar[3].length-2);
                let thelot = ar[2].substr(2,ar[2].length-2);
                let tempjob = thelot.substr(3,5);
                nreff = ar[5].substr(2,ar[5].length-2);
                if(tempjob.substr(0,1)=='0'){
                    tempjob = tempjob.substr(1,4);
                }
                let newjob = tempjob;                            

                ///# check qty
                if(numeral(newqty).value()!= oldqty){
                    alertify.warning('qty must be same !');
                    $(this).val('');
                    return;
                }                

                ///# check job
                if(aoldjob.toLowerCase()!=newjob.toLowerCase()){
                    alertify.warning('job is not same, please check the label again '+ aoldjob + ' != ' + newjob);
                    $(this).val('');
                    return;
                }
                
                if(nreff==oldreff){
                    alertify.warning('could not convert to the same reff no');
                    $(this).val('');
                    return;
                }
                $(this).val(nreff);
                document.getElementById('cvtlabel_newreff').readOnly=true; 
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/convert_validate_newlabel')?>",
                    data: {inrawtext: rawtext, inremark : remark_nmod },
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){                            
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let newitem = ar[0].substr(2,ar[0].length-2);
                            let newqty = ar[3].substr(2,ar[3].length-2); 
                            let remark_nmod = document.getElementById('cvtlabel_txt_newmodel').value;  
                            document.getElementById('cvtlabel_newitem').value=newitem+remark_nmod;
                            document.getElementById('cvtlabel_newqty').value=newqty;
                            document.getElementById('cvtlabel_btn_save').focus();
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('cvtlabel_newreff').value='';
                            document.getElementById('cvtlabel_newreff').readOnly=false; 
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('label is not recognized');
                document.getElementById('cvtlabel_newreff').value='';
            }
        }
    });
    $("#cvtlabel_btn_save").click(function (e) { 
        let oldreff = document.getElementById('cvtlabel_oldreff').value;
        let oldjob = document.getElementById('cvtlabel_oldjob').value;
        let olditemcd = document.getElementById('cvtlabel_olditemcd').value;
        let oldqty = document.getElementById('cvtlabel_oldqty').value;

        let rg_reff = document.getElementById('cvtlabel_newreff').value;
        let rg_itemcd = document.getElementById('cvtlabel_newitem').value;
        let rg_remark = document.getElementById('cvtlabel_txt_newmodel').value;
        let rg_rawtxt = document.getElementById('cvtlabel_rawtxt1').value;
        

        let ka_itemcd = document.getElementById('cvtlabel_txt_itemcode').value;
        let ka_lotno = document.getElementById('cvtlabel_txt_lotno').value;
        let ka_qty = document.getElementById('cvtlabel_txt_qty').value;
        let ka_reff = document.getElementById('cvtlabel_txt_code').value;
        let ka_remark = document.getElementById('cvtlabel_txt_newmodel_ka').value;
        let fgtype  = $("input[name='cvtlabel_typefg']:checked").val();

        document.getElementById('cvtlabel_btn_save').disabled=true;
        if(document.getElementById('cvtlabel_oldreff').readOnly){   
            if(document.getElementById('cvtlabel_newreff').readOnly ||
            document.getElementById('cvtlabel_txt_code').readOnly) {
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/convertlabel_save')?>",
                    data: {inoldreff : oldreff, inoldjob: oldjob, inolditemcd: olditemcd, inoldqty: oldqty
                    , inrg_reff : rg_reff, inrg_itemcd: rg_itemcd, inrg_rawtxt: rg_rawtxt, inrg_remark : rg_remark
                    , inka_itmcd: ka_itemcd, inka_lotno: ka_lotno, inka_reff: ka_reff, inka_remark: ka_remark
                    , infgtype: fgtype},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            alertify.success(response.status[0].msg);
                            cvtlabel_e_clear();
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }     
        }
        // alertify.message('UNDER CONSTRUCTION, progress 70%. We will tell after it is done');
    });
    $("#cvtlabel_txt_itemcode").keypress(function (e) { 
        if(e.which==13){
            let itemcd = $(this).val();
            let olditem = document.getElementById('cvtlabel_olditemcd').value;            
            let remark1 = document.getElementById('cvtlabel_txt_newmodel_ka').value;
            let m_kdasp = $("input[name='cvtlabel_typefg']:checked").val();
            if(itemcd.trim()==''){
                alertify.warning('Item Code could not be empty');                
                return;
            }
            if(remark1.includes("KD")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry KD here');
                document.getElementById('cvtlabel_txt_newmodel_ka').focus();
                return;
            }
            if(remark1.includes("ASP")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry ASP here');
                document.getElementById('cvtlabel_txt_newmodel_ka').focus();
                return;
            }
            if(olditem.includes('ES')){

            } else {                
                if(remark1.toUpperCase().includes("ES")){
                    alertify.warning('Could not split from current label to ES');
                    document.getElementById('cvtlabel_txt_newmodel_ka').value='';
                    document.getElementById('cvtlabel_txt_itemcode').value='';
                    document.getElementById('cvtlabel_txt_newmodel_ka').focus();
                    return;
                }
                if(remark1.toUpperCase().includes("WS")){
                    alertify.warning('Could not split from current label to WS');
                    document.getElementById('cvtlabel_txt_newmodel_ka').value='';
                    document.getElementById('cvtlabel_txt_itemcode').value='';
                    document.getElementById('cvtlabel_txt_newmodel_ka').focus();
                    return;
                }
            }
            itemcd += m_kdasp;
            itemcd += remark1;
            document.getElementById('cvtlabel_txt_itemcode').value=document.getElementById('cvtlabel_txt_itemcode').value.trim();
            document.getElementById('cvtlabel_txt_itemcode').readOnly=true;
            $.ajax({
                type: "get",
                url: "<?=base_url("MSTITM/checkexist")?>",
                data: {initem: itemcd},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd=="11"){                                 
                        document.getElementById("cvtlabel_txt_lotno").focus();                                                
                    } else {
                        document.getElementById("cvtlabel_txt_itemcode").value="";
                        alertify.warning(response.data[0].msg);    
                        document.getElementById('cvtlabel_txt_itemcode').readOnly=false;                    
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#cvtlabel_txt_lotno").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();
            if(cval.trim()==""){
                alertify.warning("Please enter lot no");
            } else {
                let newitem = document.getElementById('cvtlabel_txt_itemcode').value;
                let oldjob = document.getElementById('cvtlabel_oldjob').value.trim();
                let aoldjob = oldjob.split('-');
                aoldjob = aoldjob[1];                
                let oldyear = oldjob.substr(0,2);                
                if(cval.substr(0,3)=='070'){
                    let tempjob = cval.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4);
                    }
                    let newjob = tempjob;
                    if(aoldjob!=newjob){
                        alertify.warning('Job is not same, please check lot number on the label !, '+oldjob+' != '+newjob);
                        $(this).val('');
                        return;
                    }
                    document.getElementById("cvtlabel_txt_qty").focus();
                    document.getElementById("cvtlabel_txt_lotno").readOnly=true;
                } else {
                    alertify.warning('Lot Number is not valid');
                    $(this).val('');
                }                
            }
        }
    });
    $("#cvtlabel_txt_qty").keypress(function (e) { 
        if(e.which==13){
            let mqty = $(this).val();
            let oldqty = document.getElementById('cvtlabel_oldqty').value;
            if(mqty.trim()==''){
                alertify.message('QTY could not empty');
                return;
            }
            mqty = numeral(mqty).value();
            oldqty = numeral(oldqty).value();
            if(mqty==oldqty){
                document.getElementById('cvtlabel_txt_qty').readOnly = true;
                document.getElementById('cvtlabel_txt_code').focus();
            } else {
                alertify.warning('qty must be same');$(this).val('');
            }
        }
    });
    $("#cvtlabel_txt_code").keypress(function (e) { 
        if(e.which==13){
            let olditem = document.getElementById('cvtlabel_olditemcd').value;
            let m_kdasp = $("input[name='cvtlabel_typefg']:checked").val();
            let remark1 = document.getElementById('cvtlabel_txt_newmodel_ka').value;
            let reff_ka = $(this).val();
            let oldreff = document.getElementById('cvtlabel_oldreff').value;
            if(reff_ka.trim()==''){
                alertify.warning('Reff No could not be empty!');
                return;
            }
            if (reff_ka==oldreff){
                alertify.warning('Reff no could not be same');
                $(this).val('');
                return;
            }
            if(reff_ka.includes('|')){
                const akurkey = reff_ka.split("|")
                document.getElementById('cvtlabel_txt_itemcode').value = akurkey[0].substr(2,9)
                document.getElementById('cvtlabel_txt_lotno').value = akurkey[2].substr(2,akurkey[2].length)
                document.getElementById('cvtlabel_txt_qty').value = akurkey[3].substr(2,akurkey[3].length)
                document.getElementById('cvtlabel_txt_code').value = akurkey[5].substr(2,akurkey[5].length)
                reff_ka= akurkey[5].substr(2,akurkey[5].length)
            }
            if(reff_ka.length==18 || reff_ka.length==16){
                

                document.getElementById('cvtlabel_txt_code').readOnly=true;
                olditem=olditem.substr(0,9);
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_newreff')?>",
                    data: {inrawtext: reff_ka, initemcd: olditem,  intypefg: m_kdasp , inremark:remark1 },
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){                            
                            document.getElementById('cvtlabel_btn_save').focus();
                            
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('cvtlabel_txt_code').value='';
                            document.getElementById('cvtlabel_txt_code').readOnly=false;
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                })
            } else {
                alertify.warning('Reff no is not valid')
                $(this).val('')
                return
            }
            
        }
    });
</script>

