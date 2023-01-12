<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="pnd_btn_new"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="pnd_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="pnd_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="pnd_txt_doc" placeholder="<<auto number>>" readonly>
                    <button class="btn btn-primary" id="pnd_btnmod"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="pnd_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="pnd_btnplus" onclick="pnd_btnadd()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="pnd_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="pnd_divku">
                    <table id="pnd_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Item Code</th>
                                <th>Lotno</th>
                                <th class="text-right">QTY</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>
                                <td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>
                                <td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" style="text-align: right"></td>
                                <td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pnd_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Pending Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="pnd_itm_srchby" onchange="pnd_e_fokus()" class="form-select">
                            <option value="no">Document No</option>
                            <option value="rmrk">Remarks</option>
                        </select>
                        <input type="text" class="form-control" id="pnd_txtsearch" onkeypress="pnd_e_search(event)" maxlength="15" onfocus="this.select()" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="pnd_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="pnd_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Document</th>
                                    <th>Date</th>
                                    <th class="text-right">Total Item</th>
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
    </div>
</div>
<script>
    var pnd_tbllength         = 1;
    var pnd_tblrowindexsel    = '';
    var pnd_tblcolindexsel    = '';
    var pnd_e_col1;
    $("#pnd_divku").css('height', $(window).height()*68/100);
    $("#pnd_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#pnd_txt_date").datepicker('update', '<?=$dt?>');
    pnd_e_col1 = function (e) {
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };

        if(e.shiftKey && keyCode==9){
            if(pnd_tblcolindexsel>1){
                pnd_tblcolindexsel--;
            }
        } else{
            switch(keyCode){
                case 9:
                    if(pnd_tblcolindexsel<4)
                        { pnd_tblcolindexsel++; }
                    else{
                        pnd_tblcolindexsel=1;
                        if(pnd_tblrowindexsel<(pnd_tbllength-1))
                        {pnd_tblrowindexsel++;}
                    }
            }
        }
        if (e.ctrlKey) {
            switch (keyCode) {
                case arrow.up:
                    if(pnd_tblrowindexsel>0){
                        var tables = $("#pnd_tbl tbody");
                        tables.find('tr').eq(--pnd_tblrowindexsel).find('td').eq(pnd_tblcolindexsel).find('input').focus();
                    }
                    break;
                case arrow.down:
                    if(pnd_tblrowindexsel<(pnd_tbllength-1)){
                        let tables = $("#pnd_tbl tbody");
                        tables.find('tr').eq(++pnd_tblrowindexsel).find('td').eq(pnd_tblcolindexsel).find('input').focus();
                    }
                    break;
                case 78:///N
                    pnd_btnadd();
                    break;
                case 68://D
                    $("#pnd_tbl tbody > tr:last").remove();pnd_renumberrow();
                    break;
            }
        }
    };
    function pnd_btnadd(){
        $('#pnd_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
        '<td></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" style="text-align: right"></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" maxlength="50"></td>'+
        '</tr>');
        pnd_tbllength = $('#pnd_tbl tbody > tr').length;
        // pnd_tblrowindexsel = pnd_tbllength-1;
        pnd_renumberrow();
    }

    function pnd_renumberrow(){
        let rows =1;
        let table = $("#pnd_tbl tbody");
        table.find('tr').each(function (i) {
            let $tds = $(this).find('td');
                $tds.eq(0).text(rows);
            rows++;
        });
    }
    function pnd_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/plain');
        let adatapas = datapas.split('\n');
        let ttlrowspasted = 0;
        for(let c=0;c<adatapas.length;c++){
            if(adatapas[c].trim()!=''){
                ttlrowspasted++;
            }
        }
        let table = $("#pnd_tbl tbody");
        let incr =0;
        if((pnd_tbllength-pnd_tblrowindexsel)<ttlrowspasted){
            let needRows = ttlrowspasted - (pnd_tbllength-pnd_tblrowindexsel);
            for(let i = 0;i<needRows;i++){
                pnd_btnadd();
            }
        }
        for(let i=0;i<ttlrowspasted;i++){
            let mcol = adatapas[i].split('\t');
            let ttlcol = mcol.length;
            for(let k=0;(k<ttlcol) && (k<5);k++){
                table.find('tr').eq((i+pnd_tblrowindexsel)).find('td').eq((k+pnd_tblcolindexsel)).find('input').val(mcol[k].trim());
            }
        }
        event.preventDefault();
    }
    $("#pnd_btn_new").click(function (e) {
        $("#pnd_tbl tbody").empty();
        document.getElementById('pnd_txt_doc').value='';
    });
    $("#pnd_btnmins").click(function (e) {
        if(confirm("Are you sure want to delete ?")){
            let table = $("#pnd_tbl tbody");
            let mitem = table.find('tr').eq(pnd_tblrowindexsel).find('td').eq(1).find('input').val();
            let mitemlot = table.find('tr').eq(pnd_tblrowindexsel).find('td').eq(2).find('input').val();
            $.ajax({
                type: "POST",
                url: "<?=base_url('PND/remove_pnd_doc')?>",
                data: {doc:pnd_txt_doc.value, itemcd: mitem, itemlot:  mitemlot},
                dataType: "json",
                success: function (response) {
                    alertify.message(response.status.msg)
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
            table.find('tr').eq(pnd_tblrowindexsel).remove();
            pnd_renumberrow();
        }
    });
    $('#pnd_tbl tbody').on( 'click', 'tr', function () {
        pnd_tblrowindexsel = $(this).index();
        if ($(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#pnd_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
    });
    $('#pnd_tbl tbody').on( 'click', 'td', function () {
        pnd_tblcolindexsel = $(this).index();
    });
    $("#pnd_btn_save").click(function (e) {
        let konf = confirm('Are you sure want to save ?');
        if(konf){
            let mdoc = document.getElementById('pnd_txt_doc').value;
            let mdate = document.getElementById('pnd_txt_date').value;
            let mtbl = document.getElementById('pnd_tbl');
            let mtbltr = mtbl.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let mitem,mlot, mqty, mremark;
            let aitem = [], aqty = [], aremark = [], alot= [];
            if(ttlrows>1){
                for(let i=1;i<ttlrows;i++){
                    mitem = mtbl.rows[i].cells[1].getElementsByTagName('input')[0].value;
                    mlot = mtbl.rows[i].cells[2].getElementsByTagName('input')[0].value;
                    mqty = mtbl.rows[i].cells[3].getElementsByTagName('input')[0].value;
                    mremark = mtbl.rows[i].cells[4].getElementsByTagName('input')[0].value;
                    if(mitem.trim()!=''){
                        if(!isNaN(mqty)){
                            aqty.push(mqty);
                            alot.push(mlot);
                            aitem.push(mitem);
                            aremark.push(mremark);
                        }
                    }
                }
                if(aitem.length>0){
                    if(mdoc!=''){
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('PND/edit')?>",
                            data: {initem: aitem, inlot: alot,inqty: aqty, indoc: mdoc, indate: mdate, inremark: aremark},
                            dataType: "json",
                            success: function (response) {
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                }
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    } else{
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('PND/set')?>",
                            data: {initem: aitem, inlot: alot ,inqty: aqty, indate: mdate, inremark: aremark},
                            dataType: "json",
                            success: function (response) {
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('pnd_txt_doc').value=response.data[0].ref;
                                    $("#pnd_tbl tbody").empty();
                                }
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    }
                } else {
                    alertify.message('no data to be processed');
                }
            }
        }
    });
    $("#pnd_btnmod").click(function (e) {
        $("#pnd_MOD").modal('show');
    });
    function pnd_e_fokus(){
        document.getElementById('pnd_txtsearch').focus();
    }
    $("#pnd_MOD").on('shown.bs.modal', function(){
        document.getElementById('pnd_txtsearch').focus();
    });
    function pnd_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('pnd_txtsearch').value;
            let mby = document.getElementById('pnd_itm_srchby').value;
            document.getElementById('pnd_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/search')?>",
                data: {inid: msearch, inby: mby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].PND_DOC+"</td>"+
                        "<td>"+response[i].PND_DT+"</td>"+
                        "<td class='text-right'>"+response[i].TTLITEM+"</td>"+
                        "<td>"+response[i].REMARK+"</td>"+
                        "</tr>";
                    }
                    $("#pnd_tblsaved tbody").html(tohtml);
                    document.getElementById('pnd_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    $('#pnd_tblsaved tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#pnd_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        var mdoc    = $(this).closest("tr").find('td:eq(0)').text();
        var mdate   = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById('pnd_txt_doc').value=mdoc.trim();
        $("#pnd_txt_date").datepicker('update', mdate);
        $('#pnd_MOD').modal('hide');pnd_e_getdetail(mdoc);
    });

    function pnd_e_getdetail(pdoc){
        $.ajax({
            type: "get",
            url: "<?=base_url('PND/getbyid')?>",
            data: {indoc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                let tohtml = '';
                for(let i=0;i<ttlrows;i++){
                    tohtml += "<tr style='cursor:pointer'>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" value="'+response[i].PND_ITMCD+'"></td>'+
                    '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" value="'+response[i].PND_ITMLOT+'"></td>'+
                    '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" style="text-align:right" value="'+numeral(response[i].PND_QTY).value()+'"></td>'+
                    '<td><input type="text" class="form-control form-control-sm" onkeydown="pnd_e_col1(event)" onpaste="pnd_e_pastecol1(event)" value="'+response[i].PND_REMARK+'"></td>'+
                    "</tr>";
                }
                $("#pnd_tbl tbody").html(tohtml);
                document.getElementById('pnd_lblinfo').innerText = ttlrows + ' row(s) found';
                pnd_tbllength = $('#pnd_tbl tbody > tr').length;
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#pnd_btn_print").click(function (e) {
        let pendingdoc = document.getElementById('pnd_txt_doc').value;
        if(pendingdoc.trim().length>0){
            Cookies.set('CKPNDDOC_NO', pendingdoc , {expires:365});
            window.open("<?=base_url('printpending_doc')?>" ,'_blank');
        } else {
            alertify.message("Please select document first");
        }
    });
</script>