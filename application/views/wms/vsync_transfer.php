<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row" id="sync_trf_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Document</span>
                    <input type="text" class="form-control" id="sync_trf_txt_doc">
                    <button class="btn btn-primary btn-sm" id="sync_trf_btn_search" onclick="sync_trf_btn_search_eCK(this)"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Issue Date</span>
                    <input type="text" class="form-control" id="sync_trf_txt_issuedate" disabled>
                </div>
            </div>
        </div>
        <div class="row" id="sync_trf_stack0">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From Location</span>
                    <input type="text" class="form-control" id="sync_trf_cmb_wh0" disabled>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To Location</span>
                    <input type="text" class="form-control" id="sync_trf_cmb_wh1" disabled>
                </div>
            </div>
        </div>
        <div class="row" id="sync_trf_stack2">
            <div class="col-md-12 mb-3 text-center">
                <button class="btn btn-primary btn-sm" id="sync_trf_btn_sync" onclick="sync_trf_btn_sync_eCK(this)">Synchronize later</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="sync_trf_divku">
                    <table id="sync_trf_tbl" class="table table-sm table-striped table-bordered table-hover">
                        <thead class="table-light">
                            <tr class="first text-center">
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Qty</th>
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
<script>

    $("#sync_trf_divku").css('height', $(window).height()
        -document.getElementById('sync_trf_stack0').offsetHeight
        -document.getElementById('sync_trf_stack1').offsetHeight
        -document.getElementById('sync_trf_stack2').offsetHeight
        -100);
    function sync_trf_btn_search_eCK(p) {
        const docNum = sync_trf_txt_doc.value.trim()
        if(docNum.length<4) {
            sync_trf_txt_doc.focus()
            alertify.message('document is required')
            return
        }
        const data = {
            documentNumber : docNum
        }
        p.disabled = true
        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>x-transfer/document/${btoa(docNum)}`,
            data: data,
            dataType: "JSON",
            success: function (response) {
                p.disabled = false
                let mydes = document.getElementById("sync_trf_divku");
                let myfrag = document.createDocumentFragment();
                let cln = sync_trf_tbl.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("sync_trf_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = '';
                const xmlString =`<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item btnDetail" href="#">Details</a></li>
                        <li><a class="dropdown-item btnSync" href="#">Synchronize</a></li>
                        </ul>
                    </div>`
                response.data.forEach((arrayItem) => {
                    const _doc = new DOMParser().parseFromString(xmlString, "text/html")

                    _doc.body.getElementsByClassName('btnDetail')[0].onclick = () => {
                        alertify.message('sini : ' +arrayItem['DOCNO'])
                    }

                    _doc.body.getElementsByClassName('btnSync')[0].onclick = () => {
                        if(confirm('Are you sure want to sync ' +arrayItem['DOCNO'] + ' ?')) {

                        }
                    }

                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['DOCNO']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['ISUDT']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['TTLROWS']).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.appendChild(_doc.body.firstChild)
                })
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                p.disabled = false
            }
        });
    }
</script>