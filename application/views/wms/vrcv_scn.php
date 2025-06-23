<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New"     id="rcvscn_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <!-- <button title="Save"    id="rcvscn_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>                     -->
                </div>
            </div>
        </div>
        <div class="row">                    
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Vendor DN No.</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_dn" required>                    
                    <button title="Search data DO" class="btn btn-outline-secondary" type="button" id="rcvscn_btn_dnfind"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Warehouse</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_wh" required readonly> 
                    <input type="text" class="form-control" id="rcvscn_txt_whnm" required readonly> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Rack ID</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_rack" required>                              
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_itmcd" required>                    
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_itmqty" required >                    
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot No</span>                    
                    <input type="text" class="form-control" id="rcvscn_txt_itmlot" required readonly>                    
                </div>
            </div>            
        </div>        
        <div class="row">     
            <div class="col-md-5 mb-1">                
                <div class="progress" title="Scanning progress" style="height:60%">
                    <div id="rcvscn_lblitemprogress"  class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" 
                        style="width: 0%"><small id="rcvscn_lblprog">0%</small></div>                        
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="progress" title="Saving progress" style="height:60%">
                    <div id="rcvscn_lblsaveprogress"  class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" 
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" 
                        style="width: 0%"><small id="rcvscn_lblprogsave">0%</small></div>                        
                </div>
            </div>
            <div class="col-md-2 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button id="rcvscn_btn_adjust"  title="Adjust" class="btn btn-primary"><i class="fas fa-edit"></i></button>                                  
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvscn_divku">
                    <table id="rcvscn_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Item Code</th>                             
                                <th class="text-end">Receive Qty</th>
                                <th class="text-end">Scanned Qty</th>                                
                                <th class="text-end">Balance</th>
                                <th class="text-end">Saved Qty</th>                            
                                <th class="text-center">Last Update</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">NIK</th>
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
<div class="modal fade" id="RCVSCN_ADJ">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Table</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <select id="rcvscn_filteradj" class="form-select form-select-sm">
                            
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="rcvscn_lbladjdatainfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rcvscn_tbladj_div">
                        <table id="rcvscn_tbladj" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>QTY</th>
                                    <th>Lot No</th>
                                    <th>Last Update</th>
                                    <th>Status</th>
                                    <th>.</th>
                                    <th>NIK</th>
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
<div class="modal fade" id="RCVSCN_SHOWDO">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Table</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" id="rcvscn_txtsearch" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col md-1 text-center">
                    <div class="form-check-inline">                        
                        <input type="radio" id="rcvscn_rad_open" onclick="rcvscn_focussearch()" class="form-check-input" name="rcvscn_optradio" value="open" checked>                        
                        <label class="form-check-label" for="rcvscn_rad_open">
                        Open
                        </label>
                    </div>
                    <div class="form-check-inline">                        
                        <input type="radio" id="rcvscn_rad_all" onclick="rcvscn_focussearch()" class="form-check-input" name="rcvscn_optradio" value="all">                        
                        <label class="form-check-label" for="rcvscn_rad_all">
                        All
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="rcvscn_lbltblshowdo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" >
                        <table id="rcvscn_tblshowdo" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>DO No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Total Item</th>
                                    <th>Total QTY</th>
                                    <th>Unscanned QTY</th>
                                </tr>
                            </thead>
                            <tbody style="cursor: pointer">
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
    $("#rcvscn_divku").css('height', $(window).height()*61/100);
    var rcvscn_selcol = 0;    
    $("#rcvscn_txt_dn").focus();
    $("#rcvscn_btnnew").click(function (e) {   
        $("#rcvscn_tbl tbody").empty();
        $("#rcvscn_txt_dn").val('');      
        $("#rcvscn_txt_dn").focus();
        $("#rcvscn_lblitemprogress").css("width", "0%");
        $("#rcvscn_lblprog").text('0%');
        $("#rcvscn_lblsaveprogress").css("width", "0%");
        $("#rcvscn_lblprogsave").text('0%');
    });
    $("#rcvscn_txt_itmcd").keypress(function(e){
        if(e.which==13){
            var thisval = $(this).val();            
            if(thisval.substring(0,3)!='3N1'){
                alertify.warning('Unknown Format C3 Label');
                return;
            }
            thisval = thisval.substring(3, thisval.length);
            $(this).val(thisval);
            var mdo  = $("#rcvscn_txt_dn").val();
            var mrack = $("#rcvscn_txt_rack").val();
            let mwh = $("#rcvscn_txt_wh").val();
            if(mdo.trim()==''){
                $("#rcvscn_txt_dn").focus();
                return;
            }
            if(mrack.trim()==''){                
                $.ajax({
                    type: "get",
                    url: "<?=base_url('ITMLOC/getbyitemcd')?>",
                    data: {incd: thisval},
                    dataType: "json",
                    success: function (response) {
                        if(response.length>0){
                            document.getElementById('rcvscn_txt_rack').value= response[0].ITMLOC_LOC;
                        }  else {
                            alertify.warning('locataion not found');
                        }             
                    },error: function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
                return;
            }
            if (thisval.trim()!=''){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('RCV/scn_itemdo_check')?>",
                    data: {inDO: mdo ,inWH: mwh,inRACK: mrack, inITEM: thisval},
                    dataType: "text",
                    success: function (response) {
                        switch(response){
                            case '11':
                                $("#rcvscn_txt_itmqty").focus();break;
                            case '1':
                                alertify.warning('Item and Its location does not match');break;
                            case '0':
                                alertify.warning('The item is not in the DO Data');break;
                        }                    
                    }, error: function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }        
        }
    });
    var rt_rcvscan = setInterval( fscn_balancing, 300000);
    function fscn_balancing(){
        const mval = rcvscn_txt_dn.value.trim();
        if(mval.length!=0){
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/scn_balancing')?>",
                data: {inDO:mval},
                dataType: "json",
                success: function (response) {
                    let newrow, newcell;
                    let mydes = document.getElementById("rcvscn_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rcvscn_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rcvscn_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';
                    response.data.forEach((arrayItem, i) => {
                        mscan = arrayItem['SCAN_QTY'];
                        msavedqty = arrayItem['ITHQTY'];                     
                        if(mscan.charAt(0)=='.'){
                            mscan = 0;
                        }
                        if(msavedqty.charAt(0)=='.'){
                            msavedqty = 0;
                        }
                        if(arrayItem['LTSSCANTIME']){
                            mlastscantime = arrayItem['LTSSCANTIME'];
                        } else {
                            mlastscantime='';
                        }
                        if(numeral(arrayItem['RCV_QTY']).value()==numeral(msavedqty).value()){
                            stsicon = '<i class="fas fa-check text-success"></i>';
                        } else {
                            stsicon = '<i class="fas fa-question-circle text-warning"></i>';
                        }
                        mbalanc = numeral(arrayItem['RCV_QTY']).value()-numeral(mscan).value();

                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerText = (i+1)
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = arrayItem['RCV_ITMCD']
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = numeral(arrayItem['RCV_QTY']).format(',')
                        newcell.classList.add('text-end')
                        
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = numeral(mscan).format(',')
                        newcell.classList.add('text-end')
                        
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = numeral(mbalanc).format(',')
                        newcell.classList.add('text-end')
                        
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = numeral(msavedqty).format(',')
                        newcell.classList.add('text-end')

                        newcell = newrow.insertCell(-1);
                        newcell.innerText = mlastscantime

                        newcell = newrow.insertCell(-1);
                        newcell.innerHTML = stsicon

                        newcell = newrow.insertCell(-1);
                        newcell.innerText = arrayItem['NIK']
                        newcell.title = arrayItem['PIC']
                    })
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);

                    if(response.datahead.length>0){
                        let vprgs = response.datahead[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }                        
                        $("#rcvscn_lblitemprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#rcvscn_lblprog").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                    if(response.datasave.length>0){
                        vprgs = response.datasave[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }                        
                        $("#rcvscn_lblsaveprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#rcvscn_lblprogsave").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                }
            });
        }        
    }
$("#rcvscn_txt_dn").keypress(function (e) { 
    if (e.which==13){
        var mval = $(this).val();
        if(mval.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/scn_balancing')?>",
                data: {inDO: mval},
                dataType: "json",
                success: function (response) {
                    fscn_balancing()
                }
            });
        } else {
            alertify.warning('DO Data could not be blank');
        }                       
    }
});
$("#rcvscn_txt_rack").keypress(function (e) { 
    if(e.which==13){
        var mdo = $("#rcvscn_txt_dn").val();
        if (mdo.trim()!=''){
            $("#rcvscn_txt_itmcd").focus();
        } else {
            alertify.warning('Please entry DO data');$("#rcvscn_txt_dn").focus();
        }
    }
});
$("#rcvscn_txt_itmqty").keypress(function (e) { 
    if(e.which==13){
        var mthisval = $(this).val();
        var mdo = $("#rcvscn_txt_dn").val();
        var mrack = $("#rcvscn_txt_rack").val();
        var mitm = $("#rcvscn_txt_itmcd").val();        
        if (mdo.trim()==''){
            alertify.warning('Please entry DO data');
            $("#rcvscn_txt_dn").focus();
        }
        if(mrack.trim()==''){
            $("#rcvscn_txt_rack").focus();
            return;
        }
        if(mitm.trim()==''){
            $("#rcvscn_txt_itmcd").focus();
            return;
        }

        if(mthisval.trim()!=''){
            if(mthisval.substring(0,3)=='3N2'){
                var mthis_ar    = mthisval.split(' ');
                var mqty        = 0;
                var mlot        ='';
                if(!isNaN(mthis_ar[1])){
                    mqty = Number(mthis_ar[1]);
                } else {
                    alertify.warning('qty value must be numerical !');
                    return;
                }
                $(this).val(mthis_ar[1]);                                
                for(var i=2;i<mthis_ar.length;i++){
                    mlot += mthis_ar[i] + ' ';
                }
                $("#rcvscn_txt_itmlot").val(mlot);
                var mgetbal = '-';
                var tables   = $("#rcvscn_tbl tbody");                
                tables.find('tr').each(function (i) {                    
                    var $tds = $(this).find('td'),                            
                            ritem =  $tds.eq(1).text(),
                            rbalanc = $tds.eq(4).text();                    
                    if(ritem.trim()==mitm.trim()){     
                       mgetbal = rbalanc; return false;
                    }
                });
                if(Number(mqty)==0){
                    alertify.warning("QTY must greater than zero");
                    $("#rcvscn_txt_itmqty").val('');$("#rcvscn_txt_itmlot").val('');
                    $("#rcvscn_txt_itmcd").val('');$("#rcvscn_txt_itmcd").focus();
                    return;
                }
                if(mqty> numeral(mgetbal).value()){
                    $("#rcvscn_txt_itmqty").val('');$("#rcvscn_txt_itmlot").val('');
                    $("#rcvscn_txt_itmcd").val('');$("#rcvscn_txt_itmcd").focus();
                    alertify.warning("QTY > Balance !" );
                    return;
                }
                $.ajax({
                    type: "post",
                    url: "<?=base_url('RCV/scn_set')?>",
                    data: {inDO: mdo, inITM: mitm, inQTY: mqty, inLOT: mlot, inRACK : mrack},
                    dataType: "text",
                    success: function (response) {
                        if(response.substring(0,3)=='111'){
                            alertify.success('OK');
                            let ares = response.split('_');
                            if(isNaN(ares[1])){
                                $("#rcvscn_txt_itmcd").val('');
                                $("#rcvscn_txt_itmqty").val('');$("#rcvscn_txt_itmlot").val('');
                                $("#rcvscn_txt_itmcd").focus();
                            } else {
                                if(Number(ares[1])>0){
                                    $("#rcvscn_txt_itmqty").val('');$("#rcvscn_txt_itmlot").val('');$("#rcvscn_txt_itmqty").focus();
                                } else {
                                    $("#rcvscn_txt_itmcd").val('');
                                    $("#rcvscn_txt_itmqty").val('');$("#rcvscn_txt_itmlot").val('');
                                    $("#rcvscn_txt_itmcd").focus();
                                }
                            }
                            fscn_balancing();                                                  
                        } else {
                            if(response=='10'){
                                alertify.message('Location is not valid');
                            }                            
                        }                                          
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('Unknown C3 Format');
            }
        }
    }
});
$("#rcvscn_btn_adjust").click(function(){
    $("#RCVSCN_ADJ").modal('show');
    var tables   = $("#rcvscn_tbl tbody");                
    var tohtml = '<option value="-">Choose</option>';
    tables.find('tr').each(function (i) {                    
        var $tds = $(this).find('td'),                            
                ritem =  $tds.eq(1).text();       
        tohtml += '<option value="'+ritem+'">'+ritem+'</option>';        
    });
    $("#rcvscn_filteradj").html(tohtml);    
});
$("#rcvscn_filteradj").change(function(){ 
    let mdo = $("#rcvscn_txt_dn").val();
    let mitem = $("#rcvscn_filteradj").val();
    $.ajax({
        type: "get",
        url: "<?=base_url('RCV/scnd_list_bydo_item')?>",
        data: {inDO: mdo, inITEM: mitem},
        dataType: "json",
        success: function (response) {
            let newrow, newcell;
            let mydes = document.getElementById("rcvscn_tbladj_div");
            let myfrag = document.createDocumentFragment();
            let mtabel = document.getElementById("rcvscn_tbladj");
            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln);
            let tabell = myfrag.getElementById("rcvscn_tbladj");
            let tableku2 = tabell.getElementsByTagName("tbody")[0];
            tableku2.innerHTML='';
            response.forEach((arrayItem) => {
                if(arrayItem['RCVSCN_SAVED']) {
                    stsdis = arrayItem['RCVSCN_SAVED'];
                    if(stsdis=='1') {
                        stsdis = 'saved';
                    } else {
                        stsdis = 'not saved yet';
                    }
                } else {
                    stsdis = 'not saved yet';
                }
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(0);
                newcell.innerText = arrayItem['RCVSCN_ID']
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['RCVSCN_ITMCD']
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['RCVSCN_QTY']
                newcell.classList.add('text-end')
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['RCVSCN_LOTNO']
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['RCVSCN_LUPDT']
                newcell = newrow.insertCell(-1);
                newcell.innerText = stsdis
                newcell = newrow.insertCell(-1);
                newcell.innerHTML = `<i class="fas fa-trash text-warning"></i>`
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['RCVSCN_USRID']
                newcell.title = arrayItem['FULLNM']
            })
            mydes.innerHTML='';
            mydes.appendChild(myfrag);
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });
});


$("#RCVSCN_ADJ").on('hidden.bs.modal', function(){
    $("#rcvscn_tbladj tbody").empty();
    fscn_balancing();
});
$('#rcvscn_tbladj tbody').on( 'click', 'td', function () {            
    rcvscn_selcol = $(this).index();         
});
$('#rcvscn_tbladj tbody').on( 'click', 'tr', function () { 
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcvscn_tbladj tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    let rid = $(this).closest("tr").find('td:eq(0)').text();
    let sts = $(this).closest("tr").find('td:eq(5)').text();
    if(rcvscn_selcol==6){
        if(sts.substring(0,3)=='sav'){
            alertify.warning('We could not delete saved item');
        } else {
            let konfrm = confirm('Are you sure ?');
            if (konfrm){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('RCV/scnd_remove')?>",
                    data: {inID: rid},
                    dataType: "text",
                    success: function (response) {
                        alertify.message(response);
                    }, error: function(xhr,xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
                $(this).closest("tr").hide('slow',function(){
                    $(this).closest("tr").remove();
                });
            }
        }                
    }
});

$('#rcvscn_tblshowdo tbody').on( 'click', 'tr', function () { 
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcvscn_tblshowdo tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    let dono = $(this).closest("tr").find('td:eq(0)').text();
    document.getElementById("rcvscn_txt_dn").value= dono;
    $("#RCVSCN_SHOWDO").modal('hide');
    let ev = jQuery.Event("keypress");
    ev.which=13;
    $("#rcvscn_txt_dn").trigger(ev);
});
$("#rcvscn_btnsave").click(function(){    
    if(confirm(`Are you sure ?`)){
        const mDO = $("#rcvscn_txt_dn").val();
        if(mDO.trim()==''){
            $("#rcvscn_txt_dn").focus();
            return;
        }
        var a_mitem     = [];
        var a_reqqty    = [];
        var a_scanqty   = [];
        var a_savedqty  = [];
        var qtysum = 0;
        
        var tables   = $("#rcvscn_tbl tbody");                
        tables.find('tr').each(function (i) {                    
            var $tds = $(this).find('td'),
                ritem = $tds.eq(1).text(),
                rreqqty = $tds.eq(2).text(),
                rqty = $tds.eq(3).text(),
                rithqty = $tds.eq(5).text();
                if(numeral(rqty).value()!=numeral(rithqty).value()){
                    a_mitem.push(ritem);
                    a_reqqty.push(numeral(rreqqty).value());
                    a_scanqty.push(numeral(rqty).value());
                    a_savedqty.push(numeral(rithqty).value());
                }
            qtysum += numeral(rqty).value();
        });
        if (a_mitem.length>0){
            let mwh = document.getElementById('rcvscn_txt_wh').value;
            let mrack = document.getElementById('rcvscn_txt_rack').value;
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/setscn')?>",
                data: {indo: mDO, initm: a_mitem, inreqqty: a_reqqty, inscanqty: a_scanqty, insavedqty: a_savedqty, inwh: mwh, inrack: mrack},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);fscn_balancing();
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.message("nothing to be saved");
        }
    }
});
function rcvscn_focussearch(){
    document.getElementById("rcvscn_txtsearch").focus();
}
$("#RCVSCN_SHOWDO").on('shown.bs.modal', function(){
    $("#rcvscn_txtsearch").focus();
});
$("#rcvscn_btn_dnfind").click(function (e) {     
    $("#RCVSCN_SHOWDO").modal('show');
});
$("#rcvscn_txtsearch").keypress(function (e) { 
    if(e.which==13){
        let mdo = document.getElementById("rcvscn_txtsearch").value;
        let searchtype = document.getElementById('rcvscn_rad_open').checked ? 'open' : 'all';
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/searchDO')?>",
            data: {indo: mdo, insearchtype: searchtype},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let tohtml = '';
                let unscannedqty = 0;
                for(let i = 0 ; i< ttlrows; i++){
                    unscannedqty = numeral(response.data[i].TTLREQ).value() - numeral(response.data[i].TTLACT).value();
                    tohtml += '<tr>'+
                    '<td>'+response.data[i].RCV_DONO+'</td>'+
                    '<td>'+response.data[i].TGL+'</td>'+
                    '<td>'+response.data[i].MSUP_SUPNM+'</td>'+
                    '<td class="text-end">'+response.data[i].TTLITEM+'</td>'+
                    '<td class="text-end">'+numeral(response.data[i].TTLREQ).format('0,0')+'</td>'+
                    '<td class="text-end">'+numeral(unscannedqty).format('0,0')+'</td>'+
                    '</tr>';
                }
                $("#rcvscn_tblshowdo tbody").html(tohtml);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
});
</script>