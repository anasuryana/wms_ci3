<div style="padding: 10px">
	<div class="container-fluid">        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Serial Code</span>                    
                    <input type="text" class="form-control" id="rcvfgqa_txt_code" readonly maxlength=100 placeholder="code here..." required style="text-align:center">                      
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgqc_divku">
                    <table id="rcvfgqc_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-right">Qty</th>
                                <th>Unit Measure</th>
                                <th>User</th>
                                <th>FG Uniquecode</th>
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
<div class="modal fade" id="RCVFGQC_REMARK">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Remark</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text"><i class="fas fa-comments"></i></label>                        
                        <input type="text" class="form-control" id="rcvfgqc_txt_remark" >
                        <input type="hidden" id="rcvfgqc_txt_reff">
                    </div>
                </div>                
            </div>          
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="rcvfgqc_btnsave"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>            
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="rcvfgqc_modprevent">
    <div class="modal-dialog modal-lg bg-danger">
      <div class="modal-content bg-danger text-white">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Oops</h4>            
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1" id="rcvfgqc_divreff">
                    
                </div>
            </div>           
            <div class="row">                
                <div class="col mb-1" >
                    <blockquote class="blockquote" >
                        <p class="mb-0" id="rcvfgqc_divmodal"></p>
                    </blockquote>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="rcvfgqc_closee" onclick="rcvfgqc_closee_e_clck()">OK</button>
            </div>                   
        </div>             
      </div>
    </div>
</div>
<script>
    var rcvfgqc_ismodalshown = false;    
    function rcvfgqc_closee_e_clck(){
        rcvfgqc_ismodalshown = false; 
        $("#rcvfgqc_modprevent").modal('hide'); 
    }
    function rcvfgqc_showinfoofus(){
        rcvfgqc_ismodalshown = true;
        let mymodal = new bootstrap.Modal(document.getElementById("rcvfgqc_modprevent"), {backdrop: 'static', keyboard: false});
        mymodal.show();
    }
    $("#rcvfgqc_divku").css('height', $(window).height()*70/100); 
    $("#RCVFGQC_REMARK").on('shown.bs.modal', function(){
        $("#rcvfgqc_txt_remark").focus();
        document.getElementById('rcvfgqc_txt_remark').select();
    });
    $("#rcvfgqc_btnsave").click(function (e) { 
        let reffno = document.getElementById('rcvfgqc_txt_reff').value;
        let rmrk = document.getElementById('rcvfgqc_txt_remark').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setremark')?>",
            data: {inid: reffno, inrmrk: rmrk},
            dataType: "json",
            success: function (response) {
                rcvfgqc_evt_gettodayscan();
                $("#RCVFGQC_REMARK").modal('hide');
                document.getElementById('rcvfgqc_txt_remark').value='';
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.warning(response.status[0].msg);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });        
    });   
    function rcvfgqa_f_send(pval){        
        let mval = pval;
        if(mval.length >= 10 ){
            let therealcode = '';
            if(mval.indexOf("|")>-1){
                const aval = mval.split("|");
                if (aval.length>5){
                    therealcode = aval[5];
                    therealcode = therealcode.substr(2,therealcode.length);
                } else {
                    alertify.warning("the serial is also not valid");
                }                    
            } else {
                therealcode = mval.trim();
            }
            if(rcvfgqc_ismodalshown){
                return;
            }            
            $.ajax({
                type: "get",
                url: "<?=base_url('INCFG/setqa')?>",
                data: {incode: therealcode},
                dataType: "json",
                success: function (response) {
                    if(response.status === undefined){
                        document.getElementById('rcvfgqc_divreff').innerHTML = response[0].msg
                        rcvfgqc_showinfoofus()
                    } else {
                        if(response.status[0].cd==1){
                            alertify.message(response.status[0].msg);
                        } else {
                            document.getElementById('rcvfgqc_divreff').innerHTML = response.status[0].msg
                            document.getElementById('rcvfgqc_divmodal').innerHTML = therealcode
                            rcvfgqc_showinfoofus()
                        }
                        document.getElementById("rcvfgqa_txt_code").value='';
                        rcvfgqc_evt_gettodayscan();
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning("the serial is not valid");
        }
    }
    rcvfgqc_evt_gettodayscan();
    function rcvfgqc_evt_gettodayscan(){
        $.ajax({
            type: "get",
            url: "<?=base_url('INCFG/gettodayscanqa')?>",            
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfgqc_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfgqc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfgqc_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let msts='';
                for (let i = 0; i<ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode((i+1));            
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].ITH_DOC);
                    newcell.appendChild(newText);        
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].ITH_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: center';
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';
                    
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_STKUOM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].MSTEMP_FNM);
                    newcell.appendChild(newText);                                        
                    newcell = newrow.insertCell(7);
                    newcell.style = 'cursor:pointer';
                    newText = document.createTextNode(response.data[i].ITH_SER);
                    newcell.appendChild(newText);                                        
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].SER_RMRK);
                    newcell.appendChild(newText);                                        
                }
                let mrows = tableku2.getElementsByTagName("tr");
                for(let x=0;x<mrows.length;x++){
                    tableku2.rows[x].cells[7].onclick = function(){rcvfgqc_e_remark(tableku2.rows[x],x,7)};
                }    
                      
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function rcvfgqc_e_remark(prow, pirow ,pikol){
        let tcell = prow.getElementsByTagName("td")[pikol];
        let tcellremark = prow.getElementsByTagName("td")[pikol+1];
        let curval  = tcell.innerText;
        document.getElementById('rcvfgqc_txt_remark').value=tcellremark.innerText;
        document.getElementById('rcvfgqc_txt_reff').value=curval;
        $("#RCVFGQC_REMARK").modal('show');
    }

    function scanHandler(e){
		//console.log("[scanHandler]: Code: " + e.detail.code);
	}
	
    function scanErrorHandler(e){
		var sFormatedErrorString = "Error Details: {\n";
		for (var i in e.detail){
			sFormatedErrorString += '    ' + i + ': ' + e.detail[i] + ",\n";
		}
		sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
		//console.log("[scanErrorHandler]: " + sFormatedErrorString);
	}
    rcvfgqc_initOnScan();
    function rcvfgqc_initOnScan(){
		let options = {
			timeBeforeScanTest: 100, 
			avgTimeByChar: 30,
			minLength: 6, 			
			scanButtonLongPressTime: 500, 
			stopPropagation: false, 
			preventDefault: false,
			reactToPaste: true,
			reactToKeyDown: true,
			singleScanQty: 1
		}
		
		options.onScan = function(barcode, qty){
            let tesnya = barcode;
            let ates = tesnya.split(String.fromCharCode(16));
            let  e = $.Event('keypress');
            e.which = 13;
            tesnya = '';
            for(let i = 0;i<ates.length; i++){
                tesnya += ates[i];
            }
			tesnya = tesnya.replace(/Ü/g, "|");
            //let mactiveEl = document.activeElement.id;              
            //let mactiveTag = document.activeElement.tagName;                       
            document.getElementById('rcvfgqa_txt_code').value=tesnya;
            rcvfgqa_f_send(tesnya);
            //console.log("[onScan]: Code: " + barcode + " Quantity: " + qty);
		};		
        options.onScanError = function(err){
				var sFormatedErrorString = "Error Details: {\n";
				for (var i in err){
					sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
				}
				sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
				// console.log("[onScanError]: " + sFormatedErrorString);
			}; 	
		
        options.onKeyProcess = function(iKey, oEvent){
            // console.log("[onKeyProcess]: Processed key code: " + iKey);
        };				
		options.onKeyDetect = function(iKey, oEvent){
            // console.log("[onKeyDetect]: Detected key code: " + iKey);
        };				
        options.onScanButtonLongPress = function(){
            // console.log("[onScanButtonLongPress]: ScanButton has been long-pressed");
        };
		
        options.onPaste = function(sPasteString){
            // console.log("[onPaste]: Data has been pasted: " + sPasteString);
        }
		
        document.addEventListener('scan', scanHandler);				
        document.addEventListener('scanError', scanErrorHandler);									
		
		
		try {
			onScan.attachTo(document, options);
			// console.log("onScan Started!");
		} catch(e) {
			onScan.setOptions(document, options);
			// console.log("onScansettings changed!");
		}		
	}
</script>