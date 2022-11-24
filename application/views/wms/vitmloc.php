<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Item Code</span>
                    <input type="text" class="form-control" id="itmloc_txt_itemcd" required readonly>
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="itmloc_btnfinditem"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Descr. 1</span>
                    <input type="text" class="form-control" id="itmloc_txt_itmdsc1" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Descr. 2</span>
                    <input type="text" class="form-control" id="itmloc_txt_itmdsc2" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">SPT No</span>
                    <input type="text" class="form-control" id="itmloc_txt_spt" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" title="Business Group">Business</span>
                    <select class="form-select" id="itmloc_sel_business">
                        <?= $lbg ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Location</span>
                    <select class="form-select" id="itmloc_sel_loc">
                        <option value="-">Choose</option>
                        <?php foreach ($lloc as $r) : ?>
                            <option value="<?= $r['MSTLOCG_ID'] ?>"><?= $r['MSTLOCG_NM'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Sub Location</span>
                    <select class="form-select form-select-sm" id="itmloc_sel_subloc">
                        <option value="-">-</option>
                    </select>
                    <button title="Find Item" class="btn btn-outline-secondary" type="button" id="itmloc_btnsubloc" data-bs-toggle="modal" data-bs-target="#ITMLOC_MODSEARCHSUB"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="btn-group btn-group-sm">
                    <button title="Save" class="btn btn-primary" id="itmloc_btn_add">Add</button>
                    <button title="Import Template data to System" class="btn btn-outline-success" id="itmloc_btn_import"><i class="fas fa-file-import"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">
                <div class="table-responsive">
                    <table id="itmloc_tbl" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>Business</th>
                                <th class="d-none">idloc</th>
                                <th>Location</th>
                                <th>Sub Location</th>
                                <th></th>
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
<div class="modal fade" id="ITMLOC_MODITM">
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
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="itmloc_srchby" class="form-select">
                                <option value="ic">Item Code</option>
                                <option value="in">Item Name</option>
                                <option value="spt">SPT No</option>
                            </select>
                            <input type="text" class="form-control" id="itmloc_txtsearch" maxlength="44" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end">
                        <span class="badge bg-info" id="lblinfo_tblitmloc"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="itmloc_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
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
<div class="modal fade" id="ITMLOC_MODSEARCHSUB">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sub Location List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Sub location</span>
                            <input type="text" class="form-control" id="itmloc_txtsearchsubloc" maxlength="44" required placeholder="..." onkeypress="itmloc_txtsearchsubloc_eKeyPress(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="itmloc_tblsubloc_div">
                            <table id="itmloc_tblsubloc" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th>Business</th>
                                        <th>Item Code</th>
                                        <th>SPT No.</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Sub Location</th>
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
<div class="modal fade" id="ITMLOC_IMPORTDATA">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Import data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group">
                            <button title="Download a Template File (*.xls File)" id="itmloc_btn_download" onclick="itmloc_btn_download(this)" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>
                            <input type="file" id="itmloc_xlf_new" class="form-control">
                            <button id="itmloc_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="itmloc_tbl_import" class="table table-hover table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Warehouse</th>
                                        <th>Location ID</th>
                                        <th>Item Code</th>
                                        <th>Info</th>
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
    var itmloc_colidx = 0;
    $("#itmloc_btnfinditem").click(function() {
        $("#ITMLOC_MODITM").modal('show');
    });
    $("#ITMLOC_MODSEARCHSUB").on('shown.bs.modal', function() {
        itmloc_txtsearchsubloc.focus()
    });
    $("#ITMLOC_MODITM").on('shown.bs.modal', function() {
        $("#itmloc_txtsearch").focus();
    });
    $("#itmloc_txtsearch").keypress(function(e) {
        if (e.which == 13) {
            const mkey = $(this).val();
            const msearchby = $("#itmloc_srchby").val();
            $("#lblinfo_tblitmloc").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?= base_url('MSTITM/search_itemlocation') ?>",
                data: {
                    cid: mkey,
                    csrchby: msearchby
                },
                dataType: "json",
                success: function(response) {
                    const ttlrows = response.length;
                    let tohtml = '';
                    let mtype = '';
                    for (var i = 0; i < ttlrows; i++) {
                        if (response[i].MITM_MODEL.trim() == '1') {
                            mtype = 'FG';
                        } else {
                            mtype = 'RM';
                        }
                        tohtml += '<tr style="cursor:pointer">' +
                            '<td style="white-space:nowrap">' + response[i].MITM_ITMCD.trim() + '</td>' +
                            '<td style="white-space:nowrap">' + response[i].MITM_ITMD1 + '</td>' +
                            '<td>' + response[i].MITM_ITMD2 + '</td>' +
                            '<td style="white-space:nowrap">' + response[i].MITM_SPTNO + '</td>' +
                            '<td>' + mtype + '</td>' +
                            '</tr>';
                    }
                    $("#lblinfo_tblitmloc").text("");
                    $('#itmloc_tblitm tbody').html(tohtml);
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#itmloc_tblitm tbody').on('click', 'tr', function() {
        if ($(this).hasClass('table-active')) {
            $(this).removeClass('table-active');
        } else {
            $('#itmloc_tblitm tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        const mitem = $(this).closest("tr").find('td:eq(0)').text();
        const mitemnm = $(this).closest("tr").find('td:eq(1)').text();
        const mitemnm2 = $(this).closest("tr").find('td:eq(2)').text();
        const mspt = $(this).closest("tr").find('td:eq(3)').text();


        $("#itmloc_txt_itemcd").val(mitem);
        $("#itmloc_txt_itmdsc1").val(mitemnm);
        $("#itmloc_txt_itmdsc2").val(mitemnm2);
        $("#itmloc_txt_spt").val(mspt);
        $("#ITMLOC_MODITM").modal('hide');
        itmloc_findloc();
    });

    $('#itmloc_tbl tbody').on('click', 'td', function() {
        itmloc_colidx = $(this).index();
    });
    $('#itmloc_tbl tbody').on('click', 'tr', function() {
        const midlocg = $(this).closest("tr").find('td:eq(1)').text();
        const midloc = $(this).closest("tr").find('td:eq(3)').text();
        const mitm = $("#itmloc_txt_itemcd").val();
        if (itmloc_colidx == 4) {
            alertify.confirm('Need Confirmation', 'Are you sure ?', function() {
                $.ajax({
                    type: "get",
                    url: "<?= base_url('ITMLOC/remove') ?>",
                    data: {
                        initem: mitm,
                        inlocg: midlocg,
                        inloc: midloc
                    },
                    dataType: "text",
                    success: function(response) {
                        alertify.message(response);
                        itmloc_findloc();
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow);
                    }
                });
            }, function() {

            });
        }
    });

    function itmloc_findloc() {
        const mitem = $("#itmloc_txt_itemcd").val();
        $.ajax({
            type: "get",
            url: "<?= base_url('ITMLOC/getbyitemcd') ?>",
            data: {
                incd: mitem
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.length;
                let tohtml = '';
                for (var i = 0; i < ttlrows; i++) {
                    tohtml += `<tr>
                <td>${response[i].ITMLOC_BG}</td>
                <td class="d-none">${response[i].MSTLOC_GRP}</td>
                <td>${response[i].MSTLOCG_NM}</td>
                <td>${response[i].MSTLOC_CD}</td>
                <td><i title="Remove" class="fas fa-trash text-warning"></i></td>
                </tr>`;
                }
                $("#itmloc_tbl tbody").html(tohtml);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#itmloc_btn_add").click(function(e) {
        e.preventDefault();
        const mitm = $("#itmloc_txt_itemcd").val();
        if (mitm == '') {
            $("#itmloc_txt_itemcd").focus();
            return;
        }
        const business = document.getElementById('itmloc_sel_business').value;
        const mitmlocg = $("#itmloc_sel_loc").val();
        const mitmloc = $("#itmloc_sel_subloc").val();
        if (mitmloc == '-') {
            $("#itmloc_sel_subloc").focus();
            return;
        }
        $.ajax({
            type: "post",
            url: "<?= base_url('ITMLOC/set') ?>",
            data: {
                initem: mitm,
                inlocg: mitmlocg,
                inloc: mitmloc,
                inbg: business
            },
            dataType: "text",
            success: function(response) {
                alertify.message(response);
                itmloc_findloc();
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    });
    $("#itmloc_sel_loc").change(function() {
        const mval = $(this).val();
        $.ajax({
            type: "get",
            url: "<?= base_url('MSTLOC/getbygroup') ?>",
            data: {
                ingrp: mval
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length;
                let tohtml = '';
                if (ttlrows > 0) {
                    for (var i = 0; i < ttlrows; i++) {
                        tohtml += `<option value="${response.data[i].MSTLOC_CD}">${response.data[i].MSTLOC_CD}</option>`;
                    }
                } else {
                    tohtml = '<option value="-">-</option>';
                }
                $("#itmloc_sel_subloc").html(tohtml);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    });

    function itmloc_btn_download(p) {
        p.disabled = true
        p.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
        $.ajax({
            type: "POST",
            url: "<?= base_url('ITMLOC/template') ?>",
            success: function(response) {
                const blob = new Blob([response], {
                    type: "application/vnd.ms-excel"
                })
                const fileName = `tmpl_itemloc.xls`
                saveAs(blob, fileName)
                p.innerHTML = '<i class="fas fa-file-excel"></i>'
                p.disabled = false
                alertify.success('Done')
            },
            xhr: function() {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.innerHTML = '<i class="fas fa-file-excel"></i>'
                            p.disabled = false
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }

    $("#itmloc_btn_import").click(function(e) {
        e.preventDefault();
        $("#ITMLOC_IMPORTDATA").modal('show');
    });
    $("#itmloc_btn_startimport").click(function(e) {
        e.preventDefault();
        if (document.getElementById('itmloc_xlf_new').files.length == 0) {
            alert('please select file to upload');
        } else {
            let fileUpload = $("#itmloc_xlf_new")[0];
            //Validate whether File is valid Excel file.
            const regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {
                if (typeof(FileReader) != "undefined") {
                    const reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        console.log('saya perambaan selain IE');
                        reader.onload = function(e) {
                            itmloc_ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString(fileUpload.files[0]);
                    } else {
                        //For IE Browser.
                        reader.onload = function(e) {
                            let data = "";
                            let bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            itmloc_ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid Excel file.");
            }
        }
    });

    function itmloc_ProcessExcel(data) {
        //Read the Excel File data.
        const workbook = XLSX.read(data, {
            type: 'binary'
        });

        //Fetch the name of First Sheet.
        const firstSheet = workbook.SheetNames[0];
        //Read all rows from First Sheet into an JSON array.
        const excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);


        const dvExcel = $("#itmloc_tbl_import tbody");
        let tohtml = '';
        //Add the data rows from Excel file.
        for (let i = 0; i < excelRows.length; i++) {
            tohtml += '<tr style="cursor:pointer">' +
                '<td>' + excelRows[i].LOCATIONGROUP + '</td>' +
                '<td>' + excelRows[i].LOCATIONID + '</td>' +
                '<td>' + excelRows[i].ITEM + '</td>' +
                '<td>Please wait...</td>' +
                '</tr>';
        }
        dvExcel.html(tohtml);
        for (let i = 0; i < excelRows.length; i++) {
            const mitem = excelRows[i].ITEM;
            const mloc = excelRows[i].LOCATIONID;
            const mlocg = excelRows[i].LOCATIONGROUP;
            const mbusiness = excelRows[i].BUSINESSGROUP;
            $.ajax({
                type: "post",
                url: "<?= base_url('ITMLOC/import') ?>",
                data: {
                    initem: mitem,
                    inlocg: mlocg,
                    inloc: mloc,
                    inBG: mbusiness,
                    inrowid: i
                },
                dataType: "json",
                success: function(response) {
                    const table = $("#itmloc_tbl_import tbody");
                    table.find('tr').eq(response[0].indx).find('td').eq(3).text(response[0].status);
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    }

    function itmloc_txtsearchsubloc_eKeyPress(e) {
        if (e.key === 'Enter') {
            let mtabel = document.getElementById("itmloc_tblsubloc");
            mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="6" class="text-center">Please wait...</td></tr>`
            $.ajax({
                url: "<?= base_url('ITMLOC/search') ?>",
                data: {
                    search: e.target.value
                },
                dataType: "JSON",
                success: function(response) {
                    const ttlrows = response.length
                    let mydes = document.getElementById("itmloc_tblsubloc_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("itmloc_tblsubloc");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['ITMLOC_BG']
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['ITMLOC_ITM']
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = arrayItem['SPTNO']
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = arrayItem['ITMD1']
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = arrayItem['MSTLOC_GRP']
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = arrayItem['MSTLOC_CD']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    }
</script>