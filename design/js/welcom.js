function $(el){
    return document.querySelector(el);
}
function $$(els){
    return document.querySelectorAll(els);
}
// ++++++++++ animation +++++++++++++++

var cr = $('.right');
var cf = $('.left');
var s = $('.ef');
var m = $('.move');

cr.onclick = function(){
    var st = s.style;
    st.width = '40px';
    st.left= '40%';
    m.style.transform ='translateX(-49.5%)';
    setTimeout(function(){
        st.width = '40%';
        st.left= '59%'; 
    }, 300);
};
cf.onclick = function(){
    var st = s.style;
    st.width = '40px';
    st.left= '40%';
    m.style.transform ='translateX(0)';
    setTimeout(function(){
        st.width = '40%';
        st.left= '0%'; 
    }, 300);
};
// +++++++++++++++check inputs +++++++++++++++++++

var username = $$('input[name="user"],input[name="pass"]'),
    ps = $$('input[name="pass"]'),
    rps = $('input[name="spass"]'),
    sub = $$('input[type="submit"]'),
    spin = $('.fa-spin'),
    alrt = $('span');
function seterr(el,msg){
    el.setAttribute('title',msg);
    el.style.borderColor = '#F00';
}
function reerr(el){
    el.style.borderColor = 'rgba(255, 255, 255, 0.336)';
    el.setAttribute('title', '');
    
}

function check(){
    if (rps.value != ps[1].value){
        seterr(rps,'must be like password');
    }else {
        reerr(rps);
    }
}
function iserr(el){
    if (getComputedStyle(el).borderColor=='rgb(255, 0, 0)'|| el.value.length == ''){
        return true ;
    }else {
        return false;
    }
}
sub.forEach(function(btn){
    btn.onclick = function(e){
        if (this.id == 'l'){
            var er = [];
            for (var i = 0; i <2; i ++){
                if (iserr(username[i])){
                    er.push(0);
                }
            }
            if (er.length > 0){
                e.preventDefault();
                seterr(btn,"you can't send data with problems or with nodata you must fix that");
            }
        }else {
            var erl = [];
            for (var x = 2; x <4; x ++){
                if(iserr(username[x])){
                    erl.push(0);
                }
            }
            if (iserr(rps)){
                erl.push(0);
            }
            if (erl.length > 0){
                e.preventDefault();
                seterr(btn,"you can't send data with problems or with nodata you must fix that");
            }
        }
    };
});


username.forEach(function(user){
    user.oninput = function(){
        if (this.value==''||this.value.length < 6 || this.value.length >20){
            seterr(this,  'must be between 6 chars and 20chars');
            alrt.style.display = 'none';
        }else {
            reerr(this);
            if (this.id == 'up'){ 
                var elm = this;           
                // +++++++++++check if is logined+++++++++
                spin.style.display= 'block';
                XHR({
                    'to':'includes/islogined.php',
                    'data':'name='+elm.value,
                    'se': function(data){
                            if (data == 'yes'){
                                alrt.style.display = 'inline';
                                spin.style.display= 'none';
                                seterr(elm);
                            }else {            
                                spin.style.display= 'none';   
                                alrt.style.display = 'none';
                                reerr(elm);
                        }
                    }
                });
            }else {
                spin.style.display= 'none';
            }  
        }
        check();
    };
});
rps.oninput = function(){
    check();
};
function XHR(ar={'mt':'GET','to':'','data':'','se':function(){}}){
    var r = new XMLHttpRequest();
    r.open(ar.mt,ar.to+'?'+ar.data);
    r.send();
    r.onreadystatechange = function(){
        if(r.readyState ==4&&r.status == 200){
            ar.se(r.responseText);
        }
    };
}


