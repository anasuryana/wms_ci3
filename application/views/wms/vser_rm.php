<div style="padding:5px" >
    <div class="container-xxl">
        <div class="row">
            <div class="col-sm-4 mb-1 pr-1 pl-1">                                          
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Item</h4>
                    </div>
                    <div class="card-body p-1">
                        <div class="col-md-12 order-md-0 pl-1 pr-1">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">DO</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_jobno" required readonly>                                        
                                        <button class="btn btn-primary" type="button" id="serrm_btn_smod_job"><i class="fas fa-search"></i></button>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Code</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_itmcd" required readonly >                                                                               
                                    </div>
                                </div>						
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Name</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_itmnm" required readonly>								
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">DO Qty</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_jobqty" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Qty</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_qty" required>
                                    </div>
                                </div>                            
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Lot No</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_lotno" required>
                                    </div>
                                </div>                            
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm mb-1">                                        
                                        <span class="input-group-text" >Country</span>                                        
                                        <select class="form-select" id="serrm_sel_country">
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
                                <div class="col-md-6 mb-1 text-right">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            RoHS
                                        </label>
                                    </div>
                                    <div class="form-check-inline">                                        
                                        <input type="radio" id="serrm_ckyes" class="form-check-input" name="optradio" value="1" checked>
                                        <label class="form-check-label" for="serrm_ckyes">
                                        Yes
                                        </label>
                                    </div>
                                    <div class="form-check-inline">                                        
                                        <input type="radio" id="serrm_ckno" class="form-check-input" name="optradio" value="0">                                        
                                        <label class="form-check-label" for="serrm_ckyes">
                                        No
                                        </label>
                                    </div>
                                </div>                  
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 text-right">
                                    <button title="Print" class="btn btn-primary btn-sm" type="button" id="serrm_btn_prc"><i class="fas fa-save"></i></button>
                                </div>
                            </div>                       
                        </div>
                    </div>
                </div>
            </div>	
            <div class="col-sm-8 pr-1 pl-1">
                <div class="card mb-9 shadow-sm">			
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Last DO Info</h4>
                    </div>
                    <div class="card-body p-1">
                        <div class="col-md-12 order-md-0 pl-1 pr-1">
                            <div class="row">
                                <div class="col-md-12 mb-1">                               
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">DO No</span>                                        
                                        <input type="text" class="form-control" id="serrm_txt_jobno_tobfind" required readonly >                                                                                
                                        <button class="btn btn-outline-primary" type="button" id="serrm_btn_findserrm"><i class="fas fa-search"></i></button>
                                        <button class="btn btn-outline-primary" type="button" id="serrm_btn_print"><i class="fas fa-print" title="Print selected ID"></i></button>                                        
                                    </div>                                                                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <table id="serrm_tbllastjobno" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead>
                                            <tr>	
                                                <th class="text-center"><input type="checkbox" id="serrm_ckall" title="Select all"></th>
                                                <th>ID</th>
                                                <th>Item Code</th>                                                
                                                <th>Qty</th>
                                                <th>Create Date</th>
                                                <th>User</th>
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
    </div>
</div>
<div class="modal fade" id="serrm_MODJOB">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Data Table</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="serrm_txtsearchjob" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="serrm_lblinfo_tblserrm"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="serrm_job_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>                                    
                                    <th>DO No</th>                                    
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

<div class="modal fade" id="serrm_MODSELJOB">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">DO List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="serrm_txtseljob" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="serrm_lblinfo_tbljob"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="serrm_divku">
                        <table id="serrm_seljob_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">DO No</th>
                                    <th rowspan="2" class="align-middle">Item Code</th>
                                    <th rowspan="2" class="align-middle">Item Name</th>
                                    <th colspan="3" class="text-center">QTY</th>
                                </tr>
                                <tr>
                                    <th class="text-right">DO</th>
                                    <th class="text-right">Labeled</th>
                                    <th class="text-right">Unlabeled</th>
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
<div class="modal fade" id="SERRM_MODPRINT">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Label Type</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1 text-center">
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="serrm_rd_def" name="serrm_rd_lbltype" value="0" checked>
                            <label class="custom-control-label" for="serrm_rd_def">Default</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="serrm_rd_ppr" name="serrm_rd_lbltype" value="1">
                            <label class="custom-control-label" for="serrm_rd_ppr">Paper</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div id="serrm_dv">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Size</span>                            
                            <select class="form-select" id="serrm_sel_ppr">
                                <option value="A3">A3</option>
                                <option value="A4">A4</option>
                                <option value="A5">A5</option>
                                <option value="Letter">Letter</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>     
            <div class="row">
                <div class="col mb-1 text-center">
                    <button class="btn btn-primary btn-sm" id="serrm_btn_prcprint">Print</button>
                </div>
            </div>       
        </div>             
      </div>
    </div>
</div>
<script>
    var tableserrmLAST ;
    var serrm_serrmprint = [];
    $("#serrm_txt_proddt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });          
    $("#serrm_btn_prc").click(function (e) { 
        e.preventDefault();
        let mitem = $("#serrm_txt_itmcd").val();
        let mjob = $("#serrm_txt_jobno").val();
        let mqty = $("#serrm_txt_qty").val();    
        let lot = document.getElementById('serrm_txt_lotno').value;
        let mcountry = document.getElementById('serrm_sel_country').value;
        let mrohs = '';
        if(document.getElementById('serrm_ckyes').checked){
            mrohs = document.getElementById('serrm_ckyes').value;
        } else {
            mrohs = document.getElementById('serrm_ckno').value;
        }
    
        if(mjob.trim()==''){
            $("#serrm_txt_jobno").focus();
            return;
        }
        if (mitem.trim()=='') {
            alertify.warning('Please select Item first !');$("#serrm_txt_itmcd").focus();            
            return;
        }
        if(mqty.trim()==''){
            alertify.warning('QTY must be not null');$("#serrm_txt_qty").focus();
            return;
        }
        mqty = numeral(mqty).value();
        if(numeral(mqty).value()<=0){
            alertify.warning('QTY must be greater than zero');$("#serrm_txt_qty").focus();
            return;
        }

        if (lot.trim()==''){
            alertify.warning('Lot not no must be not empty !');
            document.getElementById('serrm_txt_lotno').focus();
            return;
        }
   
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setrm')?>",
            data: {initemcd: mitem, injob: mjob, inqty: mqty, inlot: lot, inrohs: mrohs, incountry: mcountry},
            dataType: "json",
            success: function (response) {
                if(response[0].cd=='0'){
                    alertify.message(response[0].msg);
                } else {
                    alertify.message(response[0].msg);
                    document.getElementById('serrm_txt_jobno_tobfind').value=response[0].doc;                    
                    initLASTserrmList();
                }                
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#serrm_txt_proddt").datepicker().on('changeDate', function(e){
        $("#serrm_txt_prodline").focus();
    });
    initLASTserrmList();
    function initLASTserrmList(){
        var mdoc = $("#serrm_txt_jobno_tobfind").val();        
        tableserrmLAST =  $('#serrm_tbllastjobno').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("SER/getbydoc")?>',
                type: 'get',
                data: {indoc: mdoc}
            },
            columns:[
                { "data": 'serrm_ID',
                    render : function(data, type, row){
                        return '<input type="checkbox">';
                    }
                },
                { "data": 'SER_ID'},
                { "data": 'SER_ITMID'},
                { "data": 'SER_QTY', render: $.fn.dataTable.render.number(',', '.', 0,'')},
                { "data": 'SER_LUPDT'},
                { "data": 'SER_USRID'}    
            ], 
            columnDefs: [
                {
                    targets: 3,
                    className: 'text-right'
                }                
            ]
        });             
    }
    $("#serrm_btn_findserrm").click(function (e) { 
        e.preventDefault();
        $("#serrm_MODJOB").modal('show');
    });
    $("#serrm_MODJOB").on('shown.bs.modal', function(){
        $("#serrm_txtsearchjob").focus();
    });
    $("#serrm_txtsearchjob").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getdocg_rm')?>",
                data: {inkey: mval},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    let tohtml='';
                    for(let i=0;i<ttlrows;i++){
                        tohtml += '<tr>'+                        
                        '<td>'+response[i].SER_DOC+'</td>'+
                        '</tr>';
                    }
                    $("#serrm_job_tbl tbody").html(tohtml);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#serrm_job_tbl tbody').on( 'click', 'tr', function () {

        let mjob =  $(this).closest("tr").find('td:eq(0)').text();        
        $("#serrm_txt_jobno_tobfind").val(mjob);
        $("#serrm_MODJOB").modal('hide');
        initLASTserrmList();
    });
    

    $("#serrm_btn_print").click(function(){
        serrm_serrmprint = [];
        $.each(tableserrmLAST.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myserrm = $tds.eq(1).text();
            if(rana){
                serrm_serrmprint.push(myserrm);
            }
        });
        if (serrm_serrmprint.length>0){
            $("#SERRM_MODPRINT").modal('show');
        } else {
            alertify.message('Please select the data to be printed first');
        }
    });
    $("#serrm_btn_prcprint").click(function (e) { 
        let mrd_type = $("input[name='serrm_rd_lbltype']:checked").val();  
        let msize = $("#serrm_sel_ppr").val();
        Cookies.set('PRINTLABEL_RM', serrm_serrmprint, {expires:365});    
        Cookies.set('PRINTLABEL_RM_LBLTYPE', mrd_type, {expires:365});
        Cookies.set('PRINTLABEL_RM_SIZE', msize, {expires:365});
        window.open("<?=base_url('printlabel_rm')?>",'_blank');
    });
    $("#serrm_ckall").click(function(){
        var ischk = $(this).is(":checked");
        $.each(tableserrmLAST.rows().nodes(), function(key, value) {
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').prop('checked', ischk);
        });
    });
   
    $("#serrm_dv").hide();
    $("#serrm_rd_def").click(function (e) {         
        $("#serrm_dv").hide();
    });
    $("#serrm_rd_ppr").click(function (e) {        
        $("#serrm_dv").show();
    });
    $("#serrm_btn_smod_job").click(function (e) { 
        e.preventDefault();
        $("#serrm_MODSELJOB").modal('show');
    });
    $("#serrm_MODSELJOB").on('shown.bs.modal', function(){
        $("#serrm_txtseljob").focus();
    });
    $("#serrm_txtseljob").keypress(function (e) { 
        if(e.which==13){
            let mval =$(this).val();
            $("#serrm_lblinfo_tbljob").text('Please wait ...');
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/getDO')?>",
                data: {inwo: mval},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;                                      
                    let mydes = document.getElementById("serrm_divku");
                    var myfrag = document.createDocumentFragment();
                    var mtabel = document.getElementById("serrm_seljob_tbl");
                    var cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    var tabell = myfrag.getElementById("serrm_seljob_tbl");
                    var tanblebod = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    var newrow, newcell, newText, unlbl;
                    tanblebod.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        unlbl = Number(response[i].RCV_QTY)-Number(response[i].LBLTTL);
                        newrow = tanblebod.insertRow(-1);                       
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response[i].RCV_DONO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response[i].RCV_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response[i].MITM_SPTNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(numeral(response[i].RCV_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response[i].LBLTTL).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';                     
                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(numeral(unlbl).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';                     
                    }                                        
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                    $("#serrm_lblinfo_tbljob").text(ttlrows + ' row(s) found');
                    $('#serrm_seljob_tbl tbody').on( 'click', 'tr', function () {
                        let mitem   = $(this).closest("tr").find('td:eq(1)').text();
                        let mjob    = $(this).closest("tr").find('td:eq(0)').text();
                        let mitemnm = $(this).closest("tr").find('td:eq(2)').text();
                        let qty     = $(this).closest("tr").find('td:eq(3)').text();
                        let qtylbl  = $(this).closest("tr").find('td:eq(4)').text();
                        if(numeral(qty).value()!=numeral(qtylbl).value()){
                            $("#serrm_txt_jobno").val(mjob); $("#serrm_txt_itmcd").val(mitem);
                            document.getElementById('serrm_txt_qty').focus();
                            document.getElementById('serrm_txt_itmnm').value=mitemnm;
                            document.getElementById('serrm_txt_jobqty').value=qty;
                            $('#serrm_seljob_tbl tbody').html('');
                            $("#serrm_MODSELJOB").modal('hide');
                        } else {
                            alertify.message('the dat');
                        }
                    });
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    $("#serrm_txt_qty").keyup(function (e) { 
        let mval = numeral($(this).val()).format(',');
        $(this).val(mval);
    });

    $("#serrm_dv").hide();
    $("#serrm_rd_def").click(function (e) {         
        $("#serrm_dv").hide();
    });
    $("#serrm_rd_ppr").click(function (e) {        
        $("#serrm_dv").show();
    });
</script>