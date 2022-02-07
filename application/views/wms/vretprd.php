<style type="text/css">
	.tagbox-remove{
		display: none;
	}
</style>
<div style="padding: 10px">
	<div class="container-xxl">       
        <div class="row">				
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >PSN No.</span>                    
                    <input type="text" class="form-control" id="ret_txt_txno" required>                   
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="ret_txt_itmcd" required>                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Category</span>                    
                    <input type="text" class="form-control" id="ret_txt_cat" required>                   
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Name</span>                    
                    <input type="text" class="form-control" id="ret_txt_itmnm" required readonly>                   
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Line</span>                    
                    <input type="text" class="form-control" id="ret_txt_line" required>                   
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >QTY (Before)</span>                    
                    <input type="text" class="form-control" id="ret_txt_befqty" required style="text-align:right">
                </div>
                
            </div>   
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot No</span>                    
                    <input type="text" class="form-control" id="ret_txt_lotno" required readonly maxlength="12">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                
            </div>
            <div class="col-md-6 mb-1">
                <div class="form-check-inline">
                    <label class="form-check-label">
                        RoHS
                    </label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" id="ret_ckyes" class="form-check-input" name="optradio" value="1" checked>
                    <label class="form-check-label" for="ret_ckyes">
                    Yes
                    </label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" id="ret_ckno" class="form-check-input" name="optradio" value="0">
                    <label class="form-check-label" for="ret_ckno">
                    No
                    </label>
                </div>
            </div>  
        </div>
        <div class="row">            
            <div class="col-md-12 mb-1">                
                <input type="text" style="width:100%" id="ret_txt_job" readonly>                
            </div>
               
        </div>  
        <div class="row">
            <div class="col-md-6 mb-1">                
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Country</span>                    
                    <select class="form-select" id="ret_sel_country">
                        <?php 
                            $toprint = '';
                            foreach($lmade as $r){
                                $toprint .= "<option value='".$r['MMADE_CD']."'>".$r['MMADE_NM']."</option>";
                            }
                            echo $toprint;
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Actual QTY</span>                    
                    <input type="text" class="form-control" id="ret_txt_actqty" style="text-align:right">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                
            </div>
            <div class="col-md-6 mb-3">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="ret_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" class="btn btn-primary" id="ret_btn_save"><i class="fas fa-save"></i></button>                    
                    <button title="Print" class="btn btn-primary" id="ret_btn_print" onclick="ret_evt_print()"><i class="fas fa-print"></i></button>
                </div>                
            </div>   
        </div>           
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="ret_divku">
                    <table id="ret_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>PSN No</th> 
                                <th>Category</th>                            
                                <th>Line</th>
                                <th>Feeder</th>                         
                                <th>Machine</th>
                                <th>Item Code</th>           
                                <th>Lot No</th>
                                <th>Bef. QTY</th>
                                <th>Act. QTY</th>
                                <th>RoHS</th>
                                <th>Country</th>
                                <th><input type="checkbox" title="Set checked or unchecked all" id="ret_ck_print"></th>
                                <th></th>
                                <th class="d-none"></th>
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
<script>
    $("#ret_divku").css('height', $(window).height()*41/100);
    $("#ret_ckyes").keypress(function (e) { 
        if(e.which==13){
            $("#ret_sel_country").focus();
        }
    });
   
    $("#ret_txt_befqty").keypress(function (e) {
        if(e.which==13){
            let mval = $(this).val();
            if(mval.substring(0,3)=='3N2'){
                let mpsn = document.getElementById('ret_txt_txno').value;
                let mcat = document.getElementById('ret_txt_cat').value;
                let mline = document.getElementById('ret_txt_line').value;
                
                let mitemcd = document.getElementById('ret_txt_itmcd').value;
                let mthis_ar = mval.split(' ');
                let mqty = 0;
                let mlot ='';
                if(!isNaN(mthis_ar[1])){
                    mqty = Number(mthis_ar[1]);
                } else {
                    alertify.warning('qty value must be numerical !');
                    return;
                }
                $(this).val(numeral(mqty).format('0,0')); 
                $(this).prop('readonly', true);
                for(var i=2;i<mthis_ar.length;i++){
                    mlot += mthis_ar[i] + ' ';
                }
                mlot = mlot.trim();
                if(mlot==''){
                    alertify.warning('lot must be not empty');
                    $(this).prop('readonly', false);
                    return;
                }
                document.getElementById('ret_txt_lotno').value=mlot;                  
                $.ajax({
                    type: "GET",
                    url: "<?=base_url('RETPRD/checklot_nofr')?>",
                    data: {inpsn : mpsn, incat: mcat, inline: mline, initem: mitemcd, inlot: mlot , inqty: mqty},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            $("#ret_sel_country").focus();
                        } else {
                            document.getElementById('ret_txt_befqty').value='';
                            document.getElementById('ret_txt_lotno').value='';
                            alertify.warning('Item and PSN were not match');
                            document.getElementById('ret_txt_befqty').readOnly=false;
                        }
                    }, error: function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }                        
        }
    });
    $("#ret_btnnew").click(function(){
        $("#ret_txt_cat").focus();
        $("#ret_txt_itmcd").val('');
        $('#ret_txt_job').tagbox('setValues', []);
        $("#ret_txt_itmnm").val('');

        $("#ret_txt_cat").val('');
        $("#ret_txt_lotno").val('');

        $("#ret_txt_line").val('');
        $("#ret_txt_befqty").val('');

        
        $("#ret_txt_actqty").val('');
        $("#ret_txt_befqty").prop('readonly', false);
        setHeadisRO(false);       
    });

    function setHeadisRO(sts){
        $("#ret_txt_txno").prop('readonly', sts);
        $("#ret_txt_cat").prop('readonly', sts);
        $("#ret_txt_line").prop('readonly', sts);
        
        
    }

    $("#ret_txt_txno").focus();
    $("#ret_txt_txno").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSN')?>",
                data: {inpsn: mval},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==0){
                        alertify.warning(response.status[0].msg);
                    } else {
                        $("#ret_txt_cat").focus();
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });            
        }
    });
    $("#ret_txt_cat").keypress(function (e) { 
        if(e.which==13){
            let mpsn = $("#ret_txt_txno").val();
            let mval = $(this).val();
            if(mval.trim()!='' && mpsn.trim()!=''){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SPL/checkPSNCAT')?>",
                    data: {inpsn: mpsn, incat: mval},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd=='0'){
                            alertify.warning(response.status[0].msg);
                        } else {
                            $("#ret_txt_line").focus();
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('should not be empty');
            }
        }
    });
    $("#ret_txt_line").keypress(function (e) { 
        if (e.which==13){
            let mpsn = $("#ret_txt_txno").val();
            let mcat = $("#ret_txt_cat").val();
            let mval = $(this).val();
            if(mval.trim()!='' && mpsn.trim()!='' && mcat.trim()!=''){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SPL/checkPSNCATLINE')?>",
                    data: {inpsn: mpsn, incat: mcat, inline: mval},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd=='0'){
                            alertify.warning(response.status[0].msg);
                        } else {
                            $("#ret_txt_itmcd").focus();
                            psnret_getdata();
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('should not be empty');
            }
        }
    });
   

    function psnret_getdata(){
        let mpsn = $("#ret_txt_txno").val();
        let mcat = $("#ret_txt_cat").val();
        let mline = $("#ret_txt_line").val();
        
        if( mpsn.trim()!='' && mcat.trim()!='' && mline.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSNCATLINENoFR')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd=='0'){
                        alertify.warning(response.data[0].msg);
                    } else {                            
                        //per JOBS
                        let mjobs =[];
                        for(let x=0;x<response.datahead.length; x++){
                            mjobs.push(response.datahead[x].PPSN1_WONO);
                        }                            
                        $('#ret_txt_job').tagbox('setValues', mjobs);
                        document.getElementById("ret_txt_itmcd").focus();
                        //end per JOBS                
                        setHeadisRO(true);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
            ret_e_getlist();
        } else {
            alertify.warning('should not be empty');
        }
    }

    function ret_e_getlist(){
        let mpsn = $("#ret_txt_txno").val();
        let mcat = $("#ret_txt_cat").val();
        let mline = $("#ret_txt_line").val();
        
        $.ajax({
                type: "get",
                url: "<?=base_url('RETPRD/getlist_nofr')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("ret_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("ret_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("ret_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let mckall = myfrag.getElementById("ret_ck_print");
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;  
                    let issaved, flagclass, flagcolorclass ;
                    for (let i = 0; i<ttlrows; i++){
                        issaved = response.data[i].RETSCN_SAVED ? response.data[i].RETSCN_SAVED : '0';
                        if(issaved=='1'){
                            flagclass = "fa-check";
                            flagcolorclass= "text-success";
                        } else {
                            flagclass = "fa-trash";
                            flagcolorclass= "text-danger";
                        }
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(response.data[i].RETSCN_ID);            
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].RETSCN_SPLDOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].RETSCN_CAT);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].RETSCN_LINE);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].RETSCN_FEDR);
                        newcell.appendChild(newText);                                
                        newcell.style.cssText = 'text-align: center';

                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(response.data[i].RETSCN_ORDERNO);
                        newcell.appendChild(newText);                                
                        newcell.style.cssText = 'text-align: center';

                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(response.data[i].RETSCN_ITMCD);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: center';

                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(response.data[i].RETSCN_LOT);
                        newcell.appendChild(newText);                        

                        newcell = newrow.insertCell(8);
                        newText = document.createTextNode(numeral(response.data[i].RETSCN_QTYBEF).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';

                        newcell = newrow.insertCell(9);
                        newText = document.createTextNode(numeral(response.data[i].RETSCN_QTYAFT).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';

                        newcell = newrow.insertCell(10);
                        newText = document.createTextNode(response.data[i].RETSCN_ROHS);
                        newcell.appendChild(newText);
                        

                        newcell = newrow.insertCell(11);
                        newText = document.createTextNode(response.data[i].MMADE_NM);
                        newcell.appendChild(newText);
                        

                        newcell = newrow.insertCell(12);
                        newText = document.createElement('input');
                        newText.setAttribute("type", "checkbox");                                                   
                        newcell.appendChild(newText);
                        newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');

                        newcell = newrow.insertCell(13);
                        newText = document.createElement('i');
                        newText.classList.add('fas', flagclass, flagcolorclass);
                        newcell.appendChild(newText);
                        newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');

                        newcell = newrow.insertCell(14);
                        newText = document.createTextNode(issaved);                        
                        newcell.classList.add('d-none');
                        newcell.appendChild(newText);
                        todisqty=0;
                    }        
                    let mrows = tableku2.getElementsByTagName("tr");      
                    // let crow, ccell ,chandler;       
                    // function cgetval(prow){
                    //     let tcell0 = prow.getElementsByTagName("td")[0];
                    //     let tcell2 = prow.getElementsByTagName("td")[2];                      
                    // }
                    function ret_e_delete(prow){
                        let datanya = prow.getElementsByTagName("td")[0].innerText;
                        let itmcd = prow.getElementsByTagName("td")[6].innerText;
                        let flagsave = prow.getElementsByTagName("td")[14].innerText;
                        if(flagsave=='0'){
                            let konfrm = confirm("Are you sure want to delete ("+datanya+") ? ");
                            if(konfrm){                               
                                $.ajax({
                                    type: "get",
                                    url: "<?=base_url('RETPRD/remove')?>",
                                    data: {inid: datanya},
                                    dataType: "json",
                                    success: function (response) {
                                        if(response.status[0].cd=='1'){
                                            alertify.success(response.status[0].msg);
                                            ret_e_getlist();
                                        } else {
                                            alertify.message(response.status[0].msg);
                                        }
                                    }, error:function(xhr,xopt,xthrow){
                                        alertify.error(xthrow);
                                    }
                                });
                            }
                        }                        
                    }
                    for(let x=0;x<mrows.length;x++){
                        tableku2.rows[x].cells[13].onclick = function(){ret_e_delete(tableku2.rows[x])};                    
                    }
                    function clooptable(){
                        let cktemp ;
                        for(let x=0;x<mrows.length;x++){
                            cktemp = tableku2.rows[x].cells[12].getElementsByTagName('input')[0];
                            cktemp.checked=mckall.checked;
                        }                        
                    }
                    mckall.onclick = function(){clooptable()};
                    mydes.innerHTML='';                            
                    mydes.appendChild(myfrag);
                }
            });
    }
    $("#ret_txt_actqty").keyup(function (e) { 
        $(this).val( numeral($(this).val()).format(',') );
    });
    $("#ret_txt_actqty").keypress(function (e) { 
        if(e.which==13){
            $("#ret_btn_save").focus();
        }
    });

    $("#ret_sel_country").change(function(){
        $("#ret_txt_actqty").focus();
    });

    $("#ret_txt_itmcd").keypress(function (e) { 
        if(e.which==13){
            let mpsn = $("#ret_txt_txno").val();
            let mcat = $("#ret_txt_cat").val();
            let mline = $("#ret_txt_line").val();
            
            let mval = $(this).val();
            if(mpsn.trim()==''){
                document.getElementById('ret_txt_txno').focus();
                alertify.message('Please fill PSN No');
                return;
            }
            if(mcat.trim()==''){
                document.getElementById('ret_txt_cat').focus();
                alertify.message('Please fill Category');
                return;
            }
            if(mline.trim()==''){
                document.getElementById('ret_txt_line').focus();
                alertify.message('Please fill Line');
                return;
            }
            
            if(mval.trim()==''){                
                alertify.message('Please fill Item Code');
                return;
            }
            if(mval.substring(0,3)!='3N1'){
                alertify.warning('Unknown Format C3 Label');
                return;
            }
            mval = mval.substring(3, mval.length);
            $(this).val(mval);
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSN_itmret_nofr')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline,  incode : mval},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd==0){
                        alertify.warning(response.data[0].msg);
                        document.getElementById('ret_txt_itmcd').value='';
                    } else {
                        document.getElementById('ret_txt_befqty').focus();
                        document.getElementById('ret_txt_itmnm').value=response.data[0].ref;
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#ret_txt_job').tagbox({
        label: 'Job No',        
        onRemoveTag :function(e) {
            e.preventDefault();           
        }
    });
    $("#ret_btn_save").click(function (e) { 
        let mpsn = $("#ret_txt_txno").val();
        let mcat = $("#ret_txt_cat").val();
        let mline = $("#ret_txt_line").val();
        
        let mitemcd = $("#ret_txt_itmcd").val();
        let mqtybef = $("#ret_txt_befqty").val();
        let mcountry = $("#ret_sel_country").val();
        let mqtyaft = $("#ret_txt_actqty").val();
        let mlot = $("#ret_txt_lotno").val();
        let mrohs = '';
        if(document.getElementById('ret_ckyes').checked){
            mrohs = document.getElementById('ret_ckyes').value;
        } else {
            mrohs = document.getElementById('ret_ckno').value;
        }
        if(mpsn.trim()==''){
            document.getElementById('ret_txt_txno').focus();
            alertify.message('Please fill PSN No');
            return;
        }
        if(mcat.trim()==''){
            document.getElementById('ret_txt_cat').focus();
            alertify.message('Please fill Category');
            return;
        }
        if(mline.trim()==''){
            document.getElementById('ret_txt_line').focus();
            alertify.message('Please fill Line');
            return;
        }
        
        if(mitemcd.trim()==''){             
            document.getElementById('ret_txt_itmcd').focus();   
            alertify.message('Please fill Item Code');
            return;
        }
        if(mqtybef.trim()==''){
            document.getElementById('ret_txt_befqty').focus();
            alertify.message('Please Scan qty or lot');
            return;
        }
        mqtybef = numeral(mqtybef).value();
        if(mlot.trim()==''){
            document.getElementById('ret_txt_lotno').focus();
            return;
        }
        mlot =mlot.trim();
        if(mqtyaft.trim()==''){
            document.getElementById('ret_txt_actqty').focus();
            alertify.message('Please fill Actual Qty');
            return;
        }
        mqtyaft = numeral(mqtyaft).value();
        // if(mqtyaft > mqtybef){
        //     alertify.warning('Qty After > Qty Before');
        //     document.getElementById('ret_txt_actqty').focus();
        //     document.getElementById('ret_txt_actqty').select();
            
        // } else {
            $.ajax({
                type: "post",
                url: "<?=base_url('RETPRD/save')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline,  initmcd: mitemcd, inrohs: mrohs, inqtybef: mqtybef,
                inqtyaft: mqtyaft, inlot: mlot, incountry : mcountry},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='02'){
                        if(confirm(response.status[0].msg + ".\n" + " proceed anyway ?" )){                            
                            ret_evt_save_alt();
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                        psnret_getdata();
                        document.getElementById('ret_txt_actqty').value='';
                        document.getElementById('ret_txt_itmnm').value='';
                        document.getElementById('ret_txt_itmcd').value='';
                        document.getElementById('ret_txt_lotno').value='';
                        document.getElementById('ret_txt_befqty').readOnly=false;
                        document.getElementById('ret_txt_befqty').value='';
                        document.getElementById('ret_txt_itmcd').focus();        
                    }
                    
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        // }
    });

    function ret_evt_save_alt(){
        let mpsn = $("#ret_txt_txno").val();
        let mcat = $("#ret_txt_cat").val();
        let mline = $("#ret_txt_line").val();
        
        let mitemcd = $("#ret_txt_itmcd").val();        
        let mcountry = $("#ret_sel_country").val();
        let mqtyaft = $("#ret_txt_actqty").val();
        
        let mrohs = '';
        if(document.getElementById('ret_ckyes').checked){
            mrohs = document.getElementById('ret_ckyes').value;
        } else {
            mrohs = document.getElementById('ret_ckno').value;
        }
        mqtyaft = numeral(mqtyaft).value();
        $.ajax({
            type: "post",
            url: "<?=base_url('RETPRD/save_alt')?>",
            data: {inpsn: mpsn, incat: mcat, inline: mline,  initmcd: mitemcd, inrohs: mrohs, 
            inqtyaft: mqtyaft, incountry : mcountry},
            dataType: "json",
            success: function (response) {
                alertify.message(response.status[0].msg);
                psnret_getdata();
                document.getElementById('ret_txt_actqty').value='';
                document.getElementById('ret_txt_itmnm').value='';
                document.getElementById('ret_txt_itmcd').value='';
                document.getElementById('ret_txt_lotno').value='';
                document.getElementById('ret_txt_befqty').readOnly=false;
                document.getElementById('ret_txt_befqty').value='';
                document.getElementById('ret_txt_itmcd').focus();               
                
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
            });
    }

    function ret_evt_print(){
        let mtbl = document.getElementById('ret_tbl');        
        let mrows = mtbl.getElementsByTagName("tr");
        let idprints = '';
        if(mrows.length>1){
            for(let x=1;x<mrows.length;x++){ 
                if(mtbl.rows[x].cells[12].getElementsByTagName('input')[0].checked){
                    idprints += mtbl.rows[x].cells[0].innerText + '.';
                }                
            }
            idprints = idprints.substring(0, idprints.length-1);
            Cookies.set('CKPSI_IDRET', idprints , {expires:365});
            window.open("<?=base_url('printlabel_ret')?>" ,'_blank');
        }
    }
    
</script>