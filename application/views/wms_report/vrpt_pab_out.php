<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="rpab_out_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Doc Type</label>
                    <select id="rpab_out_typedoc" class="form-select">
						<option value="-">All</option>
                        <option value="25">BC 2.5</option>
                        <option value="27">BC 2.7</option>
                        <option value="41">BC 4.1</option>
                        <option value="261">BC 2.6.1</option>
                        <option value="30">BC 3.0</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Purpose of Delivery</label>
                    <select id="rpab_out_zsts" class="form-select">
                        <option value="-">All</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="rpab_out_stack2">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="rpab_out_txt_itemcd" required readonly>
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="rpab_out_btnfinditem"><i class="fas fa-search"></i></button>
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="rpab_out_btnallitem">All</button>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Item Type</label>
                    <select class="form-select" id="rpab_out_cmb_itemtype">
                        <option value='-'>All</option>
                        <?=$modell?>
                        <option value='10'>Finished Goods RTN</option>
                        <option value='11'>Finished Goods Subcont</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">TPB Type</label>
                    <select id="rpab_out_typetpb" class="form-select">
						<option value="-">All</option>
                        <?php foreach ($ltpb_type as $r) {?>
                            <option value="<?=trim($r['KODE_JENIS_TPB']) . '#' . $r['SINGKATAN']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="rpab_out_stack3">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-text">
                    <i class="fas fa-calendar"></i>
                    </div>
                    <span class="input-group-text"> From</span>
                    <input type="text" class="form-control" id="rpab_out_date0" required readonly>
                    <span class="input-group-text"> To</span>
                    <input type="text" class="form-control" id="rpab_out_date1" required readonly>
                </div>
            </div>
        </div>
        <div class="row" id="rpab_out_stack4">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text"> Nomor Aju</span>
                    <input type="text" class="form-control" id="rpab_out_noaju" required maxlength="26">
                </div>
            </div>
        </div>
        <div class="row" id="rpab_out_stack5">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="Generate" class="btn btn-primary" id="rpab_out_btn_gen">Generate</button>
                    <button title="Export to Spreadsheet" class="btn btn-outline-success" id="rpab_out_btn_xls"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>

            <div class="col-md-6 mb-1 text-end">
                <span id="rpab_out_status" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">
                <div class="table-responsive" id="rpab_out_divku">
                    <table id="rpab_out_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>
                                <th colspan="2" class="align-middle text-center">PENGAJUAN <span id="rpab_out_bctype"></span> </th>
                                <th colspan="2" class="align-middle text-center">PENDAFTARAN</th>
                                <th>NO. URUT</th>
                                <th rowspan="2" class="align-middle">URAIAN JENIS BARANG</th>
                                <th rowspan="2" class="align-middle">KODE BARANG</th>
                                <th rowspan="2" class="align-middle">HSCODE</th>
                                <th rowspan="2" class="align-middle">JUMLAH</th>
                                <th rowspan="2" class="align-middle">SATUAN</th>
                                <th rowspan="2" class="align-middle">HARGA</th>
                                <th rowspan="2" class="align-middle">Valuta</th>
                                <th rowspan="2" class="align-middle">NILAI PABEAN</th>
                                <th colspan="2" class="align-middle text-center"><span id="rpab_out_tpbtype">TPB TUJUAN</th>
                                <th rowspan="2" class="align-middle">NO. INVOICE</th>
                                <th rowspan="2" class="align-middle">NO. INVOICE SMT</th>
                                <th rowspan="2" class="align-middle">NO. DO</th>
                                <th rowspan="2" class="align-middle">Ketarangan</th>
                                <th rowspan="2" class="align-middle">SPPB Document</th>
                            </tr>
                            <tr>
                                <th>NOMOR</th>
                                <th>TANGGAL</th>
                                <th class="text-center">NOMOR</th>
                                <th class="text-center">TANGGAL</th>
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
<div class="modal fade" id="rpab_out_ITEMLIST">
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
                        <input type="text" class="form-control" id="rpab_out_txtsearch" maxlength="45" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col md-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select id="rpab_out_srchby" class="form-select">
                            <option value="ic">Item Code</option>
                            <option value="in">Item Name</option>
                            <option value="spt">SPT No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end md-1">
                    <span class="badge bg-info" id="rpab_out_tblitmloc"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="rpab_out_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:80%">
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
    $("#rpab_out_divku").css('height', $(window).height()
    -document.getElementById('rpab_out_stack1').offsetHeight
    -document.getElementById('rpab_out_stack2').offsetHeight
    -document.getElementById('rpab_out_stack3').offsetHeight
    -document.getElementById('rpab_out_stack4').offsetHeight
    -document.getElementById('rpab_out_stack5').offsetHeight
    -100);
    $("#rpab_out_date0").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rpab_out_date1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rpab_out_date0").datepicker('update', new Date());
    $("#rpab_out_date1").datepicker('update', new Date());
    $("#rpab_out_ckdate").click(function (e) {
        if($(this).prop('checked')){
            $("#rpab_out_date0").val('');
            $("#rpab_out_date1").val('');
        }
    });

    $("#rpab_out_btn_gen").click(function (e) {
        let mdoctype = document.getElementById('rpab_out_typedoc').value;
        let mtpbtype = document.getElementById('rpab_out_typetpb').value;
        let mitmcd = document.getElementById('rpab_out_txt_itemcd').value;
        let mdate0 = document.getElementById('rpab_out_date0').value;
        let mdate1 = document.getElementById('rpab_out_date1').value;
        let mnoaju = document.getElementById('rpab_out_noaju').value;
        let mzstatus = document.getElementById('rpab_out_zsts').value
        let itemtype = document.getElementById('rpab_out_cmb_itemtype').value
        let atpb = mtpbtype.split("#");
        mtpbtype = atpb[0];
        document.getElementById('rpab_out_status').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/pab_out')?>",
            data: {indoctype : mdoctype, intpbtype: mtpbtype, initmcd: mitmcd.trim(),  indate0: mdate0, indate1: mdate1,
            innoaju: mnoaju, instatus: mzstatus, itemtype: itemtype},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rpab_out_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rpab_out_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rpab_out_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let mnomorin = 0;
                let mnomordis, mnomorpab, mnomorpabdis, mdatepabdis,mtanggalDaftar, mtanggalDaftardis, mnilaipab, mnilaipabdis, mberatpab, mberatpabdis, msup, msupdis, malam, malamdis, mdo,mdodis, mnomorpendaftaran, mnomorpendaftarandis
                let nomorInv, nomorInvDis, nomorInvSMT, nomorInvSMTDis
                let msppdis = ''
                for (let i = 0; i<ttlrows; i++){
                    mnilaipab = response.data[i].AMOUNT;
                    mnilaipabdis = numeral(mnilaipab).format('0,0.00')
                    if(mnomorpab != response.data[i].NOMAJU){
                        mnomorpab = response.data[i].NOMAJU;
                        mnomorpendaftaran = response.data[i].NOMPEN
                        mnomor++;
                        mnomordis= mnomor;
                        mnomorpabdis = mnomorpab;
                        mnomorpendaftarandis = mnomorpendaftaran;
                        mdatepabdis = response.data[i].DLV_BCDATE;
                        mtanggalDaftar = response.data[i].TGLPEN
                        mtanggalDaftardis = mtanggalDaftar
                        mnomorin=1;

                        msup = response.data[i].MDEL_ZNAMA;
                        msupdis = msup ? msup.trim(): '';
                        malam = response.data[i].MDEL_ADDRCUSTOMS
                        malamdis = malam ? malam.trim(): malam  ;
                        mdo = response.data[i].DLV_ID
                        mdodis = mdo.trim();

                        nomorInv = response.data[i].DLV_SMTINVNO
                        nomorInvDis = nomorInv
                        nomorInvSMT = response.data[i].DLV_INVNO
                        nomorInvSMTDis = nomorInvSMT
                        msppdis = response.data[i].DLV_SPPBDOC
                    } else {
                        mnomorin++;
                        mnomordis = '';
                        mnomorpabdis = '';
                        mnomorpendaftarandis = '';
                        mtanggalDaftardis = '';
                        mdatepabdis = '';
                        mdaterecv =''
                        nomorInvDis =''
                        nomorInvSMTDis =''
                        msppdis =''
                        if(mdo!=response.data[i].DLV_ID){
                            mdo = response.data[i].DLV_ID;
                            mnilaipab = response.data[i].AMOUNT
                            msup = response.data[i].MDEL_ZNAMA;
                            malam = response.data[i].MDEL_ADDRCUSTOMS;
                            mnilaipabdis = numeral(mnilaipab).format('0,0.00')
                            msupdis=msup;
                            malamdis = malam ? malam.trim() : '';
                            mdodis = mdo.trim();
                        } else {
                            msupdis='';
                            malamdis = '';
                            mdodis = '';
                        }
                    }
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = mnomordis
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = mnomorpabdis
                    newcell = newrow.insertCell(2)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = mdatepabdis
                    newcell = newrow.insertCell(3)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = mnomorpendaftarandis
                    newcell = newrow.insertCell(4)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = mtanggalDaftardis
                    newcell = newrow.insertCell(5)
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = mnomorin
                    newcell = newrow.insertCell(6)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data[i].MITM_HSCD
                    newcell = newrow.insertCell(9)
                    newcell.style.cssText = "text-align: right";
                    newcell.innerHTML = numeral(response.data[i].DLVPRC_QTY).format('0,0')
                    newcell = newrow.insertCell(10);
                    newcell.style.cssText = "text-align: center";
                    newcell.innerHTML = response.data[i].MITM_STKUOM
                    newcell = newrow.insertCell(11);
                    let priceper = '';
                    if(response.data[i].DLVPRC_PRC) {
                        priceper = response.data[i].DLVPRC_PRC.substr(0,1) == '.' ? '0'+response.data[i].DLVPRC_PRC : response.data[i].DLVPRC_PRC
                    } else {

                    }
                    newcell.innerHTML = priceper
                    newcell.style.cssText = "text-align: right"
                    newcell = newrow.insertCell(12);
                    newcell.innerHTML = response.data[i].VALUTA
                    newcell.classList.add('text-center')
                    newcell = newrow.insertCell(13);
                    newcell.style.cssText = "text-align: right";
                    newcell.innerHTML = mnilaipabdis
                    newcell = newrow.insertCell(14);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = msupdis
                    newcell = newrow.insertCell(15);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = malamdis
                    newcell = newrow.insertCell(16)
                    newcell.innerHTML = nomorInvDis
                    newcell.style.cssText= "white-space: nowrap"
                    newcell = newrow.insertCell(17);
                    newcell.innerHTML = nomorInvSMTDis
                    newcell.style.cssText= "white-space: nowrap"
                    newcell = newrow.insertCell(18);
                    newcell.innerHTML = mdodis
                    newcell.style.cssText= "white-space: nowrap"
                    newcell = newrow.insertCell(19)
                    newcell = newrow.insertCell(20)
                    newcell.innerHTML = msppdis
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('rpab_out_status').innerHTML = ttlrows>0 ? '': 'data not found';

            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                rpab_out_status.innerHTML = ''
            }
        });
    });
    $("#rpab_out_btnfinditem").click(function (e) {
        $("#rpab_out_ITEMLIST").modal('show');
    });
    $("#rpab_out_ITEMLIST").on('shown.bs.modal', function(){
        $("#rpab_out_txtsearch").focus();
    });

    $("#rpab_out_txtsearch").keypress(function (e) {
        if(e.which==13){
            let mkey = $(this).val();
            let msearchby = $("#rpab_out_srchby").val();
            $("#rpab_out_tblitmloc").text("Please wait...");
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
                    $("#rpab_out_tblitmloc").text("");
                    $('#rpab_out_tblitm tbody').html(tohtml);
                }
            });
        }
    });
    $('#rpab_out_tblitm tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#rpab_out_tblitm tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem = $(this).closest("tr").find('td:eq(0)').text();
        document.getElementById("rpab_out_txt_itemcd").value=mitem;
        $("#rpab_out_ITEMLIST").modal('hide');
    });
    $("#rpab_out_btnallitem").click(function (e) {
        document.getElementById('rpab_out_txt_itemcd').value='';
    });
    $("#rpab_out_typedoc").change(function (e) {
        document.getElementById('rpab_out_bctype').innerText=$("#rpab_out_typedoc option:selected").html();
        let mid = document.getElementById('rpab_out_typedoc').value;
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
                document.getElementById('rpab_out_zsts').innerHTML = str;
                document.getElementById('rpab_out_zsts').focus();
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#rpab_out_typetpb").change(function(e){
        let vals = $(this).val();
        let avals = vals.split("#");
        document.getElementById("rpab_out_tpbtype").innerText = avals[1];
    });
    $("#rpab_out_btn_xls").click(function (e) {
        let mdoctype = document.getElementById('rpab_out_typedoc').value;
        let mtpbtype = document.getElementById('rpab_out_typetpb').value;
        let mitmcd = document.getElementById('rpab_out_txt_itemcd').value;
        let mdate0 = document.getElementById('rpab_out_date0').value;
        let mdate1 = document.getElementById('rpab_out_date1').value;
        let mnoaju = document.getElementById('rpab_out_noaju').value;
        let mstatus = document.getElementById('rpab_out_zsts').value;
        let itemtype = document.getElementById('rpab_out_cmb_itemtype').value;

        let atpb = mtpbtype.split("#");
        mtpbtype = atpb[0];
        let mtpbtypes = atpb[1];
        Cookies.set('RP_PAB_DOCTYPE', mdoctype, {expires:365});
        Cookies.set('RP_PAB_TPBTYPE', mtpbtype, {expires:365});
        Cookies.set('RP_PAB_TPBTYPES', mtpbtypes, {expires:365});
        Cookies.set('RP_PAB_ITMCD', mitmcd, {expires:365});
        Cookies.set('RP_PAB_DATE0', mdate0, {expires:365});
        Cookies.set('RP_PAB_DATE1', mdate1, {expires:365});
        Cookies.set('RP_PAB_NOAJU', mnoaju, {expires:365});
        Cookies.set('RP_PAB_RCVSTATUS', mstatus, {expires:365});
        Cookies.set('RP_PAB_ITMTYPE', itemtype, {expires:365})
        window.open("<?=base_url('laporan_pembukuan_keluar_xlsx')?>",'_blank');
    });

</script>