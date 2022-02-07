<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New"     id="SCRscn_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save"    id="SCRscn_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">                    
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document No.</span>                    
                    <input type="text" class="form-control" id="SCRscn_txt_dn" required>                                 
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Warehouse</span>                    
                    <input type="text" class="form-control" id="SCRscn_txt_wh" value="<?=$mwh?>" required readonly>
                    <input type="text" class="form-control" id="SCRscn_txt_whnm" value="<?=$mwhnm?>" required readonly>
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-8 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="SCRscn_txt_itmcd" required>                    
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">            
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="SCRscn_txt_itmqty" required >                    
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot No</span>                    
                    <input type="text" class="form-control" id="SCRscn_txt_itmlot" required readonly>                    
                </div>
            </div>            
        </div>        
        <div class="row">     
            <div class="col-md-5 mb-1">                
                <div class="progress" title="Scanning progress" style="height:60%">
                    <div id="SCRscn_lblitemprogress"  class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" 
                        style="width: 0%">
                        <small id="SCRscn_lblprog">0%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="progress" title="Saving progress" style="height:60%">
                    <div id="SCRscn_lblsaveprogress"  class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" 
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" 
                        style="width: 0%">
                        <small id="SCRscn_lblprogsave">0%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button id="SCRscn_btn_adjust"  title="Adjust" class="btn btn-primary"><i class="fas fa-edit"></i></button>                                  
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="SCRscn_divku">
                    <table id="SCRscn_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Item Code</th>                             
                                <th class="text-right">Req Qty</th>
                                <th class="text-right">Scanned Qty</th>                                
                                <th class="text-right">Balance</th>
                                <th class="text-right">Saved Qty</th>                            
                                <th class="text-center">Last Update</th>
                                <th class="text-center">Status</th>
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
<div class="modal fade" id="SCRscn_ADJ">
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
                        <select id="SCRscn_filteradj" class="form-select">
                            
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="SCRscn_lbladjdatainfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" >
                        <table id="SCRscn_tbladj" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>QTY</th>
                                    <th>Lot No</th>
                                    <th>Last Update</th>
                                    <th>Status</th>
                                    <th>.</th>
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
    $("#SCRscn_divku").css('height', $(window).height()*61/100);
    var SCRscn_selcol = 0;    
    $("#SCRscn_txt_dn").focus();
    $("#SCRscn_btnnew").click(function (e) {   
        $("#SCRscn_tbl tbody").empty();
        $("#SCRscn_txt_dn").val('');      
        $("#SCRscn_txt_dn").focus();
        $("#SCRscn_lblitemprogress").css("width", "0%");
        $("#SCRscn_lblprog").text('0%');
        $("#SCRscn_lblsaveprogress").css("width", "0%");
        $("#SCRscn_lblprogsave").text('0%');
    });
    $("#SCRscn_txt_itmcd").keypress(function(e){
        if(e.which==13){
            var thisval = $(this).val();            
            if(thisval.substring(0,3)!='3N1'){
                alertify.warning('Unknown Format C3 Label');
                return;
            }
            thisval = thisval.substring(3, thisval.length);
            $(this).val(thisval);
            var mdo  = $("#SCRscn_txt_dn").val();            
            if(mdo.trim()==''){
                $("#SCRscn_txt_dn").focus();
                return;
            }
            
            if (thisval.trim()!=''){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SCR/scn_itemdo_check')?>",
                    data: {inDO: mdo , inITEM: thisval},
                    dataType: "text",
                    success: function (response) {
                        switch(response){
                            case '1':
                                $("#SCRscn_txt_itmqty").focus();break;
                            case '0':
                                alertify.warning('The item is not in the DOC Data');break;
                        }                    
                    }, error: function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }        
        }
    });
    function fscn_balancing(){
        let mval = $("#SCRscn_txt_dn").val();
        $.ajax({
            type: "get",
            url: "<?=base_url('SCR/scn_balancing')?>",
            data: {inDO: mval},
            dataType: "json",
            success: function (response) {
                var ttlrows = response.data.length;
                var tohtml = '';
                var mbalanc = 0;
                var msavedqty = 0;
                var mscan = 0;
                var mlastscantime = '';
                let stsicon = '';
                for(var i=0;i<ttlrows;i++){
                    mscan = response.data[i].SCAN_QTY;
                    msavedqty = response.data[i].ITHQTY;                     
                    if(mscan.charAt(0)=='.'){
                        mscan = 0;
                    }
                    if(msavedqty.charAt(0)=='.'){
                        msavedqty = 0;
                    }
                    if(response.data[i].LTSSCANTIME){
                        mlastscantime =response.data[i].LTSSCANTIME;
                    } else {
                        mlastscantime='';
                    }
                    if(numeral(response.data[i].SCR_QTY).value()==numeral(msavedqty).value()){
                        stsicon = '<i class="fas fa-check text-success"></i>';
                    } else {
                        stsicon = '<i class="fas fa-question-circle text-warning"></i>';
                    }
                    mbalanc = numeral(response.data[i].SCR_QTY).value()-numeral(mscan).value();
                    tohtml += '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response.data[i].SCR_ITMCD+'</td>'+
                    '<td class="text-right">'+numeral(response.data[i].SCR_QTY).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(mscan).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(mbalanc).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(msavedqty).format(',')+'</td>'+
                    '<td class="text-center">'+mlastscantime+'</td>'+
                    '<td class="text-center" style="cursor:pointer">'+stsicon+'</td>'+
                    '</tr>';
                }
                $("#SCRscn_tbl tbody").html(tohtml);
                if(response.datahead.length>0){
                    let vprgs = response.datahead[0].PROGRESS;
                    if(vprgs.charAt(0)=='.'){
                        vprgs='0'+vprgs;
                    }                        
                    $("#SCRscn_lblitemprogress").css("width", numeral(vprgs).format('0,0')+"%");
                    $("#SCRscn_lblprog").text(numeral(vprgs).format('0,0.000')+'%');
                }
                if(response.datasave.length>0){
                    vprgs = response.datasave[0].PROGRESS;
                    if(vprgs.charAt(0)=='.'){
                        vprgs='0'+vprgs;
                    }                        
                    $("#SCRscn_lblsaveprogress").css("width", numeral(vprgs).format('0,0')+"%");
                    $("#SCRscn_lblprogsave").text(numeral(vprgs).format('0,0.000')+'%');
                }
            }
        });
    }
$("#SCRscn_txt_dn").keypress(function (e) { 
    if (e.which==13){
        var mval = $(this).val();
        if(mval.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('SCR/scn_balancing')?>",
                data: {inDO: mval},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.data.length;
                    var tohtml = '';
                    var mbalanc = 0;
                    var msavedqty = 0;
                    var mscan = 0;
                    var mlastscantime = ''; 
                    var mwh ='';
                    var mwh_nm = '';
                    var mlabelinfo = '';    
                    let stsicon = '';
                    let mDT = '';
                    let mTM = '';
                    if(ttlrows>0){
                        for(var i=0;i<ttlrows;i++){                            
                            mscan = response.data[i].SCAN_QTY;
                            msavedqty = response.data[i].ITHQTY;                        
                            if(mscan.charAt(0)=='.'){
                                mscan = 0;
                            }
                            if(msavedqty.charAt(0)=='.'){
                                msavedqty = 0;
                            }
                            if(response.data[i].LTSSCANTIME){
                                mlastscantime =response.data[i].LTSSCANTIME;
                                mDT = moment(mlastscantime.substr(0,10), 'YYYY-MM-DD');
                                mTM = mlastscantime.substr(11,8);
                                mlastscantime = mDT.format('ddd, D MMM YYYY');
                                mlastscantime = mlastscantime.concat(' ', mTM);
                            } else {
                                mlastscantime='';
                            }
                            if(numeral(response.data[i].SCR_QTY).value()==numeral(msavedqty).value()){
                                stsicon = '<i class="fas fa-check text-success"></i>';
                            } else {
                                stsicon = '<i class="fas fa-question-circle text-warning"></i>';
                            }
                            mbalanc = numeral(response.data[i].SCR_QTY).value()-numeral(mscan).value();
                            tohtml += '<tr>'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+response.data[i].SCR_ITMCD.trim()+'</td>'+
                            '<td class="text-right">'+numeral(response.data[i].SCR_QTY).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(mscan).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(mbalanc).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(msavedqty).format(',')+'</td>'+
                            '<td class="text-center">'+mlastscantime+'</td>'+
                            '<td class="text-center" style="cursor:pointer">'+stsicon+'</td>'+
                            '</tr>';
                        }
                        document.getElementById('SCRscn_txt_itmcd').focus();
                    } else {
                        alertify.warning("DO Number not found");
                    }                                        
                    $("#SCRscn_tbl tbody").html(tohtml);
                    let vprgs ='';
                    if(response.datahead.length>0){
                        vprgs = response.datahead[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }                        
                        $("#SCRscn_lblitemprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#SCRscn_lblprog").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                    if(response.datasave.length>0){
                        vprgs = response.datasave[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }                        
                        $("#SCRscn_lblsaveprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#SCRscn_lblprogsave").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                }
            });
        } else {
            alertify.warning('DO Data could not be blank');
        }                       
    }
});

$("#SCRscn_txt_itmqty").keypress(function (e) { 
    if(e.which==13){
        var mthisval = $(this).val();
        var mdo = $("#SCRscn_txt_dn").val();        
        var mitm = $("#SCRscn_txt_itmcd").val();        
        if (mdo.trim()==''){
            alertify.warning('Please entry DO data');
            $("#SCRscn_txt_dn").focus();
        }
  
        if(mitm.trim()==''){
            $("#SCRscn_txt_itmcd").focus();
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
                $("#SCRscn_txt_itmlot").val(mlot);
                var mgetbal = '-';
                var tables   = $("#SCRscn_tbl tbody");                
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
                    $("#SCRscn_txt_itmqty").val('');$("#SCRscn_txt_itmlot").val('');
                    $("#SCRscn_txt_itmcd").val('');$("#SCRscn_txt_itmcd").focus();
                    return;
                }
                if(mqty> numeral(mgetbal).value()){
                    $("#SCRscn_txt_itmqty").val('');$("#SCRscn_txt_itmlot").val('');
                    $("#SCRscn_txt_itmcd").val('');$("#SCRscn_txt_itmcd").focus();
                    alertify.warning("QTY > Balance !" );
                    return;
                }
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SCR/scn_set')?>",
                    data: {inDO: mdo, inITM: mitm, inQTY: mqty, inLOT: mlot},
                    dataType: "text",
                    success: function (response) {
                        if(response.substring(0,2)=='11'){
                            alertify.success('OK');
                            let ares = response.split('_');
                            if(isNaN(ares[1])){
                                $("#SCRscn_txt_itmcd").val('');
                                $("#SCRscn_txt_itmqty").val('');$("#SCRscn_txt_itmlot").val('');
                                $("#SCRscn_txt_itmcd").focus();
                            } else {
                                if(Number(ares[1])>0){
                                    $("#SCRscn_txt_itmqty").val('');$("#SCRscn_txt_itmlot").val('');$("#SCRscn_txt_itmqty").focus();
                                } else {
                                    $("#SCRscn_txt_itmcd").val('');
                                    $("#SCRscn_txt_itmqty").val('');$("#SCRscn_txt_itmlot").val('');
                                    $("#SCRscn_txt_itmcd").focus();
                                }
                            }
                            fscn_balancing();                                                  
                        } else {
                            if(response=='0'){
                                alertify.message('Item with document does not match');
                            }                            
                        }                                          
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }
});
$("#SCRscn_btn_adjust").click(function(){
    $("#SCRscn_ADJ").modal('show');
    var tables   = $("#SCRscn_tbl tbody");                
    var tohtml = '<option value="-">Choose</option>';
    tables.find('tr').each(function (i) {                    
        var $tds = $(this).find('td'),                            
                ritem =  $tds.eq(1).text();       
        tohtml += '<option value="'+ritem+'">'+ritem+'</option>';        
    });
    $("#SCRscn_filteradj").html(tohtml);
});
$("#SCRscn_filteradj").change(function(){
    var mdo = $("#SCRscn_txt_dn").val();
    var mitem = $(this).val();
    $.ajax({
        type: "get",
        url: "<?=base_url('SCR/scnd_list_bydo_item')?>",
        data: {inDO: mdo, inITEM: mitem},
        dataType: "json",
        success: function (response) {
            let ttlrows = response.length;
            let tohtml = '';
            let stsdis = '';
            for(let i=0;i<ttlrows;i++){
                if(response[i].SCRSCN_SAVED){
                    stsdis = response[i].SCRSCN_SAVED;
                    if(stsdis=='1'){
                        stsdis = 'saved';
                    } else {
                        stsdis = 'not saved yet';
                    }

                } else {
                    stsdis = 'not saved yet';
                }
                tohtml+= '<tr style="cursor:pointer">'+
                '<td>'+response[i].SCRSCN_ID+'</td>'+
                '<td>'+response[i].SCRSCN_ITMCD+'</td>'+
                '<td class="text-right">'+response[i].SCRSCN_QTY+'</td>'+
                '<td>'+response[i].SCRSCN_LOTNO+'</td>'+
                '<td>'+response[i].SCRSCN_LUPDT+'</td>'+
                '<td>'+stsdis+'</td>'+
                '<td><i class="fas fa-trash text-warning"></i></td>'+
                '</tr>';
            }
            $("#SCRscn_tbladj tbody").html(tohtml);
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });
});
$("#SCRscn_ADJ").on('hidden.bs.modal', function(){
    fscn_balancing();
});
$('#SCRscn_tbladj tbody').on( 'click', 'td', function () {            
    SCRscn_selcol = $(this).index();         
});
$('#SCRscn_tbladj tbody').on( 'click', 'tr', function () { 
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#SCRscn_tbladj tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    let rid = $(this).closest("tr").find('td:eq(0)').text();
    let sts = $(this).closest("tr").find('td:eq(5)').text();
    if(SCRscn_selcol==6){
        if(sts.substring(0,3)=='sav'){
            alertify.warning('We could not delete saved item');
        } else {
            let konfrm = confirm('Are you sure ?');
            if (konfrm){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SCR/scnd_remove')?>",
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
$("#SCRscn_btnsave").click(function(){
    var konfirm = confirm("Are you sure ?");
    if(konfirm){
        var mDO = $("#SCRscn_txt_dn").val();
        if(mDO.trim()==''){
            $("#SCRscn_txt_dn").focus();
            return;
        }
        var a_mitem     = [];
        var a_reqqty    = [];
        var a_scanqty   = [];
        var a_savedqty  = [];
        var qtysum = 0;
        
        var tables   = $("#SCRscn_tbl tbody");                
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
            let mwh = document.getElementById('SCRscn_txt_wh').value;            
            $.ajax({
                type: "post",
                url: "<?=base_url('SCR/setscn')?>",
                data: {indo: mDO, initm: a_mitem, inreqqty: a_reqqty, inscanqty: a_scanqty, insavedqty: a_savedqty, inwh: mwh},
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
</script>