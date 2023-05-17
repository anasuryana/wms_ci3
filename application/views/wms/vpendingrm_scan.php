<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New"     id="pndscn_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save"    id="pndscn_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Document No.</span>
                    <input type="text" class="form-control" id="pndscn_txt_dn" required>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Warehouse</span>
                    <input type="text" class="form-control" id="pndscn_txt_wh" value="<?=$mwh?>" required readonly>
                    <input type="text" class="form-control" id="pndscn_txt_whnm" value="<?=$mwhnm?>" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="pndscn_txt_itmcd" required>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="pndscn_txt_itmqty" required >
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Lot No</span>
                    <input type="text" class="form-control" id="pndscn_txt_itmlot" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="progress" title="Scanning progress" style="height:60%">
                    <div id="pndscn_lblitemprogress"  class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                        style="width: 0%">
                        <small id="pndscn_lblprog">0%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="progress" title="Saving progress" style="height:60%">
                    <div id="pndscn_lblsaveprogress"  class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                    style="width: 0%">
                    <small id="pndscn_lblprogsave">0%</small>
                </div>
                </div>
            </div>
            <div class="col-md-2 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button id="pndscn_btn_adjust"  title="Adjust" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="pndscn_divku">
                    <table id="pndscn_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
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
<div class="modal fade" id="pndscn_ADJ">
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
                        <select id="pndscn_filteradj" class="form-select">

                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="pndscn_lbladjdatainfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" >
                        <table id="pndscn_tbladj" class="table table-hover table-sm table-bordered">
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
    $("#pndscn_divku").css('height', $(window).height()*61/100);
    var pndscn_selcol = 0;
    $("#pndscn_txt_dn").focus();
    $("#pndscn_btnnew").click(function (e) {
        $("#pndscn_tbl tbody").empty();
        $("#pndscn_txt_dn").val('');
        $("#pndscn_txt_dn").focus();
        $("#pndscn_lblitemprogress").css("width", "0%");
        $("#pndscn_lblprog").text('0%');
        $("#pndscn_lblsaveprogress").css("width", "0%");
        $("#pndscn_lblprogsave").text('0%');
    });
    $("#pndscn_txt_itmcd").keypress(function(e){
        if(e.which==13){
            var thisval = $(this).val();
            if(thisval.substring(0,3)!='3N1'){
                alertify.warning('Unknown Format C3 Label');
                return;
            }
            thisval = thisval.substring(3, thisval.length);
            $(this).val(thisval);
            var mdo  = $("#pndscn_txt_dn").val();
            if(mdo.trim()==''){
                $("#pndscn_txt_dn").focus();
                return;
            }

            if (thisval.trim()!=''){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('PND/scn_itemdo_check')?>",
                    data: {inDO: mdo , inITEM: thisval},
                    dataType: "text",
                    success: function (response) {
                        switch(response){
                            case '1':
                                $("#pndscn_txt_itmqty").focus();break;
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
        let mval = $("#pndscn_txt_dn").val();
        $.ajax({
            type: "get",
            url: "<?=base_url('PND/scn_balancing')?>",
            data: {inDO: mval},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let tohtml = '';
                let mbalanc = 0;
                let msavedqty = 0;
                let mscan = 0;
                let mlastscantime = '';
                let stsicon = '';
                for(let i=0;i<ttlrows;i++){
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
                    if(numeral(response.data[i].PND_QTY).value()==numeral(msavedqty).value()){
                        stsicon = '<i class="fas fa-check text-success"></i>';
                    } else {
                        stsicon = '<i class="fas fa-question-circle text-warning"></i>';
                    }
                    mbalanc = numeral(response.data[i].PND_QTY).value()-numeral(mscan).value();
                    tohtml += '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response.data[i].PND_ITMCD+'</td>'+
                    '<td class="text-right">'+numeral(response.data[i].PND_QTY).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(mscan).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(mbalanc).format(',')+'</td>'+
                    '<td class="text-right">'+numeral(msavedqty).format(',')+'</td>'+
                    '<td class="text-center">'+mlastscantime+'</td>'+
                    '<td class="text-center" style="cursor:pointer">'+stsicon+'</td>'+
                    '</tr>';
                }
                $("#pndscn_tbl tbody").html(tohtml);
                if(response.datahead.length>0){
                    let vprgs = response.datahead[0].PROGRESS;
                    if(vprgs.charAt(0)=='.'){
                        vprgs='0'+vprgs;
                    }
                    $("#pndscn_lblitemprogress").css("width", numeral(vprgs).format('0,0')+"%");
                    $("#pndscn_lblprog").text(numeral(vprgs).format('0,0.000')+'%');
                }
                if(response.datasave.length>0){
                    let vprgs = response.datasave[0].PROGRESS;
                    if(vprgs.charAt(0)=='.'){
                        vprgs='0'+vprgs;
                    }
                    $("#pndscn_lblsaveprogress").css("width", numeral(vprgs).format('0,0')+"%");
                    $("#pndscn_lblprogsave").text(numeral(vprgs).format('0,0.000')+'%');
                }
            }
        });
    }
$("#pndscn_txt_dn").keypress(function (e) {
    if (e.key==='Enter'){
        let mval = $(this).val();
        if(mval.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/scn_balancing')?>",
                data: {inDO: mval},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    let tohtml = '';
                    let mbalanc = 0;
                    let msavedqty = 0;
                    let mscan = 0;
                    let mlastscantime = '';
                    let mwh ='';
                    let mwh_nm = '';
                    let mlabelinfo = '';
                    let stsicon = '';
                    let mDT = '';
                    let mTM = '';
                    if(ttlrows>0){
                        for(let i=0;i<ttlrows;i++){
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
                            if(numeral(response.data[i].PND_QTY).value()==numeral(msavedqty).value()){
                                stsicon = '<i class="fas fa-check text-success"></i>';
                            } else {
                                stsicon = '<i class="fas fa-question-circle text-warning"></i>';
                            }
                            mbalanc = numeral(response.data[i].PND_QTY).value()-numeral(mscan).value();
                            tohtml += '<tr>'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+response.data[i].PND_ITMCD.trim()+'</td>'+
                            '<td class="text-right">'+numeral(response.data[i].PND_QTY).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(mscan).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(mbalanc).format(',')+'</td>'+
                            '<td class="text-right">'+numeral(msavedqty).format(',')+'</td>'+
                            '<td class="text-center">'+mlastscantime+'</td>'+
                            '<td class="text-center" style="cursor:pointer">'+stsicon+'</td>'+
                            '</tr>';
                        }
                        document.getElementById('pndscn_txt_itmcd').focus();
                    } else {
                        alertify.warning("DO Number not found");
                    }
                    $("#pndscn_tbl tbody").html(tohtml);
                    let vprgs ='';
                    if(response.datahead.length>0){
                        vprgs = response.datahead[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }
                        $("#pndscn_lblitemprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#pndscn_lblprog").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                    if(response.datasave.length>0){
                        vprgs = response.datasave[0].PROGRESS;
                        if(vprgs.charAt(0)=='.'){
                            vprgs='0'+vprgs;
                        }
                        $("#pndscn_lblsaveprogress").css("width", numeral(vprgs).format('0,0')+"%");
                        $("#pndscn_lblprogsave").text(numeral(vprgs).format('0,0.000')+'%');
                    }
                }
            });
        } else {
            alertify.warning('DO Data could not be blank');
        }
    }
});

$("#pndscn_txt_itmqty").keypress(function (e) {
    if(e.key==='Enter'){
        let mthisval = $(this).val();
        let mdo = $("#pndscn_txt_dn").val();
        let mitm = $("#pndscn_txt_itmcd").val();
        if (mdo.trim()==''){
            alertify.warning('Please entry DO data');
            $("#pndscn_txt_dn").focus();
        }

        if(mitm.trim()==''){
            $("#pndscn_txt_itmcd").focus();
            return;
        }

        if(mthisval.trim()!=''){
            if(mthisval.substring(0,3)=='3N2'){
                let mthis_ar    = mthisval.split(' ');
                let mqty        = 0;
                let mlot        ='';
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
                $("#pndscn_txt_itmlot").val(mlot);
                let mgetbal = '-';
                let tables   = $("#pndscn_tbl tbody");
                tables.find('tr').each(function (i) {
                    let $tds = $(this).find('td'),
                            ritem =  $tds.eq(1).text(),
                            rbalanc = $tds.eq(4).text();
                    if(ritem.trim()==mitm.trim()){
                       mgetbal = rbalanc; return false;
                    }
                });
                if(Number(mqty)==0){
                    alertify.warning("QTY must greater than zero");
                    $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');
                    $("#pndscn_txt_itmcd").val('');$("#pndscn_txt_itmcd").focus();
                    return;
                }
                if(mqty> numeral(mgetbal).value()){
                    $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');
                    $("#pndscn_txt_itmcd").val('');$("#pndscn_txt_itmcd").focus();
                    alertify.warning("QTY > Balance !" );
                    return;
                }
                $.ajax({
                    type: "post",
                    url: "<?=base_url('PND/scn_set')?>",
                    data: {inDO: mdo, inITM: mitm, inQTY: mqty, inLOT: mlot},
                    dataType: "text",
                    success: function (response) {
                        if(response.substring(0,2)=='11'){
                            alertify.success('OK');
                            let ares = response.split('_');
                            if(isNaN(ares[1])){
                                $("#pndscn_txt_itmcd").val('');
                                $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');
                                $("#pndscn_txt_itmcd").focus();
                            } else {
                                if(Number(ares[1])>0){
                                    $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');$("#pndscn_txt_itmqty").focus();
                                } else {
                                    $("#pndscn_txt_itmcd").val('');
                                    $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');
                                    $("#pndscn_txt_itmcd").focus();
                                }
                            }
                            fscn_balancing();
                        } else {
                            if(response=='0'){
                                alertify.message('Item with document does not match');
                                $("#pndscn_txt_itmcd").val('');
                                $("#pndscn_txt_itmqty").val('');$("#pndscn_txt_itmlot").val('');
                                $("#pndscn_txt_itmcd").focus();
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
$("#pndscn_btn_adjust").click(function(){
    $("#pndscn_ADJ").modal('show');
    let tables   = $("#pndscn_tbl tbody");
    let tohtml = '<option value="-">Choose</option>';
    tables.find('tr').each(function (i) {
        let $tds = $(this).find('td'),
                ritem =  $tds.eq(1).text();
        tohtml += '<option value="'+ritem+'">'+ritem+'</option>';
    });
    $("#pndscn_filteradj").html(tohtml);
});
$("#pndscn_filteradj").change(function(){
    let mdo = $("#pndscn_txt_dn").val();
    let mitem = $(this).val();
    $.ajax({
        type: "get",
        url: "<?=base_url('PND/scnd_list_bydo_item')?>",
        data: {inDO: mdo, inITEM: mitem},
        dataType: "json",
        success: function (response) {
            let ttlrows = response.length;
            let tohtml = '';
            let stsdis = '';
            for(let i=0;i<ttlrows;i++){
                if(response[i].PNDSCN_SAVED){
                    stsdis = response[i].PNDSCN_SAVED;
                    if(stsdis=='1'){
                        stsdis = 'saved';
                    } else {
                        stsdis = 'not saved yet';
                    }

                } else {
                    stsdis = 'not saved yet';
                }
                tohtml+= '<tr style="cursor:pointer">'+
                '<td>'+response[i].PNDSCN_ID+'</td>'+
                '<td>'+response[i].PNDSCN_ITMCD+'</td>'+
                '<td class="text-right">'+response[i].PNDSCN_QTY+'</td>'+
                '<td>'+response[i].PNDSCN_LOTNO+'</td>'+
                '<td>'+response[i].PNDSCN_LUPDT+'</td>'+
                '<td>'+stsdis+'</td>'+
                '<td><i class="fas fa-trash text-warning"></i></td>'+
                '</tr>';
            }
            $("#pndscn_tbladj tbody").html(tohtml);
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });
});
$("#pndscn_ADJ").on('hidden.bs.modal', function(){
    fscn_balancing();
});
$('#pndscn_tbladj tbody').on( 'click', 'td', function () {
    pndscn_selcol = $(this).index();
});
$('#pndscn_tbladj tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('table-active') ) {
        $(this).removeClass('table-active');
    } else {
        $('#pndscn_tbladj tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    let rid = $(this).closest("tr").find('td:eq(0)').text();
    let sts = $(this).closest("tr").find('td:eq(5)').text();
    if(pndscn_selcol==6){
        if(sts.substring(0,3)=='sav'){
            alertify.warning('We could not delete saved item');
        } else {
            let konfrm = confirm('Are you sure ?');
            if (konfrm){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('PND/scnd_remove')?>",
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
$("#pndscn_btnsave").click(function(){    
    if(confirm("Are you sure ?")){
        let mDO = $("#pndscn_txt_dn").val();
        if(mDO.trim()==''){
            $("#pndscn_txt_dn").focus();
            return;
        }
        let a_mitem     = [];
        let a_reqqty    = [];
        let a_scanqty   = [];
        let a_savedqty  = [];
        let qtysum = 0;

        let tables = $("#pndscn_tbl tbody");
        tables.find('tr').each(function (i) {
            let $tds = $(this).find('td'),
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
            let mwh = document.getElementById('pndscn_txt_wh').value;
            $.ajax({
                type: "post",
                url: "<?=base_url('PND/setscn')?>",
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