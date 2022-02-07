<div style="padding: 10px">
	<div class="col-md-12 order-md-1">
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New"     id="rcv_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save"    id="rcv_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>                    
                    <button title="Find saved docs"  id="rcv_btnfindsaved" class="btn btn-outline-secondary" ><i class="fas fa-search"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Trans. Date</span>
                    </div>
                    <input type="text" class="form-control" id="rcv_txt_date" required readonly>                   
                </div>
            </div>         
            <div class="col-md-8 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Vendor DN No.</span>
                    </div>
                    <input type="text" class="form-control" id="rcv_txt_dn" required>
                    <div class="input-group-append">
                        <button title="Search data from STX-I" class="btn btn-outline-secondary" type="button" id="rcv_btn_dnfind"><i class="fas fa-search"></i></button>                        
                    </div>                
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
            </div>
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Vendor</span>
                    </div>
                    <input type="text" class="form-control" id="rcv_txt_ven" required readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="rcv_btn_venfind"><i class="fas fa-search"></i></button>                        
                    </div>
                </div>
            </div>
            <div class="col-md-1 mb-1">
                <span title="Currency" class="badge badge-success" id="rcv_lbl_cur"></span>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-4 mb-1">
            </div>
            <div class="col-md-8 mb-1">
                <textarea class="form-control" rows="2" id="rcv_txa_ven" readonly></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button title="Shortcut keyboard" class="btn btn-info btn-sm" id="rcv_btn_info"><i class="fas fa-info"></i></button>
            </div>
            <div class="col-md-6 mb-3 text-right">
                <div class="btn-group btn-group-sm">
                    <button id="rcv_btn_add"  title="Add a new row" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                    <button id="rcv_btn_remove" title="Remove selected row" class="btn btn-outline-warning"><i class="fas fa-minus"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive">
                    <table id="rcv_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>PO No</th>
                                <th>Qty</th>
                                <th>Price</th>
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
<div class="modal fade" id="RCV_KEYINFO">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Shortcut Keyboard</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Shortcut</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><kbd>CTRL + ALT +  N</kbd></td>
                                <td>Add a new row</td>
                            </tr>
                            <tr>
                                <td><kbd>CTRL + ALT +  D</kbd></td>
                                <td>Remove a last row</td>
                            </tr>

                            <tr>
                                <td><kbd>CTRL + ↑</kbd></td>
                                <td>Move Up</td>
                            </tr>
                            <tr>
                                <td><kbd>CTRL + ↓</kbd></td>
                                <td>Move Down</td>
                            </tr>
                            <tr>
                                <td><kbd>TAB</kbd></td>
                                <td>Move Next</td>
                            </tr>

                            <tr>
                                <td><kbd>SHIFT + TAB</kbd></td>
                                <td>Move Previous</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <div class="alert alert-info" role="alert">
                Place cursor to textbox of Item Code first
            </div>
        </div>             
      </div>
    </div>
</div>

<div class="modal fade" id="RCV_SUP">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Supplier List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="rcv_txt_schsup" maxlength="15" required placeholder="Supplier name...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge badge-info" id="lblinfo_tblsup"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table id="rcv_tblsup" class="table table-hover table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="d-none">Supplier Code</th>
                                <th>Currency</th>
                                <th>Supplier Name</th>
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

<div class="modal fade" id="RCV_DNNOMOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Table</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="rcv_txt_schdono" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge badge-info" id="lblinfo_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table id="rcv_tbldono" class="table table-hover table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>PO No</th>
                                <th>DO No</th>
                                <th>DO Date</th>                                
                                <th>Supplier Name</th>
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

<div class="modal fade" id="RCV_SAVEDDOMOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Table</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="rcv_txt_saveddo" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge badge-info" id="lblinfo_tblsaveddono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table id="rcv_tblsaveddono" class="table table-hover table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>PO No</th>
                                <th>DO No</th>
                                <th>DO Date</th>
                                <th class="d-none">SupplierCD</th>                            
                                <th>Supplier Name</th>
                                <th>Currency</th>
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
<script>
var mm_tblrowindexsel       = '';
var mm_tbllength            = 0;
var mmeventspartcode, mmeventsprice;
var mmisfromstx = false;

$("#rcv_btnfindsaved").click(function(){
    $("#RCV_SAVEDDOMOD").modal('show');
});
$("#RCV_SAVEDDOMOD").on('shown.bs.modal', function(){
    $("#rcv_txt_saveddo").focus();
});
$("#rcv_txt_saveddo").keypress(function (e) { 
    if(e.which==13){
        var mval = $(this).val();
        $("#rcv_tblsaveddono tbody").html('');
        $("#lblinfo_tblsaveddono").text('Please wait...');
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getsaveddo_list')?>",
            data: {pdo : mval },
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml = '';
                for(var i=0 ;i<ttlrows;i++){
                    tohtml += '<tr style="cursor:pointer">'+
                    '<td>'+response[i].RCV_PO+'</td>'+
                    '<td>'+response[i].RCV_DONO+'</td>'+
                    '<td>'+response[i].RCV_RCVDATE+'</td>'+
                    '<td class="d-none">'+response[i].RCV_SUPID+'</td>'+
                    '<td>'+response[i].MSUP_SUPNM+'</td>'+
                    '<td class="text-center">'+response[i].MSUP_SUPCR+'</td>'+
                    '</tr>';
                }
                $("#rcv_tblsaveddono tbody").html(tohtml);
                $("#lblinfo_tblsaveddono").text(ttlrows+' row(s) found');
            }, 
            error : function(xhr,xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
});
$("#RCV_DNNOMOD").on('shown.bs.modal', function(){
    $("#rcv_txt_schdono").focus();
});
$("#rcv_btn_dnfind").click(function(){
    $("#RCV_DNNOMOD").modal('show');
});
$("#rcv_btn_add").click(function(){
    mme_btnadd();
});
$("#rcv_txt_schdono").keypress(function (e) { 
    if(e.which==13){
        var mval = $(this).val();
        $("#rcv_tbldono tbody").html('');
        $.ajax({
            type: "get",
            url: "<?=base_url()?>"+"RCV/getdostxi_list",
            data: {pdo: mval },
            dataType: "json",
            success: function (response) {
                var table = $("#rcv_tbldono tbody");
                var ttlrows = response.length;
                var tohtml;
                for(var i=0;i<ttlrows;i++){
                    tohtml += '<tr style="cursor:pointer">'+
                    '<td>'+response[i].SSHP_CPONO+'</td>'+
                    '<td>'+response[i].SSHP_DONO+'</td>'+
                    '<td>'+response[i].SHPDATE+'</td>'+            
                    '<td>STX-I</td>'+
                    '</tr>';
                }
                table.html(tohtml);
            },
            error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
});
$('#rcv_tbl tbody').on( 'click', 'tr', function () {  
    mm_tblrowindexsel =$(this).index();
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcv_tbl tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    
});
$('#rcv_tbldono tbody').on( 'click', 'tr', function () {      
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcv_tbldono tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var table = $("#rcv_tbl tbody");
    table.html('');
    var middo =  $(this).closest("tr").find('td:eq(1)').text();
    $("#rcv_txt_dn").val(middo.trim());
    $("#rcv_txt_dn").prop('readonly', true);
    $.ajax({
        type: "get",
        url: "<?=base_url('RCV/getdetaildo')?>",
        data: {indo: middo},
        dataType: "json",
        success: function (response) {
            var ttlrows = response.length;
            var tohtml = '';
            for(var i=0;i<ttlrows;i++){
                tohtml += '<tr>'+
                '<td>'+(i+1)+'</td>'+
                '<td>'+response[i].MITM_ITMCD+'</td>'+
                '<td>'+response[i].MITM_SPTNO+'</td>'+
                '<td>'+response[i].SSHP_CPONO+'</td>'+
                '<td class="text-right">'+numeral(response[i].SSHP_SHPQT).format(',')+'</td>'+
                '<td class="text-right">'+numeral(response[i].SSHP_SLPRC).format(',')+'</td>'+
                '</tr>';
            }
            table.html(tohtml);mmisfromstx=true;
        }
    });
    $("#RCV_DNNOMOD").modal('hide');
});

$('#rcv_tblsaveddono tbody').on( 'click', 'tr', function () {
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcv_tblsaveddono tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var middo   =  $(this).closest("tr").find('td:eq(1)').text();
    var mdtdo   =  $(this).closest("tr").find('td:eq(2)').text();
    var midsup  =  $(this).closest("tr").find('td:eq(3)').text();
    var mnmsup  =  $(this).closest("tr").find('td:eq(4)').text();
    var mcrsup  =  $(this).closest("tr").find('td:eq(5)').text();

    $("#rcv_txt_dn").val(middo);
    $("#rcv_txt_date").datepicker('update', mdtdo);
    $("#rcv_txt_ven").val(midsup);
    $("#rcv_txa_ven").val(mnmsup);
    $("#rcv_lbl_cur").text(mcrsup);
    $.ajax({
        type: "get",
        url: "<?=base_url('RCV/getdetailsaveddo')?>",
        data: {indo : middo},
        dataType: "json",
        success: function (response) {
            var ttlrows = response.length;
            var tohtml ='';
            for(var i=0;i<ttlrows;i++){
                tohtml += '<tr style="cursor:pointer">'+
                '<td>'+(i+1)+'</td>'+
                '<td><input type="text" class="form-control form-control-sm" onkeydown="mmeventspartcode(event)" onkeypress="mmeventkp(this,event)" value="'+response[i].MITM_ITMCD.trim()+'"></td>'+
                '<td>'+response[i].MITM_SPTNO+'</td>'+
                '<td><input type="text" class="form-control form-control-sm" size="10" onkeypress="mmeventkpcol3(this,event)" value="'+response[i].RCV_PO.trim()+'"></td>'+
                '<td><input type="text" class="form-control form-control-sm" size="10" value="'+response[i].RCV_QTY+'"></td>'+
                '<td><input type="text" class="form-control form-control-sm" size="10" onkeydown="mmeventsprice(event)" value="'+response[i].RCV_AMT+'"></td>'+
                '</tr>';
            }
            $('#rcv_tbl tbody').html(tohtml);
        },
        error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
    $("#RCV_SAVEDDOMOD").modal('hide');    
});
$("#rcv_btn_remove").click(function(){
    var konf = confirm("Are you sure want to delete ?");
    if(konf){
        var table = $("#rcv_tbl tbody");
        var mitem = table.find('tr').eq(mm_tblrowindexsel).find('td').eq(1).find('input').val();
        var mdo = $("#rcv_txt_dn").val();
        $.ajax({
            type: "get",
            url: "<?=base_url()?>"+"RCV/delete",
            data: {initem : mitem, indo: mdo },
            dataType: "text",
            success: function (response) {
                alertify.message(response);
            }, error : function(xhr,xopt, xthrow){
                alertify.error(xthrow);
            }
        });
        table.find('tr').eq(mm_tblrowindexsel).remove();
        mm_renumberrow();
    }
});
$("#RCV_SUP").on('shown.bs.modal', function(){
    $("#rcv_txt_schsup").focus();
});
$("#rcv_btn_venfind").click(function(){
    $("#RCV_SUP").modal('show');
});
$("#rcv_txt_schsup").keypress(function (e) { 
    if(e.which==13){
        $("#lblinfo_tblsup").text("Please wait...");
        $('#rcv_tblsup tbody').html('');
        var mkey = $(this).val();
        $.ajax({
            type: "GET",
            url: "<?=base_url()?>"+"MSTSUP/getbyname",
            data: {inkey : mkey},
            dataType: "json",
            success: function (response) {
                var tohtml = '';
                var ttlrows = response.length;
                if(ttlrows>0){
                    for(var i =0;i<ttlrows;i++) {
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td class='d-none'>"+response[i].MSUP_SUPCD.trim()+"</td>"+
                        "<td>"+response[i].MSUP_SUPCR.trim()+"</td>"+
                        "<td>"+response[i].MSUP_SUPNM.trim()+"</td>"+
                        "</tr>";
                    }
                    $('#rcv_tblsup tbody').html(tohtml);
                }
                $("#lblinfo_tblsup").text(ttlrows+" row(s) found");
            }
        });
    }
});
$("#rcv_btnnew").click(function(){
    $("#rcv_txt_dn").prop('readonly', false);
    rcv_addfirstrow();
    $("#rcv_txt_dn").val('');
    $("#rcv_txt_ven").val('');$("#rcv_txa_ven").val('');mmisfromstx=false;
});

$("#rcv_btn_info").click(function(){
    $("#RCV_KEYINFO").modal('show');
});
function mme_btnadd(){
    $('#rcv_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
    '<td>1</td>'+
    '<td><input type="text" class="form-control form-control-sm" onkeydown="mmeventspartcode(event)" onkeypress="mmeventkp(this,event)"  ></td>'+
    '<td></td>'+
    '<td><input type="text" class="form-control form-control-sm" size="10" onkeypress="mmeventkpcol3(this,event)"></td>'+
    '<td><input type="text" class="form-control form-control-sm" size="10" ></td>'+
    '<td><input type="text" class="form-control form-control-sm" size="10" onkeydown="mmeventsprice(event)"></td>'+    
    '</tr>');
    mm_tbllength = $('#rcv_tbl tbody > tr').length;    
    mm_renumberrow();   
}
function mm_renumberrow(){
    var rows =1;
    var table = $("#rcv_tbl tbody");       
    table.find('tr').each(function (i) {
        var $tds = $(this).find('td');
            $tds.eq(0).text(rows);
        rows++;
    });
}
//special event   
function mmeventonpastecol1(event){
    var datapas = event.clipboardData.getData('text/plain');
    alertify.message(datapas);
}
function mmeventkp(ths,e){
    if(e.which==13){
        var curval = ths.value;
        curval =curval.trim();
        if(curval!=''){
            var table = $("#rcv_tbl tbody");
            table.find('tr').eq(mm_tblrowindexsel).find('td').eq(2).text('please wait..');
            $.ajax({
                type: "get",
                url: "<?=base_url()?>"+"MSTITM/getbyid",
                data: {cid : curval},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;                    
                    var table = $("#rcv_tbl tbody");
                    if(ttlrows>0){                                           
                        table.find('tr').eq(mm_tblrowindexsel).find('td').eq(2).text(response[0].MITM_SPTNO);
                        table.find('tr').eq(mm_tblrowindexsel).find('td').eq(3).find('input').focus();
                    } else {
                        table.find('tr').eq(mm_tblrowindexsel).find('td').eq(2).text("Not found");
                    }
                },
                error: function(xhr,xopt,xthrow){
                    var table = $("#rcv_tbl tbody");
                    table.find('tr').eq(mm_tblrowindexsel).find('td').eq(2).text(xthrow);
                }
            });
        }        
    }    
}
mmeventsprice = function(e){
    e = e || window.event;
    var keyCode = e.keyCode || e.which;
    switch (keyCode) {
        case 9:
            mm_tblrowindexsel++;
            break;
    }
}
function mmeventkpcol3(ths,e){
    if(e.which==13){
        var table = $("#rcv_tbl tbody");
        table.find('tr').eq(mm_tblrowindexsel).find('td').eq(4).find('input').focus();
    }
}
mmeventspartcode = function (e) { 
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };

        if (e.ctrlKey) {            
            switch (keyCode) {
                case arrow.up:
                    if(mm_tblrowindexsel>0){
                        var tables = $("#rcv_tbl tbody");
                        tables.find('tr').eq(--mm_tblrowindexsel).find('td').eq('1').find('input').focus();
                    }
                    break;
                case arrow.down:                    
                    if(mm_tblrowindexsel<(mm_tbllength-1)){                        
                        var tables = $("#rcv_tbl tbody");
                        tables.find('tr').eq(++mm_tblrowindexsel).find('td').eq('1').find('input').focus();
                    }
                    break;
                case 78:///N                    
                    mme_btnadd();
                    break;
                case 68://D
                    $("#rcv_tbl tbody > tr:last").remove();mm_renumberrow();                    
                    break;                
            }
        }        
    };
$("#rcv_txt_date").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
    
});
rcv_addfirstrow();
function rcv_addfirstrow(){    
    var tohtml = '<tr style="cursor:pointer">'+
    '<td>1</td>'+
    '<td><input type="text" class="form-control form-control-sm" onpaste="mmeventonpastecol1(event)" onkeydown="mmeventspartcode(event)" onkeypress="mmeventkp(this,event)" ></td>'+
    '<td></td>'+
    '<td><input type="text" class="form-control form-control-sm" size="10" onkeypress="mmeventkpcol3(this,event)"></td>'+    
    '<td><input type="text" class="form-control form-control-sm" size="10" ></td>'+
    '<td><input type="text" class="form-control form-control-sm" size="10" onkeydown="mmeventsprice(event)"></td>'+    
    '</tr>';
    $('#rcv_tbl tbody').html(tohtml);
}
$("#rcv_txt_date").datepicker('update', '<?php echo date('Y-m-d'); ?>');
$('#rcv_tblsup tbody').on( 'click', 'tr', function () { 
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#rcv_tblsup tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var mid =  $(this).closest("tr").find('td:eq(0)').text();
    var mcur =  $(this).closest("tr").find('td:eq(1)').text();
    var mname = $(this).closest("tr").find('td:eq(2)').text();   
    $("#rcv_txa_ven").val(mname);
    $("#rcv_lbl_cur").text(mcur);
    $("#rcv_txt_ven").val(mid);
    $("#RCV_SUP").modal('hide');
});
$("#rcv_btnsave").click(function(){
    var mdate       = $("#rcv_txt_date").val();
    var mdo         = $("#rcv_txt_dn").val();
    var mven        = $("#rcv_txt_ven").val();
    var mcurrency   = $("#rcv_lbl_cur").text();
    if(mdo.trim()=='' || mdo.trim()=='-'){
        $("#rcv_txt_dn").focus();
        return;
    }
    var apo         = [];
    var aitem       = [];
    var aqty        = [];
    var aprice      = [];
    var tables      = $("#rcv_tbl tbody");
    tables.find('tr').each(function (i) {
        if(!mmisfromstx){
            var $tds = $(this).find('td'),
                rpo =  $tds.eq(3).find('input').val(),
                ritem =  $tds.eq(1).find('input').val(),
                rqty =  $tds.eq(4).find('input').val(),
                rprice =  $tds.eq(5).find('input').val();
            if(ritem!=''){
                apo.push(rpo);
                aitem.push(ritem);
                aqty.push(numeral(rqty).value());
                aprice.push(numeral(rprice).value());
            }
        } else {
            var $tds = $(this).find('td'),                
                rpo =  $tds.eq(3).text(),
                ritem =  $tds.eq(1).text(),
                rqty =  $tds.eq(4).text(),
                rprice =  $tds.eq(5).text();
            if(ritem!=''){                
                apo.push(rpo);
                aitem.push(ritem);
                aqty.push(numeral(rqty).value());
                aprice.push(numeral(rprice).value());
            }      
        }                
    });
    if (apo.length>0){
        var konf = confirm('Are you sure ?');
        if(konf){
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/set')?>",
                data: {inpo: apo, initem: aitem, inqty: aqty, inprice: aprice,
                indate: mdate, indo: mdo, inven: mven, incurr: mcurrency},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                }, error: function(xhr,xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }        
    }    
});
</script>