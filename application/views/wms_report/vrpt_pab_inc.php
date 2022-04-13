<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="rpab_in_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Doc Type</label>                    
                    <select id="rpab_in_typedoc" class="form-select">
						<option value="-">All</option>
                        <option value="23">BC 2.3</option>
                        <option value="27">BC 2.7</option>
                        <option value="40">BC 4.0</option>
                        <option value="262">BC 2.6.2</option>
                    </select>                    
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Receiving Status</label>                    
                    <select id="rpab_in_zsts" class="form-select">
                        <option value="-">All</option>
                    </select>                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">TPB Type</label>                    
                    <select id="rpab_in_typetpb" class="form-select">
						<option value="-">All</option>
                        <?php foreach($ltpb_type as $r) {?>
                            <option value="<?=trim($r['KODE_JENIS_TPB']).'#'.$r['SINGKATAN']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="rpab_in_stack2">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="rpab_in_txt_itemcd" required readonly>                    
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="rpab_in_btnfinditem"><i class="fas fa-search"></i></button>                        
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="rpab_in_btnallitem">All</button>                                                                    
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Type</label>
                    <select class="form-select" id="rpab_in_cmb_itemtype">
                        <option value='-'>All</option>
                        <?=$modell?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Supplier</span>                    
                    <select class="form-select" id="rpab_in_sel_sup" data-style="btn-primary">
                        <option value="-">All</option>
                        <?php                         
                        foreach($lsup as $r){
                            if(trim($r->MSUP_SUPNM)!=''){
                            ?>
                            <option value="<?=trim($r->MSUP_SUPCD)?>"><?='['.trim($r->MSUP_SUPCR).'] '.$r->MSUP_SUPNM?></option>
                            <?php
                            }
                        }          
                        ?>
                    </select>        
                </div>
            </div>
        </div>        
        <div class="row" id="rpab_in_stack3">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <div class="input-group-text">
                    <i class="fas fa-calendar"></i>
                    </div>
                    <span class="input-group-text"> From</span>                    
                    <input type="text" class="form-control" id="rpab_in_date0" required readonly>                             
                    <span class="input-group-text"> To</span>                                            
                    <input type="text" class="form-control" id="rpab_in_date1" required readonly>                   
                </div>
            </div>          
        </div>    
        <div class="row" id="rpab_in_stack4">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Nomor Aju</span>
                    <input type="text" class="form-control" id="rpab_in_noaju" required maxlength="26">                      
                </div>
            </div>
        </div>    
        <div class="row" id="rpab_in_stack5">
            <div class="col-md-6 mb-1"> 
                <div class="btn-group btn-group-sm">
                    <button title="Generate" class="btn btn-primary" id="rpab_in_btn_gen">Generate</button>                    
                    <button title="Export to Spreadsheet" class="btn btn-outline-success" id="rpab_in_btn_xls"><i class="fas fa-file-excel"></i></button>
                </div>                
            </div>
            <div class="col-md-6 mb-1 text-end">
                <span id="rpab_in_status" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">
                <div class="table-responsive" id="rpab_in_divku">
                    <table id="rpab_in_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:80%">
                        <thead class="table-light">
                            <tr>                                
                                <th rowspan="2" class="align-middle">No</th>
                                <th colspan="3" class="text-center">PENGAJUAN <span id="rpab_in_bctype"></span> </th>
                                <th class="align-middle text-center">PENDAFTARAN</th>
                                <th>NO. URUT</th>
                                <th rowspan="2" class="align-middle">URAIAN JENIS BARANG</th>
                                <th rowspan="2" class="align-middle">KODE BARANG</th>
                                <th rowspan="2" class="align-middle">HS CODE</th>
                                <th rowspan="2" class="align-middle">JUMLAH</th>
                                <th rowspan="2" class="align-middle">SATUAN</th>
                                <th rowspan="2" class="align-middle">NILAI PABEAN</th>
                                <th rowspan="2" class="align-middle">BERAT(KG)</th>
                                <th colspan="2" class="align-middle text-center"><span id="rpab_in_tpbtype">KB</span> ASAL PERUSAHAAN </th>
                                <th rowspan="2" class="align-middle">Surat Jalan</th>
                                <th rowspan="2" class="align-middle">Keterangan</th>
                            </tr>
                            <tr>                                
                                <th>NOMOR</th>
                                <th>TANGGAL DOKUMEN</th>
                                <th>TANGGAL PENERIMAAN</th>
                                <th class="text-center">NOMOR</th>
                                <th>BARANG</th>
                                <th>NAMA</th>                                                           
                                <th>ALAMAT</th>                                                           
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
<div class="modal fade" id="RPAB_IN_ITEMLIST">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col md-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="rpab_in_txtsearch" maxlength="45" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col md-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="rpab_in_srchby" class="form-select">
                            <option value="ic">Item Code</option>
                            <option value="in">Item Name</option>        
                            <option value="spt">SPT No</option>
                        </select>                  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end md-1">
                    <span class="badge bg-info" id="rpab_in_tblitmloc"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="rpab_in_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:80%">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Descr. 1</th>
                                    <th>Descr. 2</th>
                                    <th>SPT No</th>
                                    <th>Type</th>
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
    $("#rpab_in_divku").css('height', $(window).height()   
    -document.getElementById('rpab_in_stack1').offsetHeight 
    -document.getElementById('rpab_in_stack2').offsetHeight
    -document.getElementById('rpab_in_stack3').offsetHeight
    -document.getElementById('rpab_in_stack4').offsetHeight
    -document.getElementById('rpab_in_stack5').offsetHeight    
    -100);    
    $("#rpab_in_date0").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rpab_in_date1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rpab_in_date0").datepicker('update', new Date());
    $("#rpab_in_date1").datepicker('update', new Date());
    $("#rpab_in_ckdate").click(function (e) { 
        if($(this).prop('checked')){
            $("#rpab_in_date0").val('');
            $("#rpab_in_date1").val('');
        }        
    });  

    $("#rpab_in_btn_gen").click(function (e) { 
        let mdoctype = document.getElementById('rpab_in_typedoc').value;
        let mtpbtype = document.getElementById('rpab_in_typetpb').value;
        let mitmcd = document.getElementById('rpab_in_txt_itemcd').value;
        let msup = document.getElementById('rpab_in_sel_sup').value;
        let mdate0 = document.getElementById('rpab_in_date0').value;
        let mdate1 = document.getElementById('rpab_in_date1').value;
        let mnoaju = document.getElementById('rpab_in_noaju').value;
        let mzstatus = document.getElementById('rpab_in_zsts').value;
        let itemtype = document.getElementById('rpab_in_cmb_itemtype').value;
        let atpb = mtpbtype.split("#");
        mtpbtype = atpb[0];
        document.getElementById('rpab_in_status').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/dr_pab_inc')?>",
            data: {indoctype : mdoctype, intpbtype: mtpbtype, initmcd: mitmcd.trim(), insup: msup.trim(), indate0: mdate0, indate1: mdate1,
            innoaju: mnoaju, instatus: mzstatus,itemtype:itemtype},
            dataType: "json",
            success: function (response) {
                
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rpab_in_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rpab_in_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("rpab_in_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let mnomorin = 0;
                let mnomordis, mnomorpab, mnomorpabdis, mdatepabdis,mdaterecv, mnilaipab, mnilaipabdis, mberatpab, mberatpabdis, msup, msupdis, malam, malamdis, mdo,mdodis, mnomorpendaftaran, mnomorpendaftarandis;
                for (let i = 0; i<ttlrows; i++){
                    if(mnomorpab != response.data[i].RCV_RPNO){
                        mnomorpab = response.data[i].RCV_RPNO;
                        mnomorpendaftaran = response.data[i].RCV_BCNO;                        
                        mnomor++;
                        mnomordis= mnomor;
                        mnomorpabdis = mnomorpab;
                        mnomorpendaftarandis = mnomorpendaftaran;
                        mdatepabdis = response.data[i].RCV_RPDATE;
                        mdaterecv = response.data[i].RCV_RCVDATE;
                        mnomorin=1;      
                        mnilaipab = response.data[i].RCV_TTLAMT;
                        mnilaipabdis = numeral(mnilaipab).format('0,0.00');
                        mberatpab = response.data[i].RCV_NW;
                        mberatpabdis = numeral(mberatpab).format('0,0.00');
                        msup = response.data[i].MSUP_SUPNM;
                        msupdis = msup ? msup.trim(): '';
                        malam = response.data[i].MSUP_ADDR1;
                        malamdis = malam ? malam.trim(): malam  ;
                        mdo = response.data[i].RCV_DONO;
                        mdodis = mdo.trim();
                    } else {
                        mnomorin++;
                        mnomordis = '';
                        mnomorpabdis = '';
                        mnomorpendaftarandis = '';
                        mdatepabdis = '';   
                        mdaterecv ='';                                                                     
                        if(mdo!=response.data[i].RCV_DONO){
                            mdo = response.data[i].RCV_DONO;
                            mnilaipab = response.data[i].RCV_TTLAMT;
                            mberatpab = response.data[i].RCV_NW;
                            msup = response.data[i].MSUP_SUPNM;
                            malam = response.data[i].MSUP_ADDR1;
                            mnilaipabdis = numeral(mnilaipab).format('0,0.00');
                            mberatpabdis = numeral(mberatpab).format('0,0.00');
                            msupdis=msup;
                            malamdis = malam ? malam.trim() : '';
                            mdodis = mdo.trim();
                        } else {
                            mnilaipabdis = '';
                            mberatpabdis = '';
                            msupdis='';
                            malamdis = '';
                            mdodis = '';
                        }
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(mnomordis);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(mnomorpabdis);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(mdatepabdis);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(mdaterecv);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(mnomorpendaftarandis);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(mnomorin);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1.trim());
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].RCV_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].RCV_HSCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(numeral(response.data[i].RCV_QTY).format('0,0'));
                    newcell.style.cssText = "text-align: right";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode(response.data[i].MITM_STKUOM);
                    newcell.style.cssText = "text-align: center";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(11);
                    newText = document.createTextNode(mnilaipabdis);
                    newcell.style.cssText = "text-align: right";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(12);
                    newText = document.createTextNode(mberatpabdis);
                    newcell.style.cssText = "text-align: right";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(13);
                    newText = document.createTextNode(msupdis);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(14);
                    newText = document.createTextNode(malamdis);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(15);
                    newText = document.createTextNode(mdodis);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(16);
                    newText = document.createTextNode(response.data[i].URAIAN_TUJUAN_PENGIRIMAN);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('rpab_in_status').innerHTML = ttlrows>0 ? '': 'data not found';

            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#rpab_in_btnfinditem").click(function (e) { 
        $("#RPAB_IN_ITEMLIST").modal('show');
    });
    $("#RPAB_IN_ITEMLIST").on('shown.bs.modal', function(){
        $("#rpab_in_txtsearch").focus();
    });

    $("#rpab_in_txtsearch").keypress(function (e) { 
        if(e.which==13){
            let mkey = $(this).val();
            let msearchby = $("#rpab_in_srchby").val();
            $("#rpab_in_tblitmloc").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml ='';
                    let mtype = '';
                    for(let i=0;i<ttlrows;i++){
                        if(response[i].MITM_MODEL.trim()=='1'){
                            mtype = 'FG';
                        } else {
                            mtype = 'RM';
                        }
                        tohtml += '<tr style="cursor:pointer">'+
                        '<td style="white-space:nowrap">'+response[i].MITM_ITMCD.trim()+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MITM_ITMD1+'</td>'+
                        '<td>'+response[i].MITM_ITMD2+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MITM_SPTNO+'</td>'+
                        '<td>'+mtype+'</td>'+
                        '</tr>';
                    }
                    $("#rpab_in_tblitmloc").text("");
                    $('#rpab_in_tblitm tbody').html(tohtml);
                }
            });
        }
    });
    $('#rpab_in_tblitm tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#rpab_in_tblitm tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem = $(this).closest("tr").find('td:eq(0)').text(); 
        document.getElementById("rpab_in_txt_itemcd").value=mitem;
        $("#RPAB_IN_ITEMLIST").modal('hide');
    });
    $("#rpab_in_btnallitem").click(function (e) { 
        document.getElementById('rpab_in_txt_itemcd').value='';
    });
    $("#rpab_in_typedoc").change(function (e) { 
        document.getElementById('rpab_in_bctype').innerText=$("#rpab_in_typedoc option:selected").html();
        let mid = document.getElementById('rpab_in_typedoc').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {inid: mid},
            dataType: "json",
            success: function (response) {
                let str = '<option value="-">All</option>';
                if(response.status[0].cd != '0'){
                    let ttlrows = response.data.length;
                    for(let i=0;i<ttlrows;i++){
                        str  += '<option value="'+response.data[i].KODE_TUJUAN_PENGIRIMAN+'">'+response.data[i].URAIAN_TUJUAN_PENGIRIMAN+'</option>';
                    }
                }
                document.getElementById('rpab_in_zsts').innerHTML = str;
                document.getElementById('rpab_in_zsts').focus();
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#rpab_in_typetpb").change(function(e){
        let vals = $(this).val();
        let avals = vals.split("#");
        document.getElementById("rpab_in_tpbtype").innerText = avals[1];
    });
    $("#rpab_in_btn_xls").click(function (e) {      
        let mdoctype = document.getElementById('rpab_in_typedoc').value;
        let mtpbtype = document.getElementById('rpab_in_typetpb').value;
        let mitmcd = document.getElementById('rpab_in_txt_itemcd').value;
        let msup = document.getElementById('rpab_in_sel_sup').value;
        let mdate0 = document.getElementById('rpab_in_date0').value;
        let mdate1 = document.getElementById('rpab_in_date1').value;
        let mnoaju = document.getElementById('rpab_in_noaju').value;
        let mstatus = document.getElementById('rpab_in_zsts').value;
        let itemtype = document.getElementById('rpab_in_cmb_itemtype').value;
        let atpb = mtpbtype.split("#");
        mtpbtype = atpb[0];   
        let mtpbtypes = atpb[1];
        Cookies.set('RP_PAB_DOCTYPE', mdoctype, {expires:365});
        Cookies.set('RP_PAB_TPBTYPE', mtpbtype, {expires:365});
        Cookies.set('RP_PAB_TPBTYPES', mtpbtypes, {expires:365});
        Cookies.set('RP_PAB_ITMCD', mitmcd, {expires:365});
        Cookies.set('RP_PAB_SUP', msup, {expires:365});
        Cookies.set('RP_PAB_DATE0', mdate0, {expires:365});
        Cookies.set('RP_PAB_DATE1', mdate1, {expires:365});
        Cookies.set('RP_PAB_NOAJU', mnoaju, {expires:365});
        Cookies.set('RP_PAB_RCVSTATUS', mstatus, {expires:365});
        Cookies.set('RP_PAB_ITMTYPE', itemtype, {expires:365});
        window.open("<?=base_url('laporan_pembukuan_masuk_xlsx')?>",'_blank');
    });
    
</script>