<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Serial Code</span>
                    <input type="text" class="form-control" id="rcvfgwhrtn_txt_code" readonly maxlength="100" placeholder="code here..." required style="text-align:center">
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgwhrtn_divku">
                    <table id="rcvfgwhrtn_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
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
<script>
    $("#rcvfgwhrtn_divku").css('height', $(window).height()*70/100);       
    function rcvfgwhrtn_f_send(pval){
        let mval = pval;
        if(mval.length >= 10 ){
            let therealcode = '';
            if(mval.indexOf("|")>-1){
                let aval = mval.split("|");
                if (aval.length>5){
                    therealcode = aval[5];
                    therealcode = therealcode.substr(2,therealcode.length);
                } else {
                    alertify.warning("the serial is also not valid");
                }
            } else {
                therealcode = mval.trim();
            }
            $.ajax({
                type: "get",
                url: "<?=base_url('INCFG/setwhrtn')?>",
                data: {incode: therealcode},
                dataType: "JSON",
                success: function (response) {
                    alertify.message(response.status[0].msg);
                    document.getElementById("rcvfgwhrtn_txt_code").value='';
                    rcvfgwhrtn_evt_gettodayscan();
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning("the serial is not valid");
        }                    
    }
    rcvfgwhrtn_evt_gettodayscan();
    function rcvfgwhrtn_evt_gettodayscan(){       
        $.ajax({
            type: "get",
            url: "<?=base_url('INCFG/gettodayscanwhrtn')?>",            
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfgwhrtn_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfgwhrtn_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfgwhrtn_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
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
                }              
                      
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
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
    rcvfgwhrtn_initOnScan();
    function rcvfgwhrtn_initOnScan(){
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
            document.getElementById('rcvfgwhrtn_txt_code').value=tesnya;
            rcvfgwhrtn_f_send(tesnya);
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