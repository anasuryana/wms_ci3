<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">DO Number</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_docnoorigin" readonly>                    
                    <button class="btn btn-primary" id="rcvcustoms_fg_btnmod"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Doc Type</label>                    
                    <select id="rcvcustoms_fg_typedoc" class="form-select">
                        <option value="23">BC 2.3</option>
                        <option value="27">BC 2.7</option>
                        <option value="40">BC 4.0</option>                                        
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Receiving Status</label>                    
                    <select id="rcvcustoms_fg_zsts" class="form-select">                                                         
                    </select>                    
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">NoAju</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_noaju" maxlength="26">
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">NoPen</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_regno" maxlength="6">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Doc. Date</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_dd">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Rcv. Date</label>
                    <input type="text" class="form-control" id="rcvcustoms_fg_rcvdate">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">TPB Type</label>                    
                    <select id="rcvcustoms_fg_typetpb" class="form-select">
                        <?php foreach($ltpb_type as $r) {?>
                            <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">KPPBC</label>                    
                    <select id="rcvcustoms_fg_kppbc" class="form-select">
                        <?php 
                        $toprint = '';
                        foreach($officelist as $r){
                            $toprint .= '<option value="'.$r['KODE_KANTOR'].'">'.$r['URAIAN_KANTOR'].'</option>';
                        }
                        echo $toprint;
                        ?>
                    </select>                    
                    <label class="input-group-text">...</label>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Total Amount</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_amount">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Net Weight</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_NW">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Gross Weight</label>                    
                    <input type="text" class="form-control" id="rcvcustoms_fg_GW">
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="rcvcustoms_fg_save" title="Save"><i class="fas fa-save"></i></button>                    
                </div>                
            </div>
            <div class="col-md-6 mb-3 text-end">
                <span class="badge bg-info" id="rcvcustoms_fg_lbltbl"></span>
            </div>
        </div>        
        
        <div class="row">
            <div class="col-md-12 mb-1" >  
                <div class="table-responsive" id="rcvcustoms_fg_divku">
                    <table id="rcvcustoms_fg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th> <!-- 0 -->
                                <th>NoUrut</th> <!-- 1 -->
                                <th>NoPen</th> <!-- 2 -->
                                <th>PO No</th> <!-- 3 -->
                                <th>Date</th> <!-- 4 -->
                                <th class="d-none">CustomerID</th> <!-- 5 -->
                                <th>Customer</th> <!-- 6 -->
                                <th>Currency</th> <!-- 7 -->
                                <th>Item Code</th> <!-- 8 -->
                                <th>Item Name</th> <!-- 9 -->
                                <th>QTY</th> <!-- 10 -->
                                <th>UM</th> <!-- 11 -->
                                <th>Price</th> <!-- 12 -->
                                <th>Amount</th> <!-- 13 -->
                                <th class="d-none">WH</th> <!-- 14 -->
                                <th class="d-none">GRLNO</th> <!-- 15 -->
                                <th>HS Code</th> <!-- 16 -->
                                <th title="Bea Masuk">BM</th> <!-- 17 -->
                                <th>PPN</th> <!-- 18 -->
                                <th>PPH</th> <!-- 19 -->
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
<div class="modal fade" id="rcvcustoms_fg_DTLMOD">
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
                        <input type="text" class="form-control" id="rcvcustoms_fg_txt_search">
                    </div>
                </div>
            </div>   
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text"><input type="checkbox" checked id="rcvcustoms_fg_ck"></label>                        
                        <select id="rcvcustoms_fg_monthfilter" class="form-select">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>                
                        <input type="number" class="form-control" id="rcvcustoms_fg_year" maxlength="4">
                        
                    </div>
                </div>
            </div>   
            <div class="row">
                <div class="col-md-12 mb-1">                    
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Business Group</label>
                        <select id="rcvcustoms_fg_supfilter" class="form-select">
                            <?=$lsupplier?>
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_datefilter" readonly>                        
                        <button class="btn btn-secondary" id="rcvcustoms_fg_btn_filterdate" onclick="rcvcustoms_fg_btn_filterdate_e_click()"><i class="fas fa-backspace"></i> </button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                           Search by
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="rcvcustoms_fg_rad_do" class="form-check-input" name="optradio_fg" value="do" checked>
                        <label class="form-check-label" for="rcvcustoms_fg_rad_do">
                        DO No
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="rcvcustoms_fg_rad_item" class="form-check-input" name="optradio_fg" value="item">
                        <label class="form-check-label" for="rcvcustoms_fg_rad_item">
                        Item Code
                        </label>
                    </div>
                </div>
            </div>         
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="lblinfo_rcvcustoms_fg_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="rcvcustoms_fg_divku_search">
                    <table id="rcvcustoms_fg_tbldono" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>DO No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th class="text-end">Status</th>
                                <th class="text-center">HSCODE</th>
                                <th class="text-end">BM</th>
                                <th class="text-end">PPN</th>
                                <th class="text-end">PPH</th>
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

<div class="modal fade" id="rcvcustoms_fg_PROGRESS">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>            
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    Please wait ...
                </div>                
            </div>            
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <i class="fas fa-spinner fa-spin fa-7x text-info"></i>
                </div>                
            </div>            
        </div>             
      </div>
    </div>
</div>

<script>    
    $("#rcvcustoms_fg_divku").css('height', $(window).height()*51/100);
    
    var rcvcustoms_fg_selcol='0';

    $("#rcvcustoms_fg_monthfilter").change(function (e) { 
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_ck").click(function (e) { 
        document.getElementById('rcvcustoms_fg_monthfilter').disabled=!document.getElementById('rcvcustoms_fg_ck').checked;
        document.getElementById('rcvcustoms_fg_year').disabled=!document.getElementById('rcvcustoms_fg_ck').checked;
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    document.getElementById('rcvcustoms_fg_year').value=new Date().getFullYear();
    $("#rcvcustoms_fg_rad_do").click(function (e) {         
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_rad_item").click(function (e) {             
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_PROGRESS").on('shown.bs.modal', function(){
        rcvcustoms_fg_e_save();
    });
   
    $("#rcvcustoms_fg_btn_download").click(function (e) { 
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getlinkitemtemplate')?>",
            dataType: "text",                       
            success:function(response) {          
                window.open(response, '_blank');
                alertify.message("<i>Start downloading...</i>");
            },
            error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });        
    });
  
    var rcvcustoms_fg_ttlxls = 0;
    var rcvcustoms_fg_ttlxls_savd = 0;
  
    function rcvcustoms_fg_e_save(){
        var mbctype = $("#rcvcustoms_fg_typedoc").val();        
        var mdate   = $("#rcvcustoms_fg_dd").val();
        var mrcvdate   = $("#rcvcustoms_fg_rcvdate").val();
        var mtpb    = $("#rcvcustoms_fg_typetpb").val();
        var mdo     = $("#rcvcustoms_fg_docnoorigin").val();
        var mnoaju  = $("#rcvcustoms_fg_noaju").val();
        var mregno  = $("#rcvcustoms_fg_regno").val();
        var m_nw    = numeral($("#rcvcustoms_fg_NW").val()).value();
        var m_gw    = numeral($("#rcvcustoms_fg_GW").val()).value();
        var m_amt   = numeral($("#rcvcustoms_fg_amount").val()).value();
        let mkppbc   = $("#rcvcustoms_fg_kppbc").val();
        let zstsrcv = document.getElementById('rcvcustoms_fg_zsts').value;
        var ar_nourut = [];
        var ar_pono = [];
        var ar_dodt = [];
        var ar_supcd = [];
        var ar_curr = [];
        var ar_item = [];
        var ar_qty = [];
        var ar_price = [];
        var ar_amt = [];
        let ar_wh = [];
        let ar_grlno = [];
        let ar_hscode = [];
        let ar_bm = [];
        let ar_ppn = [];
        let ar_pph = [];
        
        var tables      = $("#rcvcustoms_fg_tbl tbody");
        tables.find('tr').each(function (i) {
            let $tds = $(this).find('td'),
                rnourut =  $tds.eq(1).text(),
                rpo =  $tds.eq(3).text(),                
                rsup =  $tds.eq(5).text(),
                rcurr =  $tds.eq(7).text(),
                ritem =  $tds.eq(8).text(),
                rqty =  $tds.eq(10).text(),
                rprc =  $tds.eq(12).text(),
                ramt =  $tds.eq(13).text(),
                rwh =  $tds.eq(14).text();
                rgrlno =  $tds.eq(15).text();
                rhscode =  $tds.eq(16).text();
                rbm =  $tds.eq(17).text();
                rppn =  $tds.eq(18).text();
                rpph =  $tds.eq(19).text();
            if(ritem!=''){                
                ar_nourut.push(rnourut);
                ar_pono.push(rpo.trim());
                
                ar_supcd.push(rsup);
                ar_curr.push(rcurr);
                ar_item.push(ritem);
                ar_qty.push(numeral(rqty).value());
                ar_price.push(rprc);
                ar_amt.push(ramt);
                ar_wh.push(rwh);
                ar_grlno.push(rgrlno);
                ar_hscode.push(rhscode);
                ar_bm.push(rbm);
                ar_ppn.push(rppn);
                ar_pph.push(rpph);
            }
        });
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/updateBCDoc')?>",
            data: {inbctype: mbctype, inbcno: mnoaju, inbcdate: mdate,inrcvdate: mrcvdate, inwh: ar_wh,
                inpo:ar_pono ,indo: mdo, intpb: mtpb, inregno: mregno, inNW: m_nw, inGW: m_gw,
                insupcd: ar_supcd, incurr: ar_curr, initm: ar_item, inqty: ar_qty,
                inprice: ar_price, inamt: ar_amt, inttl_amt: m_amt, inkppbc: mkppbc, ingrlno: ar_grlno, inhscode: ar_hscode,
                instsrcv :zstsrcv, inbm : ar_bm, inppn: ar_ppn, inpph: ar_pph, innomorurut: ar_nourut },
            dataType: "json",
            success: function (response) {
                alertify.message(response[0].msg);
                let mitem = document.getElementById('rcvcustoms_fg_docnoorigin').value;
                MGGetDODetail_FGRET(mitem.trim());      
                WMSGetDODetail_FGRET(mitem.trim());
                if(response[0].cd=='0'){
                    location.reload();
                }
                $("#rcvcustoms_fg_PROGRESS").modal('hide');
            }, error : function(xhr,xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#rcvcustoms_fg_save").click(function(){
        let dono = document.getElementById('rcvcustoms_fg_docnoorigin').value;
        let mnoaju = document.getElementById('rcvcustoms_fg_noaju').value;
        let mnopen = document.getElementById('rcvcustoms_fg_regno').value;

        if(dono.trim()==''){
            alertify.warning('Please select DO first');
            document.getElementById('rcvcustoms_fg_btnmod').focus();
            return;
        }
        if(mnoaju.trim().length!=26){
            alertify.warning('NoAju must be 26 digit');
            document.getElementById('rcvcustoms_fg_noaju').focus()
            return;
        }
        if(mnopen.trim().length!=6){
            alertify.warning('NoPen must be 6 digit');
            document.getElementById('rcvcustoms_fg_regno').focus()
            return;
        }
        let mymodal = new bootstrap.Modal(document.getElementById("rcvcustoms_fg_PROGRESS"), {backdrop: 'static', keyboard: false});
        mymodal.show();        
    });
    
    
    $('#rcvcustoms_fg_supfilter').change(function(){ //on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_datefilter").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    }); 

    function rcvcustoms_fg_btn_filterdate_e_click(){
        document.getElementById('rcvcustoms_fg_datefilter').value="";
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    }

    $("#rcvcustoms_fg_dd").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });      
    $("#rcvcustoms_fg_rcvdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });      
    function getdocBC(pdo){
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getBCField')?>",
            data: {indo : pdo},
            dataType: "json",
            success: function (response) {
                if(response.length>0){
                    $("#rcvcustoms_fg_typedoc").val(response[0].RCV_BCTYPE);
                    $("#rcvcustoms_fg_docnoorigin").val(response[0].RCV_BCNO);
                    $("#rcvcustoms_fg_typetpb").val(response[0].RCV_TPB);
                }                
            },
            error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    
    
    $('#rcvcustoms_fg_tbl tbody').on( 'click', 'td', function () {            
        rcvcustoms_fg_selcol = $(this).index();         
    });
    $("#rcvcustoms_fg_btnmod").click(function (e) { 
        e.preventDefault();
        $("#rcvcustoms_fg_DTLMOD").modal('show');
    });
    $("#rcvcustoms_fg_DTLMOD").on('shown.bs.modal', function(){
        $("#rcvcustoms_fg_txt_search").focus();
    });
    $("#rcvcustoms_fg_txt_search").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            let mby = '';
            if(document.getElementById('rcvcustoms_fg_rad_do').checked){
                mby = document.getElementById('rcvcustoms_fg_rad_do').value;
            } else {
                mby = document.getElementById('rcvcustoms_fg_rad_item').value;
            }
            let mpermonth = document.getElementById('rcvcustoms_fg_ck').checked ? 'y' : 'n';
            let myear = document.getElementById('rcvcustoms_fg_year').value;
            let mmonth = document.getElementById('rcvcustoms_fg_monthfilter').value;
            let msuplier = document.getElementById('rcvcustoms_fg_supfilter').value;
            let mdatefilter = document.getElementById('rcvcustoms_fg_datefilter').value;
            
            $("#lblinfo_rcvcustoms_fg_tbldono").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/MGGetDOReturn')?>",
                data: {inid: mval, inby: mby, inpermonth: mpermonth, inyear: myear, inmonth: mmonth, insup: msuplier
                ,indatefilter: mdatefilter},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';                    
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].STKTRND1_DOCNO+"</td>"+
                        "<td>"+response[i].ISUDT+"</td>"+
                        "<td>"+response[i].MBSG_DESC+"</td>"+
                        "<td class='text-end'>"+numeral(response[i].TTLITEMIN/response[i].TTLITEM*100).format(',')+"% synchronized</td>"+
                        "<td>"+response[i].RCV_HSCD+"</td>"+
                        "<td>"+response[i].RCV_BM+"</td>"+
                        "<td>"+response[i].RCV_PPN+"</td>"+
                        "<td>"+response[i].RCV_PPH+"</td>"+
                        "</tr>";
                    }
                    $("#rcvcustoms_fg_tbldono tbody").html(tohtml);
                    $("#lblinfo_rcvcustoms_fg_tbldono").text("");
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#rcvcustoms_fg_tbldono tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#rcvcustoms_fg_tbldono tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        var mitem       = $(this).closest("tr").find('td:eq(0)').text();
        $("#rcvcustoms_fg_docnoorigin").val(mitem.trim());
        MGGetDODetail_FGRET(mitem.trim());        
        $("#rcvcustoms_fg_DTLMOD").modal('hide');
        WMSGetDODetail_FGRET(mitem.trim());
    });

    function vrcv_e_getstsrcv(mid, pval){        
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {inid: mid, instst : pval},
            dataType: "json",
            success: function (response) {
                let str = '<option value="-">-</option>';
                if(response.status[0].cd != '0'){
                    let ttlrows = response.data.length;
                    for(let i=0;i<ttlrows;i++){
                        str  += '<option value="'+response.data[i].KODE_TUJUAN_PENGIRIMAN+'">'+response.data[i].URAIAN_TUJUAN_PENGIRIMAN+'</option>';
                    }
                }
                document.getElementById('rcvcustoms_fg_zsts').innerHTML = str;
                document.getElementById('rcvcustoms_fg_zsts').value = response.status[0].reff;
                document.getElementById('rcvcustoms_fg_zsts').focus();

            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function WMSGetDODetail_FGRET(pdo){
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/WMSGetDODetail')?>",
            data: {indo: pdo},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                if (ttlrows>0){
                    let m_nw = ''; let m_gw = ''; let m_ttlamt = '';  
                    let mstsrcv ='-';
                    for(let i=0;i<1;i++){
                        if(response[i].RCV_ZSTSRCV) {
                            mstsrcv = response[i].RCV_ZSTSRCV;
                            vrcv_e_getstsrcv(response[i].RCV_BCTYPE,mstsrcv );
                        } else {
                            mstsrcv ='-';
                        }
                    }
                    for(let i=0;i<ttlrows;i++){
                        if(response[i].RCV_NW){
                            m_nw = response[i].RCV_NW.substring(0,1)=='.' ? '0'+response[i].RCV_NW : response[i].RCV_NW;
                        } else {
                            m_nw = 0;
                        }     
                        if(response[i].RCV_GW){
                            m_gw = response[i].RCV_GW.substring(0,1)=='.' ? '0'+response[i].RCV_GW : response[i].RCV_GW;
                        } else {
                            m_gw = 0;
                        }
                        if(response[i].RCV_TTLAMT){
                            m_ttlamt = response[i].RCV_TTLAMT.substring(0,1)=='.' ? '0'+response[i].RCV_TTLAMT : response[i].RCV_TTLAMT;
                            console.log('sini : ' + m_ttlamt + ' {'+response[i].RCV_TTLAMT+'}');
                        } else {
                            m_ttlamt = 0;
                        }
                        
                        $("#rcvcustoms_fg_typedoc").val(response[i].RCV_BCTYPE);
                        $("#rcvcustoms_fg_noaju").val(response[i].RCV_RPNO);
                        $("#rcvcustoms_fg_regno").val(response[i].RCV_BCNO);
                        $("#rcvcustoms_fg_dd").datepicker('update', response[i].RCV_RPDATE);
                        $("#rcvcustoms_fg_rcvdate").datepicker('update',response[i].RCV_RCVDATE);
                        $("#rcvcustoms_fg_typetpb").val(response[i].RCV_TPB);
                        $("#rcvcustoms_fg_kppbc").val(response[i].RCV_KPPBC);
                        $("#rcvcustoms_fg_NW").val(m_nw);
                        $("#rcvcustoms_fg_GW").val(m_gw);
                        $("#rcvcustoms_fg_amount").val(m_ttlamt);
                    }
                } else {
                    $("#rcvcustoms_fg_typedoc").val('');
                    $("#rcvcustoms_fg_noaju").val('');
                    $("#rcvcustoms_fg_regno").val('');
                    $("#rcvcustoms_fg_typetpb").val('');
                    $("#rcvcustoms_fg_kppbc").val('');
                    $("#rcvcustoms_fg_NW").val('');
                    $("#rcvcustoms_fg_GW").val('');
                    $("#rcvcustoms_fg_amount").val('');
                }                
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function MGGetDODetail_FGRET(pdo){
        $("#rcvcustoms_fg_lbltbl").text("Please wait . . .");
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/MGGetDODetailReturn')?>",
            data: {indo: pdo},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                let tohtml = '';
                let strprice = '';
                let stramt = '';
                $("#rcvcustoms_fg_lbltbl").text(ttlrows+" row(s) found");
                for(let i=0;i<ttlrows;i++){
                    strprice = response[i].STKTRND2_PRICE;
                    if(strprice.substring(0,1)=='.'){
                        strpirce = '0'+response[i].STKTRND2_PRICE;
                    } else {
                        strpirce = response[i].STKTRND2_PRICE;
                    }
                    stramt = response[i].AMT;
                    if(stramt.substring(0,1)=='.'){
                        stramt = '0'+response[i].AMT;
                    } else {
                        stramt = response[i].AMT;
                    }
                    tohtml += "<tr style='cursor:pointer'>"+
                    "<td>"+response[i].SYNC_STS+"</td>"+
                    "<td contenteditable='true'>"+response[i].NOURUT+"</td>"+
                    "<td>"+response[i].PROFNO+"</td>"+
                    "<td></td>"+
                    "<td>"+response[i].ISUDT+"</td>"+
                    "<td class='d-none'>"+response[i].MCUS_CUSCD+"</td>"+
                    "<td>"+response[i].MCUS_CUSNM+"</td>"+
                    "<td>"+response[i].MCUS_CURCD+"</td>"+
                    "<td>"+response[i].STKTRND2_ITMCD+"</td>"+
                    "<td>"+response[i].MITM_ITMD1+"</td>"+
                    "<td class='text-end'>"+numeral(response[i].RETQT).format(',')+"</td>"+
                    "<td>"+response[i].MITM_STKUOM+"</td>"+
                    "<td class='text-end'>"+strpirce+"</td>"+
                    "<td class='text-end'>"+stramt+"</td>"+
                    "<td class='d-none'>"+response[i].STKTRND1_LOCCDFR+"</td>"+
                    "<td class='d-none'></td>"+
                    "<td contenteditable='true'>"+response[i].HSCD+"</td>"+
                    "<td contenteditable='true'>"+response[i].BEAMASUK+"</td>"+
                    "<td contenteditable='true'>"+response[i].PPN+"</td>"+
                    "<td contenteditable='true'>"+response[i].PPH+"</td>"+
                    "</tr>";
                }
                $("#rcvcustoms_fg_tbl tbody").html(tohtml);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);$("#rcvcustoms_fg_lbltbl").text("");
            }
        });
    }
    $("#rcvcustoms_fg_typedoc").change(function(){
        let mid = document.getElementById('rcvcustoms_fg_typedoc').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {inid: mid},
            dataType: "json",
            success: function (response) {
                let str = '<option value="-">-</option>';
                if(response.status[0].cd != '0'){
                    let ttlrows = response.data.length;
                    for(let i=0;i<ttlrows;i++){
                        str  += '<option value="'+response.data[i].KODE_TUJUAN_PENGIRIMAN+'">'+response.data[i].URAIAN_TUJUAN_PENGIRIMAN+'</option>';
                    }
                }
                document.getElementById('rcvcustoms_fg_zsts').innerHTML = str;
                document.getElementById('rcvcustoms_fg_zsts').focus();
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });        
    });

    $('#rcvcustoms_fg_dd').datepicker()
    .on('changeDate', function(e) {
        $("#rcvcustoms_fg_typetpb").focus();
    });
    $('#rcvcustoms_fg_datefilter').datepicker()
    .on('changeDate', function(e) {
        $("#rcvcustoms_fg_txt_search").focus();
    });
    $("#rcvcustoms_fg_typetpb").change(function(){
        $("#rcvcustoms_fg_NW").focus();
    });
    
</script>