<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scrser_btn_new"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="scrser_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="scrser_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Document</label>                    
                    <input type="text" class="form-control" id="scrser_txt_doc" placeholder="<<auto number>>" readonly>                    
                    <button class="btn btn-primary" id="scrser_btnmod"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Date</label>                    
                    <input type="text" class="form-control" id="scrser_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scrser_btnplus" ><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="scrser_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="scrser_divku">
                    <table id="scrser_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc. of pending</th>
                                <th>ID</th>
                                <th>Item Code</th>
                                <th>Job No</th>
                                <th class="text-right">QTY</th>
                                <th class="text-right">Scrap QTY</th>
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
<div class="modal fade" id="scrser_MOD">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">      
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Scrap Document List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Search</span>                            
                            <input type="text" class="form-control" id="scrser_txtsearch" onkeypress="scrser_e_search(event)" maxlength="15" onfocus="this.select()" required placeholder="...">                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Search by</span>                            
                            <select id="scrser_itm_srchby" onchange="scrser_e_fokus()" class="form-select">
                                <option value="no">Document No</option>
                                <option value="rmrk">Remarks</option>                            
                                <option value="pnddoc">Pending Document</option>
                            </select>                  
                        </div>
                    </div>
                </div>            
                <div class="row">
                    <div class="col mb-1 text-right">
                        <span class="badge bg-info" id="scrser_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="scrser_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
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
<div class="modal fade" id="scrser_MODpen">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">      
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document of Pending List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Search</span>                            
                            <input type="text" class="form-control" id="scrser_txtsearchpending"  maxlength="15" onfocus="this.select()" required placeholder="...">                        
                        </div>
                    </div>
                </div>                          
                <div class="row">
                    <div class="col mb-1">
                        <button class="btn btn-sm btn-primary" id="scrser_btngetsel">Get selected rows</button>
                    </div>
                    <div class="col mb-1 text-right">
                        <span class="badge bg-info" id="scrser_lblinfopending"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="scrser_tblpen" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle"><input type="checkbox" id="scrser_ckall" onclick="scrser_clooptable()"> </th>
                                        <th rowspan="2" class="align-middle">Document</th>
                                        <th rowspan="2" class="align-middle">Date</th>
                                        <th rowspan="2" class="align-middle">ID</th>
                                        <th rowspan="2" class="align-middle">Item Code</th>
                                        <th rowspan="2" class="align-middle">Job No</th>
                                        <th colspan="4" class="text-center">Qty</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Pend.</th>        
                                        <th class="text-right">Released</th>        
                                        <th class="text-right">Scrap</th>        
                                        <th class="text-right">Remain</th>
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
    var scrser_tbllength         = 1;
    var scrser_tblrowindexsel    = '';
    var scrser_tblcolindexsel    = '';
    var scrser_e_col1;
    var scrser_rowslist = [];
    $("#scrser_divku").css('height', $(window).height()*68/100);
    $("#scrser_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#scrser_txt_date").datepicker('update', '<?=$dt?>');
    scrser_e_col1 = function (e) { 
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };
        
        if(e.shiftKey && keyCode==9){            
            if(scrser_tblcolindexsel>1){
                scrser_tblcolindexsel--;
            }
        } else{
            switch(keyCode){
                case 9:
                    if(scrser_tblcolindexsel<3)
                        { scrser_tblcolindexsel++; }
                    else{
                        scrser_tblcolindexsel=1;
                        if(scrser_tblrowindexsel<(scrser_tbllength-1))
                        {scrser_tblrowindexsel++;}
                    }
            }
        }
        if (e.ctrlKey) {            
            switch (keyCode) {
                case arrow.up:
                    if(scrser_tblrowindexsel>0){
                        var tables = $("#scrser_tbl tbody");
                        tables.find('tr').eq(--scrser_tblrowindexsel).find('td').eq(scrser_tblcolindexsel).find('input').focus();
                    }
                    break;
                case arrow.down:
                    if(scrser_tblrowindexsel<(scrser_tbllength-1)){                        
                        let tables = $("#scrser_tbl tbody");                        
                        tables.find('tr').eq(++scrser_tblrowindexsel).find('td').eq(scrser_tblcolindexsel).find('input').focus();                        
                    }
                    break;
                case 78:///N
                    scrser_btnadd();
                    break;
                case 68://D
                    $("#scrser_tbl tbody > tr:last").remove();scrser_renumberrow();                    
                    break;
            }
        }
    };
    function scrser_btnadd(){
        $('#scrser_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
        '<td></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scrser_e_col1(event)" onpaste="scrser_e_pastecol1(event)" maxlength="50"></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scrser_e_col1(event)" onpaste="scrser_e_pastecol1(event)" style="text-align: right"></td>'+         
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scrser_e_col1(event)" onpaste="scrser_e_pastecol1(event)" maxlength="50"></td>'+
        '</tr>');
        scrser_tbllength = $('#scrser_tbl tbody > tr').length;    
        // scrser_tblrowindexsel = scrser_tbllength-1;
        scrser_renumberrow();   
    }

    function scrser_renumberrow(){
        let rows =1;
        let table = $("#scrser_tbl tbody");       
        table.find('tr').each(function (i) {
            let $tds = $(this).find('td');
                $tds.eq(0).text(rows);
            rows++;
        });
    }
    function scrser_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/plain');        
        let adatapas = datapas.split('\n');        
        let ttlrowspasted = 0;
        for(let c=0;c<adatapas.length;c++){
            if(adatapas[c].trim()!=''){
                ttlrowspasted++;
            }
        }
        let table = $("#scrser_tbl tbody");
        let incr =0;
        // console.log('scrser_tbllength='+scrser_tbllength);
        // console.log('scrser_tblrowindexsel='+scrser_tblrowindexsel);
        // console.log('ttlrowspasted='+ttlrowspasted);
        
        if((scrser_tbllength-scrser_tblrowindexsel)<ttlrowspasted){
            let needRows = ttlrowspasted - (scrser_tbllength-scrser_tblrowindexsel);
            for(let i = 0;i<needRows;i++){
                scrser_btnadd();
            }
        }
        for(let i=0;i<ttlrowspasted;i++){
            let mcol = adatapas[i].split('\t');
            let ttlcol = mcol.length;
            for(let k=0;(k<ttlcol) && (k<4);k++){
                table.find('tr').eq((i+scrser_tblrowindexsel)).find('td').eq((k+scrser_tblcolindexsel)).find('input').val(mcol[k].trim());
            }                
        }
        event.preventDefault();
    }
    $("#scrser_btn_new").click(function (e) {         
        $("#scrser_tbl tbody").empty()
        document.getElementById('scrser_txt_doc').value=''
        scrser_rowslist = []
    });
    $("#scrser_btnmins").click(function (e) { 
        let konf = confirm("Are you sure want to delete ?");
        if(konf){
            let mdoc = document.getElementById('scrser_txt_doc').value;
            let table = $("#scrser_tbl tbody");
            if(mdoc.trim()==''){
                table.find('tr').eq(scrser_tblrowindexsel).remove();
                scrser_renumberrow();
            } else {
                let mkey =  table.find('tr').eq(scrser_tblrowindexsel).find('td').eq(2).text();
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SCR/removeser')?>",
                    data: {inkey: mkey, indoc: mdoc},
                    dataType: "text",
                    success: function (response) {
                        switch(response){
                            case '0':
                                alertify.warning('Could not be deleted, because it is already scanned');break;
                            case '00':
                                alertify.message('Could not be deleted');break;
                            case '1':
                                alertify.success('Deleted');break;
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }                        
        }        
    });
    $('#scrser_tbl tbody').on( 'click', 'tr', function () {  
        scrser_tblrowindexsel =$(this).index();
        if ($(this).hasClass('table-active') ) {	
            $(this).removeClass('table-active');
        } else {                    			
            $('#scrser_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
    });
    $('#scrser_tbl tbody').on( 'click', 'td', function () {            
        scrser_tblcolindexsel = $(this).index();         
    });
    $("#scrser_btn_save").click(function (e) { 
        let konf = confirm('Are you sure want to save ?');
        if(konf){
            let mdoc = document.getElementById('scrser_txt_doc').value;   
            let mdate = document.getElementById('scrser_txt_date').value;
            let mtbl = document.getElementById('scrser_tbl');
            let mtbltr = mtbl.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let mdocpen, mitem,mqtypend, mqty, mremark, mser;
            let adoc=[], aitem = [], aqty = [], aremark = [], aser=[];
            let allowed = true;
            if(ttlrows>1){                
                for(let i=1;i<ttlrows;i++){
                    mdocpen = mtbl.rows[i].cells[1].innerText;
                    mser = mtbl.rows[i].cells[2].innerText;
                    mitem = mtbl.rows[i].cells[3].innerText;                                  
                    mqtypend = mtbl.rows[i].cells[5].innerText;                    
                    mqty = numeral(mtbl.rows[i].cells[6].innerText).value();                    
                    if(numeral(mqtypend).value()< numeral(mqty).value()){
                        alertify.warning('QTY Scrap > Qty Pending!');
                        mtbl.rows[i].cells[5].focus();
                        allowed=false; break;
                    } 
                    mremark = mtbl.rows[i].cells[7].innerText;
                    if(mitem.trim()!=''){
                        if(!isNaN(mqty)){
                            adoc.push(mdocpen);
                            aser.push(mser);
                            aqty.push(mqty);
                            aitem.push(mitem);                            
                            aremark.push(mremark);
                        }                        
                    }
                }
                if(aitem.length>0 && allowed){
                    if(mdoc!=''){                                        
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SCR/edit')?>",
                            data: {indocreff: adoc, initem: aitem, inqty: aqty, indoc: mdoc, indate: mdate, inremark: aremark},
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
                            url: "<?=base_url('SCR/setser')?>",
                            data: {indocreff: adoc ,inser : aser,initem: aitem, inqty: aqty, indate: mdate, inremark: aremark},
                            dataType: "json",
                            success: function (response) {
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);                                
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('scrser_txt_doc').value=response.data[0].ref;
                                    $("#scrser_tbl tbody").empty();
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
    $("#scrser_btnmod").click(function (e) {
        $("#scrser_MOD").modal('show');
    });
    function scrser_e_fokus(){
        document.getElementById('scrser_txtsearch').focus();
    }
    $("#scrser_MOD").on('shown.bs.modal', function(){
        document.getElementById('scrser_txtsearch').focus();        
    });
    $("#scrser_MODpen").on('shown.bs.modal', function(){
        document.getElementById('scrser_txtsearchpending').focus();        
    });
    function scrser_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('scrser_txtsearch').value;
            let mby = document.getElementById('scrser_itm_srchby').value;           
            document.getElementById('scrser_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('SCR/searchser')?>",
                data: {inid: msearch, inby: mby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';                    
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].SCRSER_DOC+"</td>"+
                        "<td>"+response[i].SCRSER_DT+"</td>"+
                        "<td class='text-right'>"+response[i].TTLITEM+"</td>"+
                        "<td>"+response[i].REMARK+"</td>"+
                        "</tr>";
                    }
                    $("#scrser_tblsaved tbody").html(tohtml);                    
                    document.getElementById('scrser_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    $('#scrser_tblsaved tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#scrser_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        var mdoc    = $(this).closest("tr").find('td:eq(0)').text();
        var mdate   = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById('scrser_txt_doc').value=mdoc.trim();
        $("#scrser_txt_date").datepicker('update', mdate);
        $('#scrser_MOD').modal('hide');scrser_e_getdetail(mdoc);
    });

    function scrser_e_getdetail(pdoc){
        $.ajax({
            type: "get",
            url: "<?=base_url('SCR/getbyidser')?>",
            data: {indoc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                let tohtml = '';
                for(let i=0;i<ttlrows;i++){
                    tohtml += "<tr>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response[i].SCRSER_REFFDOC+'</td>'+
                    '<td>'+response[i].SCRSER_SER+'</td>'+
                    '<td>'+response[i].SER_ITMID+'</td>'+
                    '<td>'+response[i].SER_DOC+'</td>'+
                    '<td class="text-right">'+response[i].PNDSER_QTY+'</td>'+
                    '<td class="text-right">'+response[i].SCRSER_QTY+'</td>'+
                    '<td contenteditable="true" class="bg-white">'+response[i].SCRSER_REMARK+'</td>'+
                    "</tr>";
                }
                $("#scrser_tbl tbody").html(tohtml);                    
                document.getElementById('scrser_lblinfo').innerText = ttlrows + ' row(s) found';
                scrser_tbllength = $('#scrser_tbl tbody > tr').length;
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#scrser_btnplus").click(function (e) { 
        $("#scrser_MODpen").modal('show');
    });

    $("#scrser_txtsearchpending").keypress(function (e) { 
        if(e.which==13){
            let keys = document.getElementById('scrser_txtsearchpending').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/searchser_sc')?>",
                data: {inkeys: keys},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    let mcktype = '';
                    let mpndqty, mrelqty, mscrqty, mremainqty;
                    for(let i=0;i<ttlrows;i++){
                        mpndqty = numeral(response[i].PNDSER_QTY).value();
                        mrelqty = numeral(response[i].RELQTY).value();
                        mscrqty = numeral(response[i].SCRSER_QTY).value();
                        mremainqty = (mpndqty-(mrelqty+mscrqty)); 
                        if( mremainqty >0 ){
                            mcktype = '<input type="checkbox">';
                        } else {
                            mcktype = '<input type="checkbox" disabled>';                            
                        }
                        tohtml += "<tr>"+
                        '<td>'+mcktype+'</td>'+
                        '<td>'+response[i].PNDSER_DOC+'</td>'+
                        '<td>'+response[i].PNDSER_DT+'</td>'+
                        '<td>'+response[i].PNDSER_SER+'</td>'+
                        '<td>'+response[i].SER_ITMID+'</td>'+
                        '<td>'+response[i].SER_DOC+'</td>'+
                        '<td class="text-right">'+numeral(mpndqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mrelqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mscrqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mremainqty).format('0,0')+'</td>'+
                        "</tr>";
                    }
                    $("#scrser_tblpen tbody").html(tohtml);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    function scrser_clooptable(){
        let cktemp ;
        let tabell = document.getElementById("scrser_tblpen");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let mckall = document.getElementById("scrser_ckall");
        for(let x=0;x<mrows.length;x++){
            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
            if(!cktemp.disabled){
                cktemp.checked=mckall.checked;
            }
        }                        
    }

    function scrser_Baris(pdoc,pid ,pitem, plot, pqty){
        this.doc = pdoc;
        this.id = pid;
        this.item = pitem;
        this.lot = plot;
        this.qty = pqty;
    }

    $("#scrser_btngetsel").click(function (e) {
        let tabell = document.getElementById("scrser_tblpen");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let nrow;
        let ttlsel = scrser_rowslist.length;
        for(let x=0;x<mrows.length;x++){
            let ck = tableku2.rows[x].cells[0].getElementsByTagName("input")[0];
            let mdoc = tableku2.rows[x].cells[1].innerText;
            let mid = tableku2.rows[x].cells[3].innerText;
            let mitem = tableku2.rows[x].cells[4].innerText;
            let mlot = tableku2.rows[x].cells[5].innerText;
            let mqty = tableku2.rows[x].cells[9].innerText;
            if(ck.checked){
                ttlsel = scrser_rowslist.length;
                if(ttlsel==0){
                    nrow = new scrser_Baris(mdoc,mid, mitem , mlot, mqty);
                    scrser_rowslist.push(nrow);
                } else {
                    let isexist = false;
                    for(let k=0;k<ttlsel;k++){
                        if(scrser_rowslist[k].doc==mdoc && scrser_rowslist[k].id == mid){
                            isexist = true;
                            break;
                        }
                    }
                    if(!isexist){
                        nrow = new scrser_Baris(mdoc, mid ,mitem , mlot, mqty);
                        scrser_rowslist.push(nrow);
                    }
                }
                
            }            
        } 
        ttlsel = scrser_rowslist.length;
        let tabeledes = document.getElementById("scrser_tbl"); let tohtml = '';
        tableku2 = tabeledes.getElementsByTagName("tbody")[0];
        for(let i =0 ; i < ttlsel; i++){
            tohtml += '<tr>'+
            '<td>'+(i+1)+'</td>'+
            '<td>'+scrser_rowslist[i].doc+'</td>'+
            '<td>'+scrser_rowslist[i].id+'</td>'+
            '<td>'+scrser_rowslist[i].item+'</td>'+
            '<td>'+scrser_rowslist[i].lot+'</td>'+
            '<td class="text-right">'+scrser_rowslist[i].qty+'</td>'+
            '<td class="text-right">'+scrser_rowslist[i].qty+'</td>'+
            '<td contenteditable="true" class="bg-white"></td>'+
            '</tr>';
        }
        tableku2.innerHTML = tohtml;
        $("#scrser_MODpen").modal('hide');
        $("#scrser_tblpen tbody").empty();
    });

    $("#scrser_btn_print").click(function (e) { 
        let mdoc = document.getElementById("scrser_txt_doc").value;
        if(mdoc.trim()==''){
            alertify.message('Please choose document first');
            return;
        }
        Cookies.set('CKSCRDOCSER_NO', mdoc , {expires:365});
        window.open("<?=base_url('printscrapingser_doc')?>" ,'_blank');
    });
</script>