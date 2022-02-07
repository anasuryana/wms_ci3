<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scr_btn_new"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="scr_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="scr_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Document</label>                    
                    <input type="text" class="form-control" id="scr_txt_doc" placeholder="<<auto number>>" readonly>                    
                    <button class="btn btn-primary" id="scr_btnmod"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Date</label>                    
                    <input type="text" class="form-control" id="scr_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scr_btnplus" ><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="scr_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="scr_divku">
                    <table id="scr_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%s">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc. of pending</th>
                                <th>Item Code</th>
                                <th>Lot No</th>
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
<div class="modal fade" id="scr_MOD">
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
                            <input type="text" class="form-control" id="scr_txtsearch" onkeypress="scr_e_search(event)" maxlength="15" onfocus="this.select()" required placeholder="...">                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Search by</span>                            
                            <select id="scr_itm_srchby" onchange="scr_e_fokus()" class="form-select">
                                <option value="no">Document No</option>
                                <option value="rmrk">Remarks</option>                            
                            </select>                  
                        </div>
                    </div>
                </div>            
                <div class="row">
                    <div class="col mb-1 text-right">
                        <span class="badge bg-info" id="scr_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="scr_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
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
<div class="modal fade" id="scr_MODpen">
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
                            <input type="text" class="form-control" id="scr_txtsearchpending"  maxlength="15" onfocus="this.select()" required placeholder="...">                        
                        </div>
                    </div>
                </div>                          
                <div class="row">
                    <div class="col mb-1">
                        <button class="btn btn-sm btn-primary" id="scr_btngetsel">Get selected rows</button>
                    </div>
                    <div class="col mb-1 text-right">
                        <span class="badge bg-info" id="scr_lblinfopending"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="scr_tblpen" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle"><input type="checkbox" id="scr_ckall" onclick="scr_clooptable()"> </th>
                                        <th rowspan="2" class="align-middle">Document</th>
                                        <th rowspan="2" class="align-middle">Date</th>
                                        <th rowspan="2" class="align-middle">Item Code</th>
                                        <th rowspan="2" class="align-middle">Lot No</th>
                                        <th colspan="4" class="text-center">QTY</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Pend.</th>
                                        <th class="text-right">Release</th>
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
    var scr_tbllength         = 1;
    var scr_tblrowindexsel    = '';
    var scr_tblcolindexsel    = '';
    var scr_e_col1;
    var scr_rowslist = [];
    $("#scr_divku").css('height', $(window).height()*68/100);
    $("#scr_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#scr_txt_date").datepicker('update', '<?=$dt?>');
    scr_e_col1 = function (e) { 
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };
        
        if(e.shiftKey && keyCode==9){            
            if(scr_tblcolindexsel>1){
                scr_tblcolindexsel--;
            }
        } else{
            switch(keyCode){
                case 9:
                    if(scr_tblcolindexsel<3)
                        { scr_tblcolindexsel++; }
                    else{
                        scr_tblcolindexsel=1;
                        if(scr_tblrowindexsel<(scr_tbllength-1))
                        {scr_tblrowindexsel++;}
                    }
            }
        }
        if (e.ctrlKey) {            
            switch (keyCode) {
                case arrow.up:
                    if(scr_tblrowindexsel>0){
                        var tables = $("#scr_tbl tbody");
                        tables.find('tr').eq(--scr_tblrowindexsel).find('td').eq(scr_tblcolindexsel).find('input').focus();
                    }
                    break;
                case arrow.down:
                    if(scr_tblrowindexsel<(scr_tbllength-1)){                        
                        let tables = $("#scr_tbl tbody");                        
                        tables.find('tr').eq(++scr_tblrowindexsel).find('td').eq(scr_tblcolindexsel).find('input').focus();                        
                    }
                    break;
                case 78:///N
                    scr_btnadd();
                    break;
                case 68://D
                    $("#scr_tbl tbody > tr:last").remove();scr_renumberrow();                    
                    break;
            }
        }
    };
    function scr_btnadd(){
        $('#scr_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
        '<td></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scr_e_col1(event)" onpaste="scr_e_pastecol1(event)" maxlength="50"></td>'+
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scr_e_col1(event)" onpaste="scr_e_pastecol1(event)" style="text-align: right"></td>'+         
        '<td><input type="text" class="form-control form-control-sm" onkeydown="scr_e_col1(event)" onpaste="scr_e_pastecol1(event)" maxlength="50"></td>'+
        '</tr>');
        scr_tbllength = $('#scr_tbl tbody > tr').length;    
        // scr_tblrowindexsel = scr_tbllength-1;
        scr_renumberrow();   
    }

    function scr_renumberrow(){
        let rows =1;
        let table = $("#scr_tbl tbody");       
        table.find('tr').each(function (i) {
            let $tds = $(this).find('td');
                $tds.eq(0).text(rows);
            rows++;
        });
    }
    function scr_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/plain');        
        let adatapas = datapas.split('\n');        
        let ttlrowspasted = 0;
        for(let c=0;c<adatapas.length;c++){
            if(adatapas[c].trim()!=''){
                ttlrowspasted++;
            }
        }
        let table = $("#scr_tbl tbody");
        let incr =0;        
        
        if((scr_tbllength-scr_tblrowindexsel)<ttlrowspasted){
            let needRows = ttlrowspasted - (scr_tbllength-scr_tblrowindexsel);
            for(let i = 0;i<needRows;i++){
                scr_btnadd();
            }
        }
        for(let i=0;i<ttlrowspasted;i++){
            let mcol = adatapas[i].split('\t');
            let ttlcol = mcol.length;
            for(let k=0;(k<ttlcol) && (k<4);k++){
                table.find('tr').eq((i+scr_tblrowindexsel)).find('td').eq((k+scr_tblcolindexsel)).find('input').val(mcol[k].trim());
            }                
        }
        event.preventDefault();
    }
    $("#scr_btn_new").click(function (e) {         
        $("#scr_tbl tbody").empty();
        document.getElementById('scr_txt_doc').value='';
    });
    $("#scr_btnmins").click(function (e) { 
        let konf = confirm("Are you sure want to delete ?");
        if(konf){
            let mdoc = document.getElementById('scr_txt_doc').value;
            let table = $("#scr_tbl tbody");
            let mitem = table.find('tr').eq(scr_tblrowindexsel).find('td').eq(1).find('input').val();                
            table.find('tr').eq(scr_tblrowindexsel).remove();
            scr_renumberrow();
            $.ajax({
                type: "get",
                url: "<?=base_url('SCR/remove')?>",
                data: {inkey: mitem, indoc: mdoc},
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
    });
    $('#scr_tbl tbody').on( 'click', 'tr', function () {  
        scr_tblrowindexsel =$(this).index();
        if ($(this).hasClass('table-active') ) {	
            $(this).removeClass('table-active');
        } else {                    			
            $('#scr_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
    });
    $('#scr_tbl tbody').on( 'click', 'td', function () {            
        scr_tblcolindexsel = $(this).index();         
    });
    $("#scr_btn_save").click(function (e) { 
        let konf = confirm('Are you sure want to save ?');
        if(konf){
            let mdoc = document.getElementById('scr_txt_doc').value;   
            let mdate = document.getElementById('scr_txt_date').value;
            let mtbl = document.getElementById('scr_tbl');
            let mtbltr = mtbl.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let mdocpen, mitem,mlot,mqtypend, mqty, mremark;
            let adoc=[], aitem = [],alot=[], aqty = [], aremark = [];
            let allowed = true;
            if(ttlrows>1){                
                for(let i=1;i<ttlrows;i++){
                    mdocpen = mtbl.rows[i].cells[1].innerText;
                    mitem = mtbl.rows[i].cells[2].innerText;
                    mlot = mtbl.rows[i].cells[3].innerText;                    
                    mqtypend = mtbl.rows[i].cells[4].innerText;                    
                    mqty = numeral(mtbl.rows[i].cells[5].innerText).value();                    
                    if(numeral(mqtypend).value()< numeral(mqty).value()){
                        alertify.warning('QTY Scrap > Qty Pending!');
                        mtbl.rows[i].cells[5].focus();
                        allowed=false; break;
                    } 
                    mremark = mtbl.rows[i].cells[6].innerText;
                    if(mitem.trim()!=''){
                        if(!isNaN(mqty)){
                            adoc.push(mdocpen);
                            aqty.push(mqty);
                            aitem.push(mitem);
                            alot.push(mlot);
                            aremark.push(mremark);
                        }                        
                    }
                }
                if(aitem.length>0 && allowed){
                    if(mdoc!=''){                                        
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SCR/edit')?>",
                            data: {indocreff: adoc, initem: aitem, inlot: alot, inqty: aqty, indoc: mdoc, indate: mdate, inremark: aremark},
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
                            url: "<?=base_url('SCR/set')?>",
                            data: {indocreff: adoc ,initem: aitem,inlot: alot, inqty: aqty, indate: mdate, inremark: aremark},
                            dataType: "json",
                            success: function (response) {
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);                                
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('scr_txt_doc').value=response.data[0].ref;
                                    $("#scr_tbl tbody").empty();
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
    $("#scr_btnmod").click(function (e) {
        $("#scr_MOD").modal('show');
    });
    function scr_e_fokus(){
        document.getElementById('scr_txtsearch').focus();
    }
    $("#scr_MOD").on('shown.bs.modal', function(){
        document.getElementById('scr_txtsearch').focus();        
    });
    $("#scr_MODpen").on('shown.bs.modal', function(){
        document.getElementById('scr_txtsearchpending').focus();        
    });
    function scr_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('scr_txtsearch').value;
            let mby = document.getElementById('scr_itm_srchby').value;           
            document.getElementById('scr_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('SCR/search')?>",
                data: {inid: msearch, inby: mby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';                    
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].SCR_DOC+"</td>"+
                        "<td>"+response[i].SCR_DT+"</td>"+
                        "<td class='text-right'>"+response[i].TTLITEM+"</td>"+
                        "<td>"+response[i].REMARK+"</td>"+
                        "</tr>";
                    }
                    $("#scr_tblsaved tbody").html(tohtml);                    
                    document.getElementById('scr_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    $('#scr_tblsaved tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#scr_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        var mdoc    = $(this).closest("tr").find('td:eq(0)').text();
        var mdate   = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById('scr_txt_doc').value=mdoc.trim();
        $("#scr_txt_date").datepicker('update', mdate);
        $('#scr_MOD').modal('hide');scr_e_getdetail(mdoc);
    });

    function scr_e_getdetail(pdoc){
        $.ajax({
            type: "get",
            url: "<?=base_url('SCR/getbyid')?>",
            data: {indoc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                let tohtml = '';
                for(let i=0;i<ttlrows;i++){
                    tohtml += "<tr>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response[i].SCR_REFFDOC+'</td>'+
                    '<td>'+response[i].SCR_ITMCD+'</td>'+
                    '<td>'+response[i].SCR_ITMLOT+'</td>'+
                    '<td class="text-right">'+response[i].PND_QTY+'</td>'+
                    '<td contenteditable="true" class="text-right bg-white">'+response[i].SCR_QTY+'</td>'+
                    '<td contenteditable="true" class="bg-white">'+response[i].SCR_REMARK+'</td>'+
                    "</tr>";
                }
                $("#scr_tbl tbody").html(tohtml);                    
                document.getElementById('scr_lblinfo').innerText = ttlrows + ' row(s) found';
                scr_tbllength = $('#scr_tbl tbody > tr').length;
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#scr_btnplus").click(function (e) { 
        $("#scr_MODpen").modal('show');
    });

    $("#scr_txtsearchpending").keypress(function (e) { 
        if(e.which==13){
            let keys = document.getElementById('scr_txtsearchpending').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('PND/search_sc')?>",
                data: {inkeys: keys},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    let mcktype = '';
                    let mpndqty, mrelqty, mscrqty, mremainqty;                    
                    for(let i=0;i<ttlrows;i++){
                        mpndqty = numeral(response[i].PND_QTY).value();
                        mrelqty = numeral(response[i].RELQTY).value();
                        mscrqty = numeral(response[i].SCRQTY).value();
                        mremainqty = (mpndqty-(mrelqty+mscrqty));                        
                        if( mremainqty >0 ){
                            mcktype = '<input type="checkbox">';
                        } else {
                            mcktype = '<input type="checkbox" disabled>';                            
                        }
                        tohtml += "<tr>"+
                        '<td>'+mcktype+'</td>'+
                        '<td>'+response[i].PND_DOC+'</td>'+
                        '<td>'+response[i].PND_DT+'</td>'+
                        '<td>'+response[i].PND_ITMCD+'</td>'+
                        '<td>'+response[i].PND_ITMLOT+'</td>'+
                        '<td class="text-right">'+numeral(mpndqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mrelqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mscrqty).format('0,0')+'</td>'+
                        '<td class="text-right">'+numeral(mremainqty).format('0,0')+'</td>'+
                        "</tr>";
                    }
                    $("#scr_tblpen tbody").html(tohtml);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    function scr_clooptable(){
        let cktemp ;
        let tabell = document.getElementById("scr_tblpen");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let mckall = document.getElementById("scr_ckall");
        for(let x=0;x<mrows.length;x++){
            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
            if(!cktemp.disabled){
                cktemp.checked=mckall.checked;
            }            
        }                        
    }

    function SCR_Baris(pdoc, pitem, plot, pqty){
        this.doc = pdoc;
        this.item = pitem;
        this.lot = plot;
        this.qty = pqty;
    }

    $("#scr_btngetsel").click(function (e) { 
        let tabell = document.getElementById("scr_tblpen");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let nrow;
        let ttlsel = scr_rowslist.length;
        for(let x=0;x<mrows.length;x++){
            let ck = tableku2.rows[x].cells[0].getElementsByTagName("input")[0];
            let mdoc = tableku2.rows[x].cells[1].innerText;
            let mitem = tableku2.rows[x].cells[3].innerText;
            let mlot = tableku2.rows[x].cells[4].innerText;
            let mqty = tableku2.rows[x].cells[8].innerText;
            if(ck.checked){
                ttlsel = scr_rowslist.length;
                if(ttlsel==0){
                    nrow = new SCR_Baris(mdoc, mitem , mlot, mqty);
                    scr_rowslist.push(nrow);
                } else {
                    let isexist = false;
                    for(let k=0;k<ttlsel;k++){
                        if(scr_rowslist[k].doc==mdoc && scr_rowslist[k].item == mitem && scr_rowslist[k].lot==mlot){
                            isexist = true;
                            break;
                        }
                    }
                    if(!isexist){
                        nrow = new SCR_Baris(mdoc, mitem , mlot, mqty);
                        scr_rowslist.push(nrow);
                    }
                }
                
            }            
        } 
        ttlsel = scr_rowslist.length;
        let tabeledes = document.getElementById("scr_tbl"); let tohtml = '';
        tableku2 = tabeledes.getElementsByTagName("tbody")[0];
        for(let i =0 ; i < ttlsel; i++){
            tohtml += '<tr>'+
            '<td>'+(i+1)+'</td>'+
            '<td>'+scr_rowslist[i].doc+'</td>'+
            '<td>'+scr_rowslist[i].item+'</td>'+
            '<td>'+scr_rowslist[i].lot+'</td>'+
            '<td class="text-right">'+scr_rowslist[i].qty+'</td>'+
            '<td contenteditable="true" class="text-right bg-white">'+scr_rowslist[i].qty+'</td>'+
            '<td contenteditable="true" class="bg-white"></td>'+
            '</tr>';
        }
        tableku2.innerHTML = tohtml;
        $("#scr_MODpen").modal('hide');
    });

    $("#scr_btn_print").click(function (e) { 
        let mdoc = document.getElementById("scr_txt_doc").value;
        if(mdoc.trim()==''){
            alertify.message('Please choose document first');
            return;
        }
        Cookies.set('CKSCRDOC_NO', mdoc , {expires:365});
        window.open("<?=base_url('printscraping_doc')?>" ,'_blank');
    });
</script>