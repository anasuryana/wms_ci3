<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="pndser_btn_new"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="pndser_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="pndser_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="pndser_txt_doc" placeholder="<<auto number>>" readonly>
                    <button class="btn btn-primary" id="pndser_btnmod"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="pndser_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="pndser_btnplus" onclick="pndser_btnadd()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="pndser_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="pndser_divku">
                    <table id="pndser_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Item Code</th>
                                <th>Document Reference</th>
                                <th class="text-right">QTY</th>
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
<div class="modal fade" id="pndser_MOD">
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
                        <select id="pndser_itm_srchby" onchange="pndser_e_fokus()" class="form-select">
                            <option value="no">Document No</option>
                            <option value="rmrk">Remarks</option>
                        </select>
                        <input type="text" class="form-control" id="pndser_txtsearch" onkeypress="pndser_e_search(event)" maxlength="15" onfocus="this.select()" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="pndser_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="pndser_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
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

<div class="modal fade" id="pndser_MODprep">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Warehouse</span>
                        <select id="pndser_wh" class="form-select">
                           <?php
$todis = '';
foreach ($lwh as $r) {
    $todis .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . '</option>';
}
echo $todis;
?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="pndser_itmlist_srchby"  class="form-select">
                            <option value="id">ID</option>
                            <option value="cd">Item Code</option>
                            <option value="doc">Job Number</option>
                        </select>
                        <input type="text" class="form-control" id="pndser_txtsearchitem" maxlength="16" onfocus="this.select()" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <span class="badge bg-info" id="pndser_lbltbl"></span>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-center">
                    <button class="btn btn-sm btn-outline-primary" id="pndser_btngetsel">Get selected list</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="pndsermod_divku">
                        <table id="pndsermod_tblitem" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" class="form-check-input" id="pndser_ckall"></th>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>Job Number</th>
                                    <th class="text-right">Qty</th>
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
    var pndser_tbllength         = 1;
    var pndser_tblrowindexsel    = '';
    var pndser_tblcolindexsel    = '';
    var pndser_ar_item_ser = [];
    var pndser_ar_item_cd = [];
    var pndser_ar_item_job = [];
    var pndser_ar_item_qty = [];
    var pndser_editmode =false;
    $("#pndser_divku").css('height', $(window).height()*68/100);
    $("#pndser_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#pndser_txt_date").datepicker('update', '<?=$dt?>');
    $("#pndser_MODprep").on('shown.bs.modal', function(){
        document.getElementById('pndser_txtsearchitem').focus();
    });
    $("#pndser_wh").change(function (e) {
        document.getElementById('pndser_txtsearchitem').focus();
    });
    $("#pndser_itmlist_srchby").change(function (e) {
        document.getElementById('pndser_txtsearchitem').focus();
    });
    function pndser_btnadd(){
        $("#pndser_MODprep").modal('show');
        pndser_renumberrow();
    }

    $("#pndser_txtsearchitem").keypress(function (e) {
        if(e.which==13){
            document.getElementById('pndser_lbltbl').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
            let msearch = $(this).val();
            let mwh = document.getElementById('pndser_wh').value;
            let search_by = document.getElementById('pndser_itmlist_srchby').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/getser')?>",
                data: {insearch: msearch, inwh: mwh, inby: search_by},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("pndsermod_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("pndsermod_tblitem");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("pndsermod_tblitem");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let mckall = myfrag.getElementById("pndser_ckall");
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createElement('input');
                        newText.setAttribute("type", "checkbox");
                        newText.classList.add("form-check-input");
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].ITH_SER);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].ITH_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].SER_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response.data[i].SER_QTY).format('0,0'));
                        newcell.style.cssText = "text-align:right";
                        newcell.appendChild(newText);
                    }
                    let mrows = tableku2.getElementsByTagName("tr");
                    function clooptable(){
                        let cktemp ;
                        for(let x=0;x<mrows.length;x++){
                            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
                            cktemp.checked=mckall.checked;
                        }
                    }
                    mckall.onclick = function(){clooptable()};
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                    document.getElementById('pndser_lbltbl').innerHTML = '';
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    function pndser_renumberrow(){
        let rows =1;
        let table = $("#pndser_tbl tbody");
        table.find('tr').each(function (i) {
            let $tds = $(this).find('td');
                $tds.eq(0).text(rows);
            rows++;
        });
    }

    $("#pndser_btn_new").click(function (e) {
        $("#pndser_tbl tbody").empty();
        $("#pndsermod_tblitem tbody").empty();
        document.getElementById('pndser_txt_doc').value='';
        pndser_ar_item_ser =[];
        pndser_ar_item_cd =[];
        pndser_ar_item_job=[];
        pndser_ar_item_qty=[];
        pndser_editmode =false;
    });
    $("#pndser_btnmins").click(function (e) {
        console.log('button mins : '+pndser_tblrowindexsel);
        if(pndser_tblrowindexsel===''){
            return;
        }
        let konf = confirm("Are you sure want to delete ?");

        if(konf){
            let table = $("#pndser_tbl tbody");
            let mitem = table.find('tr').eq(pndser_tblrowindexsel).find('td').eq(1).text();
            if(!pndser_editmode){
                table.find('tr').eq(pndser_tblrowindexsel).remove();
                pndser_renumberrow();
            } else {
                $.ajax({
                    type: "get",
                    url: "<?=base_url('PND/remove')?>",
                    data: {inid:mitem},
                    dataType: "json",
                    success: function (response) {
                        if(response.data[0].cd=='11'){
                            alertify.message(response.data[0].msg);
                            let table = $("#pndser_tbl tbody");
                            let mitem = table.find('tr').eq(pndser_tblrowindexsel).find('td').eq(1).text();
                            table.find('tr').eq(pndser_tblrowindexsel).remove();
                            pndser_tblrowindexsel='';
                            pndser_renumberrow();
                        } else {
                            alertify.message(response.data[0].msg);
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    });
    $('#pndser_tbl tbody').on( 'click', 'tr', function () {
        pndser_tblrowindexsel =$(this).index();
        console.log(pndser_tblrowindexsel);
        if ($(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#pndser_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
    });
    $('#pndser_tbl tbody').on( 'click', 'td', function () {
        pndser_tblcolindexsel = $(this).index();
    });
    $("#pndser_btn_save").click(function (e) {
        if(confirm('Are you sure want to save ?')){
            let mdoc = document.getElementById('pndser_txt_doc').value;
            let mdate = document.getElementById('pndser_txt_date').value;
            let mtbl = document.getElementById('pndser_tbl');
            let mtbltr = mtbl.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let mitem,mlot, mqty, mser, mremark;
            let aitem = [], aqty = [], aser = [], alot= [];
            let aremark = [];
            if(ttlrows>1){
                for(let i=1;i<ttlrows;i++){
                    mser = mtbl.rows[i].cells[1].innerText;
                    mitem = mtbl.rows[i].cells[2].innerText;
                    mlot = mtbl.rows[i].cells[3].innerText;
                    mqty = numeral(mtbl.rows[i].cells[4].innerText).value();
                    mremark = mtbl.rows[i].cells[5].innerText;
                    if(mitem.trim()!=''){
                        if(!isNaN(mqty)){
                            aqty.push(mqty);
                            alot.push(mlot);
                            aitem.push(mitem);
                            aser.push(mser);
                            aremark.push(mremark);
                        }
                    }
                }
                if(aitem.length>0){
                    if(mdoc!=''){
                        $("#pndsermod_tblitem tbody").empty();
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('PND/editser')?>",
                            data: {initem: aitem, inlot: alot,inqty: aqty, indoc: mdoc, indate: mdate, inser: aser, inremark: aremark},
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
                            url: "<?=base_url('PND/setser')?>",
                            data: {initem: aitem ,inqty: aqty, indate: mdate, inser: aser, inremark: aremark},
                            dataType: "json",
                            success: function (response) {
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('pndser_txt_doc').value=response.data[0].ref;
                                    alertify.message('Document of Pending is ready to be printed');

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
    $("#pndser_btnmod").click(function (e) {
        $("#pndser_MOD").modal('show');
    });
    function pndser_e_fokus(){
        document.getElementById('pndser_txtsearch').focus();
    }
    $("#pndser_MOD").on('shown.bs.modal', function(){
        document.getElementById('pndser_txtsearch').focus();
    });
    function pndser_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('pndser_txtsearch').value;
            let mby = document.getElementById('pndser_itm_srchby').value;
            document.getElementById('pndser_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/searchser')?>",
                data: {inid: msearch, inby: mby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].PNDSER_DOC+"</td>"+
                        "<td>"+response[i].PNDSER_DT+"</td>"+
                        "<td class='text-right'>"+response[i].TTLITEM+"</td>"+
                        "<td>"+response[i].REMARK+"</td>"+
                        "</tr>";
                    }
                    $("#pndser_tblsaved tbody").html(tohtml);
                    document.getElementById('pndser_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    $('#pndser_tblsaved tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#pndser_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        var mdoc    = $(this).closest("tr").find('td:eq(0)').text();
        var mdate   = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById('pndser_txt_doc').value=mdoc.trim();
        $("#pndser_txt_date").datepicker('update', mdate);
        $('#pndser_MOD').modal('hide');pndser_e_getdetail(mdoc);
    });

    function pndser_e_getdetail(pdoc){
        $.ajax({
            type: "get",
            url: "<?=base_url('PND/getbyidser')?>",
            data: {indoc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                let tohtml = '';
                for(let i=0;i<ttlrows;i++){
                    tohtml += "<tr style='cursor:pointer'>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response[i].PNDSER_SER+'</td>'+
                    '<td>'+response[i].SER_ITMID+'</td>'+
                    '<td>'+response[i].SER_DOC+'</td>'+
                    '<td class="text-right">'+numeral(response[i].PNDSER_QTY).format('0,0')+'</td>'+
                    '<td contenteditable="true" class="bg-white">'+response[i].PNDSER_REMARK+'</td>'+
                    "</tr>";
                }
                $("#pndser_tbl tbody").html(tohtml);
                document.getElementById('pndser_lblinfo').innerText = ttlrows + ' row(s) found';
                pndser_tbllength = $('#pndser_tbl tbody > tr').length;
                pndser_editmode = true;
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#pndser_btn_print").click(function (e) {
        let pendingdoc = document.getElementById('pndser_txt_doc').value;
        Cookies.set('CKPNDDOCSER_NO', pendingdoc , {expires:365});
        window.open("<?=base_url('printpendingser_doc')?>" ,'_blank');
    });

    $("#pndser_btngetsel").click(function (e) {
        let tabell = document.getElementById("pndsermod_tblitem");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let cktemp,ttlcek;

        ttlcek= 0;
        for(let x=0;x<mrows.length;x++){
            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
            if(cktemp.checked){
                if(pndser_ar_item_ser.length==0){
                    pndser_ar_item_ser.push(tableku2.rows[x].cells[1].innerHTML);
                    pndser_ar_item_cd.push(tableku2.rows[x].cells[2].innerHTML);
                    pndser_ar_item_job.push(tableku2.rows[x].cells[3].innerHTML);
                    pndser_ar_item_qty.push(tableku2.rows[x].cells[4].innerHTML);
                } else {
                    let isexist = false;
                    let ttl = pndser_ar_item_cd.length;
                    for(let i =0;i<ttl;i++){
                        if(tableku2.rows[x].cells[1].innerHTML==pndser_ar_item_ser[i]){
                            isexist =true;break;
                        }
                    }
                    if(!isexist){
                        pndser_ar_item_ser.push(tableku2.rows[x].cells[1].innerHTML);
                        pndser_ar_item_cd.push(tableku2.rows[x].cells[2].innerHTML);
                        pndser_ar_item_job.push(tableku2.rows[x].cells[3].innerHTML);
                        pndser_ar_item_qty.push(tableku2.rows[x].cells[4].innerHTML);
                    }
                }
            }
        }
        let ttlsel = pndser_ar_item_ser.length;
        let tohtml = '';
        for(let i=0;i<ttlsel;i++){
            tohtml += "<tr>"+
            "<td>"+(i+1)+"</td>"+
            "<td>"+pndser_ar_item_ser[i]+"</td>"+
            "<td>"+pndser_ar_item_cd[i]+"</td>"+
            "<td>"+pndser_ar_item_job[i]+"</td>"+
            "<td class='text-right'>"+pndser_ar_item_qty[i]+"</td>"+
            "<td contenteditable='true'></td>"+
            "</tr>";
        }
        $("#pndser_tbl tbody").html(tohtml);
        $("#pndser_MODprep").modal('hide');
    });
</script>