<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">DO Number</label>                    
                    <input type="text" class="form-control" id="splitdo_docnoorigin" readonly>                    
                    <button class="btn btn-primary" id="splitdo_btnmod"><i class="fas fa-search"></i></button>                    
                </div>                
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Supplier</label>                
                    <input type="text" class="form-control" id="splitdo_supplier" readonly>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Doc Type</label>                    
                    <input type="text" class="form-control" id="splitdo_typedoc" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">NoAju</label>                    
                    <input type="text" class="form-control" id="splitdo_noaju" readonly>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">NoPen</label>                    
                    <input type="text" class="form-control" id="splitdo_regno" readonly>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">TPB Type</label>                    
                    <input type="text" id="splitdo_typetpb" class="form-control" readonly>                        
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">KPPBC</label>                    
                    <input type="text" class="form-control" id="splitdo_kppbc" readonly>                    
                    <label class="input-group-text">...</label>                    
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Net Weight</label>                    
                    <input type="text" class="form-control" id="splitdo_NW" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Gross Weight</label>                    
                    <input type="text" class="form-control" id="splitdo_GW" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm btn-block">
                    <button class="btn btn-primary " id="splitdo_sync" title="Synchronize data from MEGA"><i class="fas fa-sync"></i> Synchronize</button>
                </div>
            </div>			
            <div class="col-md-6 mb-1 text-right" >
                <span class="badge bg-info" id="lblinfo_splitdo_tbldono"></span>
            </div>
        </div>
        
        <div class="row">				
            <div class="col-md-12 mb-1" >
                <div class="table-responsive" id="splitdo_divku">
                    <table id="splitdo_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>PO No</th>
                                <th>Date</th>                     
                                <th>Currency</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>QTY</th>
                                <th>UM</th>                                
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
<div class="modal fade" id="splitdo_DTLMOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">DO List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">              
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Search</label>                        
                        <input type="text" class="form-control" id="splitdo_txt_search">
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
                        <label class="form-check-label">
                            <input type="radio" id="splitdo_rad_do" class="form-check-input" name="optradio" value="do" checked>DO No
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" id="splitdo_rad_item" class="form-check-input" name="optradio" value="item">Item Code
                        </label>
                    </div>
                </div>
            </div>         
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="lblinfo_splitdo_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table id="splitdo_tbldono" class="table table-hover table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>DO No</th>
                                <th>Supplier</th>                                
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

<div class="modal fade" id="splitdo_PROGRESS">
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

<div class="modal fade" id="splitdo_DTLMOD_rank">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Rank Detail</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">   
            <div class="row">
                <div class="col text-center mb-1">
                    <button class="btn btn-sm btn-primary" id="splitdo_btnstart">Start Synchronizing</button>
                </div>
            </div>
            <div class="row">
                <div class="col text-center mb-1" id="splitdo_alert">
                    
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="table-responsive" id="splitdo_divku_rank">
                        <table id="splitdo_tbldo_rank" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Code Group</th>
                                    <th>Item Code</th>
                                    <th>Grade</th>
                                    <th>QTY</th>
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
    $("#splitdo_btnstart").click(function (e) {
        let ttlrows = $("#splitdo_tbldo_rank tbody>tr").length;
        if(ttlrows<=0){
            alertify.message('there is nothing to be synchronized');
            return;
        }
        let k = confirm("Are You sure?");
        if(k){
            let mdo = document.getElementById("splitdo_docnoorigin").value;            
            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/sync_rank')?>",
                data: {indo: mdo},
                dataType: "json",
                success: function (response) {                    
                    if(response[0].cd!='0'){
                        $("#splitdo_alert").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                     response[0].msg+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                    '</div>');
                    } else {
                        $("#splitdo_alert").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                     response[0].msg+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                    '</div>');
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#splitdo_sync").click(function (e) {
        
        let mdo = document.getElementById('splitdo_docnoorigin').value;
        if(mdo.trim()!=''){
            $("#splitdo_tbldo_rank tbody").html("<tr><td colspan='4' class='text-center'>Please wait <i class='fas fa-spinner fa-spin text-info'></i></td></tr>");
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/get_rank')?>",
                data: {indo: mdo.trim()},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("splitdo_divku_rank");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("splitdo_tbldo_rank");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("splitdo_tbldo_rank");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    let textold = '';
                    let itemhead_txt ='';
                    tableku2.innerHTML='';
                    for(let i=0;i<ttlrows;i++){
                        if(textold!=response.data[i].RCV_ITMCD){
                            textold=response.data[i].RCV_ITMCD;
                            itemhead_txt =textold;
                        }else{
                            itemhead_txt='';
                        }
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(itemhead_txt);            
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].PGRELED_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].PGRELED_GRADE);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(numeral(response.data[i].PGRELED_GRDQT).format('0,0'));
                        newcell.style.cssText='text-align: right';
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
            $("#splitdo_DTLMOD_rank").modal('show');
        } else {
            alertify.message('Please select DO Number');
        }
    });
    $("#splitdo_divku").css('height', $(window).height()*53/100);
    $('#splitdo_tbl tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#splitdo_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem       = $(this).closest("tr").find('td:eq(3)').text();
        let mitemnm     = $(this).closest("tr").find('td:eq(4)').text();        
        $("#splitdo_itmcd").val(mitem); $("#splitdo_itmnm").val(mitemnm);        
    }); 
    var splitdo_selcol='0';
    $("#splitdo_rad_do").click(function (e) {         
        document.getElementById('splitdo_txt_search').focus();
    });
    $("#splitdo_rad_item").click(function (e) {             
        document.getElementById('splitdo_txt_search').focus();
    });
    $("#splitdo_PROGRESS").on('shown.bs.modal', function(){
        
    });    

    $("#splitdo_save").click(function(){       
        $("#splitdo_PROGRESS").modal({backdrop: 'static', keyboard: false}); 
    });    
    
    $('#splitdo_tbl tbody').on( 'click', 'td', function () {            
        splitdo_selcol = $(this).index();         
    });
    $("#splitdo_btnmod").click(function (e) { 
        e.preventDefault();
        $("#splitdo_DTLMOD").modal('show');
    });
    $("#splitdo_DTLMOD").on('shown.bs.modal', function(){
        $("#splitdo_txt_search").focus();
    });
    $("#splitdo_txt_search").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            let mby = '';
            if(document.getElementById('splitdo_rad_do').checked){
                mby = document.getElementById('splitdo_rad_do').value;
            } else {
                mby = document.getElementById('splitdo_rad_item').value;
            }            
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/GetDO_split')?>",
                data: {inid: mval, inby: mby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';                    
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].RCV_DONO+"</td>"+
                        "<td>"+response[i].MSUP_SUPNM+"</td>"+                        
                        "</tr>";
                    }
                    $("#splitdo_tbldono tbody").html(tohtml);                    
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#splitdo_tbldono tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#splitdo_tbldono tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem       = $(this).closest("tr").find('td:eq(0)').text();
        let supplier       = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById("splitdo_supplier").value=supplier;
        $("#splitdo_docnoorigin").val(mitem.trim());
        split_do_e_GetDODetail(mitem.trim());        
        $("#splitdo_DTLMOD").modal('hide');
        WMSGetDODetail(mitem.trim());
    });

    function WMSGetDODetail(pdo){
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/WMSGetDODetail')?>",
            data: {indo: pdo},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.length;
                if (ttlrows>0){
                    let m_nw = ''; let m_gw = ''; let m_ttlamt = '';                    
                    for(let i=0;i<ttlrows;i++){
                        if(response[i].RCV_NW){
                            m_nw = response[i].RCV_NW.substring(0,1)=='.' ? '0' : response[i].RCV_NW;
                        } else {
                            m_nw = 0;
                        }     
                        if(response[i].RCV_GW){
                            m_gw = response[i].RCV_GW.substring(0,1)=='.' ? '0' : response[i].RCV_GW;
                        } else {
                            m_gw = 0;
                        }
                        if(response[i].RCV_TTLAMT){
                            m_ttlamt = response[i].RCV_TTLAMT.substring(0,1)=='.' ? '0' : response[i].RCV_TTLAMT;
                        } else {
                            m_ttlamt = 0;
                        }                    
                        $("#splitdo_typedoc").val(response[i].RCV_BCTYPE);
                        $("#splitdo_noaju").val(response[i].RCV_RPNO);
                        $("#splitdo_regno").val(response[i].RCV_BCNO);                        
                        $("#splitdo_typetpb").val(response[i].RCV_TPB);
                        $("#splitdo_kppbc").val(response[i].RCV_KPPBC);
                        $("#splitdo_NW").val(m_nw);
                        $("#splitdo_GW").val(m_gw);
                        $("#splitdo_amount").val(m_ttlamt);                        
                    }
                } else {
                    $("#splitdo_typedoc").val('');
                    $("#splitdo_noaju").val('');
                    $("#splitdo_regno").val('');
                    $("#splitdo_typetpb").val('');
                    $("#splitdo_kppbc").val('');
                    $("#splitdo_NW").val('');
                    $("#splitdo_GW").val('');
                    $("#splitdo_amount").val('');
                }                
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function split_do_e_GetDODetail(pdo){        
        $("#lblinfo_splitdo_tbldono").text("Please wait . . .");
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/GetDODetail_split')?>",
            data: {indo: pdo},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let tohtml = '';                                
                $("#lblinfo_splitdo_tbldono").text(ttlrows+" row(s) found");
                for(let i=0;i<ttlrows;i++){                    
                    tohtml += "<tr style='cursor:pointer'>"+
                    "<td>"+response.data[i].RCV_PO+"</td>"+
                    "<td>"+response.data[i].RCV_RCVDATE+"</td>"+
                    "<td>"+response.data[i].MSUP_SUPCR+"</td>"+
                    "<td>"+response.data[i].RCV_ITMCD+"</td>"+
                    "<td>"+response.data[i].MITM_ITMD1+"</td>"+
                    "<td class='text-right'>"+numeral(response.data[i].RCV_QTY).format('0,0')+"</td>"+
                    "<td>"+response.data[i].MITM_STKUOM+"</td>"+                   
                    "</tr>";
                }
                $("#splitdo_tbl tbody").html(tohtml);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);$("#rcvcustoms_lbltbl").text("");
            }
        });
    }
    $("#splitdo_typedoc").change(function(){
        $("#splitdo_noaju").focus();
    });


    $("#splitdo_typetpb").change(function(){
        $("#splitdo_NW").focus();
    });
    
</script>