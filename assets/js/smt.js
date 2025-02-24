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