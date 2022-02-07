<div style="padding: 10px">
	<div class="container-xxl">      
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-2">                    
                    <label class="input-group-text">Period</label>                    
                    <select id="rcvprint_month" class="form-select">
                        <?php
                            $toprint ='';
                            $toprint .= '<option value="1">January</option>';
                            $toprint .= '<option value="2">February</option>';
                            $toprint .= '<option value="3">March</option>';
                            $toprint .= '<option value="4">April</option>';
                            $toprint .= '<option value="5">May</option>';
                            $toprint .= '<option value="6">June</option>';
                            $toprint .= '<option value="7">July</option>';
                            $toprint .= '<option value="8">August</option>';
                            $toprint .= '<option value="9">September</option>';
                            $toprint .= '<option value="10">October</option>';
                            $toprint .= '<option value="11">November</option>';
                            $toprint .= '<option value="12">December</option>';
                            echo  $toprint;
                        ?>
                    </select>
                    <input class="form-control" id="rcvprint_year" type="number" placeholder="Year" maxlength="4" value="<?=date('Y')?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">                
                <div class="form-check form-check-inline">                    
                    <input type="radio" id="rcvprint_rad_open" onclick="initRCVList()" class="form-check-input" name="rcvprint_optradio" value="open" checked>
                    <label class="form-check-label" for="rcvprint_rad_open">
                    Open
                    </label>
                </div>
                <div class="form-check form-check-inline">                    
                    <input type="radio" id="rcvprint_rad_all" onclick="initRCVList()"  class="form-check-input" name="rcvprint_optradio" value="all">
                    <label class="form-check-label" for="rcvprint_rad_all">
                    All
                    </label>
                </div>                
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-1">                
                <table id="rcvprint_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><input class="form-check-input" type="checkbox" id="rcvprint_ckall" title="Select all"></th>                                
                            <th>PO No</th>
                            <th>DO No</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Currency</th>
                            <th class="text-center"><button id="rcvprint_btnall" class="btn btn-sm btn-primary" title="Print selected rows"><i class="fas fa-print"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>                     
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
</div>
<script>
    var tableRCV ; // = $('#rcvprint_tbl').DataTable({fixedHeader: true});
    var rcvcustoms_selcol   ='0';
    var rcvcustoms_seldo    =[];
    
    $("#rcvprint_btnall").click(function(){
        var serprint = [];
        $.each(tableRCV.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myser = $tds.eq(2).text();
            if(rana){
                serprint.push(myser);
            }
        });
        if(serprint.length>0){
            Cookies.set('PRINTLABEL_DO', serprint, {expires:365});            
            window.open("<?=base_url('printlabel_do')?>",'_blank');
        }
    });
    $("#rcvcustoms_dd").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rcvprint_month").change(function(){
        initRCVList();
    });
    $("#rcvprint_ckall").click(function(){
        var ischk = $(this).is(":checked");
        let headflg = '';
        $.each(tableRCV.rows().nodes(), function(key, value) {
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').prop('checked', ischk);
        });
    });
    $("#rcvprint_month").val(<?=date('m')?>);
    initRCVList();
    function initRCVList(){
        var myear = $("#rcvprint_year").val();
        var mmonth = $("#rcvprint_month").val();
        let temprend, temprend2,temprend3,temprend4;
        let searchtype = document.getElementById('rcvprint_rad_open').checked ? 'open' : 'all';
        tableRCV =  $('#rcvprint_tbl').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("RCV/getsaveddo_list_jdt")?>',
                type: 'get',
                data: {inyear: myear, inmonth: mmonth, insts: searchtype}
            },
            columns:[
                { "data": 'RCV_DONO',
                    render : function(data, type, row){
                        return '<input type="checkbox" class="form-check-input">';
                    }
                },
                { "data": 'RCV_PO'},
                { "data": 'RCV_DONO',
                    render : function(data,type,row){                        
                        if(type==='display'){
                            let toret = '';
                            if(temprend!=row.RCV_DONO){
                                temprend=row.RCV_DONO;
                                toret = temprend;
                            } else {
                                toret = '';
                            }
                            return toret;
                        } else {
                            return row.RCV_DONO;
                        }
                        
                    }
                },
                { "data": 'RCV_RCVDATE',
                    render : function(data,type,row){
                        if(type==='display'){
                            let toret = '';                       
                            if(temprend2!=row.RCV_DONO){
                                temprend2=row.RCV_DONO;
                                toret = row.RCV_RCVDATE;
                            } else {
                                toret = '';
                            }                        
                            return toret;
                        } else {
                            return row.RCV_RCVDATE;
                        }
                        
                    }
                },
                { "data": 'MSUP_SUPNM',
                    render : function(data,type,row){
                        if(type==='display'){
                            let toret = '';                       
                            if(temprend3!=row.RCV_DONO){
                                temprend3=row.RCV_DONO;
                                toret = row.MSUP_SUPNM;
                            } else {
                                toret = '';
                            }                        
                            return toret;
                        } else {
                            return row.MSUP_SUPNM;
                        }
                    }
                },
                { "data": 'MSUP_SUPCR', 
                    render:  function(data,type,row){
                        if(type==='display'){
                            let toret = '';                       
                            if(temprend4!=row.RCV_DONO){
                                temprend4=row.RCV_DONO;
                                toret = row.MSUP_SUPCR;
                            } else {
                                toret = '';
                            }                        
                            return toret;
                        } else {
                            return row.MSUP_SUPCR;
                        }
                    }
                },
                { "data": 'MSUP_SUPCR',
                    render : function(data, type, row){
                        //console.log(row);
                        return '<i class="fas fa-print" title="Print"></i>';
                    } 
                }                         
            ],
            columnDefs: [
                {
                    targets: 0,
                    className : 'text-center'
                },
                {
                    targets: 6,
                    className : 'text-center'
                }
            ]        
        });        
            
    }

    $('#rcvprint_tbl tbody').on( 'click', 'tr', function () { 
		if ($(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#rcvprint_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        
        var mdo =  $(this).closest("tr").find('td:eq(2)').text();               
        if (String(rcvcustoms_selcol)=='6'){
            var ado = [];ado.push(mdo);
            Cookies.set('PRINTLABEL_DO', ado, {expires:365});            
            window.open("<?=base_url('printlabel_do')?>",'_blank');
        }
    });

    $('#rcvprint_tbl tbody').on( 'click', 'td', function () {            
        rcvcustoms_selcol = $(this).index();         
    });

    $("#rcvprint_year").mouseup(function () { 
        initRCVList();
    });
</script>