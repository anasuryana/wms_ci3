// vkeikaku
let vkeikakuOperationMode = 0
let vkeikakuActiveTab = ''
let keikakuColumnIndexStart = 6

function smtPWValidator(pvalue, data = { PWPOL_LENGTH: 0, PWPOL_ISCOMPLEX: 0 }) {
    const minimumLengthPassword = data.PWPOL_LENGTH
    let numberList = [...Array(10).keys()]
    let specialCharList = ['~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', ':', '"', '<', '>', '?', '{', '}', '|']
    if (pvalue.trim().length < minimumLengthPassword) {
        return { cd: '0', msg: `At least ${minimumLengthPassword} characters` }
    }

    let isFound = false
    for (let i = 0; i < numberList.length; i++) {
        if (pvalue.includes(numberList[i])) {
            isFound = true
            break
        }
    }
    if (!isFound) {
        return { cd: '0', msg: 'At least 1 numerical character' }
    }

    isFound = false
    for (let i = 0; i < specialCharList.length; i++) {
        if (pvalue.includes(specialCharList[i])) {
            isFound = true
            break
        }
    }
    if (!isFound) {
        return { cd: '0', msg: 'At least 1 special character' }
    }
    return { cd: '1', msg: 'OK' }
}

const onlyInLeft = (left, right, compareFunction) =>
    left.filter(leftValue =>
        !right.some(rightValue =>
            compareFunction(leftValue, rightValue)
        )
    );
let resizeObserverO = new ResizeObserver(() => { })

function smtGetOrdinal(n) {
    let ord = 'th';

    if (n % 10 == 1 && n % 100 != 11) {
        ord = 'st';
    }
    else if (n % 10 == 2 && n % 100 != 12) {
        ord = 'nd';
    }
    else if (n % 10 == 3 && n % 100 != 13) {
        ord = 'rd';
    }

    return ord;
}

function txfg_selectElementContents(el) {
    var body = document.body,
        range, sel;
    if (document.createRange && window.getSelection) {
        range = document.createRange();
        sel = window.getSelection();
        sel.removeAllRanges();
        try {
            range.selectNodeContents(el);
            sel.addRange(range);
        } catch (e) {
            range.selectNode(el);
            sel.addRange(range);
        }
    } else if (body.createTextRange) {
        range = body.createTextRange();
        range.moveToElementText(el);
        range.select();
    }
}


// related keikaku
function doubleControlEvent() {
    if (event.key === 'Control') {
        timesCtrlClicked++
        if (timesCtrlClicked >= 2) {
            if (typeof keikaku_main_tab !== 'undefined') {
                if (vkeikakuOperationMode) {
                    if (vkeikakuActiveTab == '#keikaku_tabRM') {
                        vkeikakuActiveTab = '#keikaku_tab_prodplan'
                        vkeikakuSimulate(keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1), keikaku_calculation_sso.getData())
                    } else {
                        vkeikakuActiveTab = '#keikaku_tabRM'
                    }
                    let firstTabEl = document.querySelector(`#keikaku_main_tab button[data-bs-target="${vkeikakuActiveTab}"]`)
                    let thetab = new bootstrap.Tab(firstTabEl)
                    thetab.show()
                }
            }
        }
        setTimeout(() => (timesCtrlClicked = 0), 350)
    }
}

let timesCtrlClicked = 0;
document.addEventListener('keyup', doubleControlEvent, true)

function keikaku_btn_mode(pThis) {
    if (vkeikakuOperationMode === 1) {
        pThis.innerText = 'User Mode'
        vkeikakuOperationMode = 0
        keikaku_info_tab.innerHTML = ``
    } else {
        pThis.innerText = 'Planner Mode'
        vkeikakuOperationMode = 1

        keikaku_info_tab.innerHTML = `
                <i class="fas fa-chalkboard-user fa-fade"></i> Planner Mode                    
                `
    }
}

function vkeikakuSimulate(pData, pCalculation) {
    console.log({ data: pData, calculation: pCalculation })
    let asProdplan1 = [null, null, null, null, null, null];
    let asProdplan2 = [null, null, null, null, null, null];
    let asProdplan3 = [null, null, null, null, null, null];
    let dataCalculation = [null, null, null, null, null, null];
    let ttlCalculationColumn = pCalculation[9].length
    for (let i = 1; i < ttlCalculationColumn; i++) {
        let _jam = String('0' + String(pCalculation[9][i] - 1)).slice(-2)
        asProdplan1.push(_jam)
        asProdplan2.push(pCalculation[8][i] * pCalculation[10][i])
        asProdplan3.push(pCalculation[8][i])
        dataCalculation.push(pCalculation[7][i])
    }
    let asMatrix = []

    asMatrix.push(asProdplan1)
    asMatrix.push(asProdplan2)
    asMatrix.push(asProdplan3)

    let tempModel = ''
    let tempType = '';
    let tempSpecs = '';
    let tempAssyCode = '';
    pData.forEach((AI, index) => {
        let _shouldChangeModel = false;
        let _usedTime = 0;
        if (tempModel.length > 0) {
            if (AI[5].substring(0, 4) == tempType.substring(0, 4) && tempSpecs == AI[9]) {
                if (tempAssyCode == AI[7]) {
                    _shouldChangeModel = false;
                } else {
                    _shouldChangeModel = true;
                    _usedTime = 0.25;
                }
            } else {
                _usedTime = 0.25;
                _shouldChangeModel = true;
            }

            if (tempSpecs != AI[9]) {
                tempSpecs = AI[9];
            }

            if (AI[5].substring(0, 4) != tempType.substring(0, 4)) {
                tempType = AI[5];
            }

            if (tempAssyCode != AI[7]) {
                tempAssyCode = AI[7];
            }
        } else {
            if (tempModel != AI[1]) {
                _shouldChangeModel = false; // first row always set to false
                tempModel = AI[1];
                tempType = AI[5];
                tempSpecs = AI[9];
                tempAssyCode = AI[7];
            }
        }
        let _producton_worktime = AI[10] / 3600 * numeral(AI[4]).value()
        let _ct_hour = AI[10] / 3600
        let _asMatrix1 = [null, _shouldChangeModel, (_shouldChangeModel ? _usedTime : 0), null, null, null];
        let _asMatrix2 = [
            AI[7],
            null,
            _producton_worktime,
            AI[2] + '-' + AI[7],
            _ct_hour,
            AI[9] + "#" + AI[1] + "#" + AI[2] + "#" + numeral(AI[3]).value() + "#" + numeral(AI[4]).value() + "#" + AI[5] + "#" + AI[6] + "#" + index + "#" + keikaku_line_input.value
        ];
        for (let i = 1; i < ttlCalculationColumn; i++) {
            _asMatrix1.push(null)
            _asMatrix2.push(null)
        }

        asMatrix.push(_asMatrix1)
        asMatrix.push(_asMatrix2)
    })

    // bismillah proses kalkulasi waktu
    let matrixRowsLength = asMatrix.length;
    for (let i = 3; i < matrixRowsLength; i++) {
        for (let col = keikakuColumnIndexStart; col < (6 + 36); col++) {
            let _totalProductionHours = asMatrix[i][2];
            if (_totalProductionHours == 0) {
                asMatrix[i][col] = 0;
            } else {
                asMatrix[i][col] = _plotTime(asMatrix, col, i, _totalProductionHours.toFixed(5));
            }
        }
    }

    for (let i = 3; i < matrixRowsLength; i++) {
        for (let col = keikakuColumnIndexStart; col < (6 + 36); col++) {
            if (!asMatrix[i][0]) { // change model
                if (col === keikakuColumnIndexStart) {
                    if (asMatrix[i][1]) {
                        if (asMatrix[i][col] == 0) {
                            asMatrix[i][col] = 0;
                        } else {
                            asMatrix[i][col] = Math.round(asMatrix[i][col] / asMatrix[i][2]);
                        }
                    } else {
                        if (asMatrix[i][col] == 0) {
                            asMatrix[i][col] = 0;
                        } else {
                            asMatrix[i][col] = asMatrix[i][col] / asMatrix[i][4];
                        }
                    }
                }
            } else {
                if (asMatrix[i][4] == 0) {
                    asMatrix[i][col] = 0;
                } else {
                    if (col === keikakuColumnIndexStart) {
                        asMatrix[i][col] = Math.round(asMatrix[i][col] / asMatrix[i][4]);
                    } else {
                        if (asMatrix[i][col] == 0) {
                            asMatrix[i][col] = 0;
                        } else {
                            asMatrix[i][col] = Math.round(asMatrix[i][col] / asMatrix[i][4]);
                        }
                    }
                }
            }
        }
    }

    keikakuDisplayProdplan(asMatrix, [], dataCalculation, [])
}


function _plotTime(data, parX, parY, parProductionHours) {
    let _plotedTime = 0;
    let restEffectiveWorkTime = data[1][parX] - _sumVertical(data, parX, parY);

    if (parX === keikakuColumnIndexStart) {
        if (parProductionHours < restEffectiveWorkTime) {
            _plotedTime = parProductionHours;
        } else {
            _plotedTime = restEffectiveWorkTime;
        }
    } else {
        if (parProductionHours < restEffectiveWorkTime) {
            _plotedTime = parProductionHours - _sumHorizontal(data, parX, parY);
        } else if ((parProductionHours - _sumHorizontal(data, parX, parY)) < restEffectiveWorkTime) {
            _plotedTime = parProductionHours - _sumHorizontal(data, parX, parY);
        } else {
            _plotedTime = restEffectiveWorkTime;
        }
    }

    return _plotedTime;
}

function _sumVertical(data, parX, parY) {
    let _summarizedVertical = 0;
    for (let __r = parY; __r > 2; __r--) {
        _summarizedVertical += numeral(data[__r][parX]).value();
    }
    return _summarizedVertical.toFixed(5)
}

function _sumHorizontal(data, parX, parY) {
    let _summarizedHorizontal = 0;
    for (let __c = parX; __c >= keikakuColumnIndexStart; __c--) {
        _summarizedHorizontal += data[parY][__c];
    }
    return _summarizedHorizontal;
}
