
/*
    created by moad abdou;
    first full project
*/ 
//users images
var link = 'users_imgs/';
// +++++++++++++++++++++++++++
    // globals fun ##########"erea"#####
//++++++++++++++++++++++++++++++++++++++++++++


//single element selector 

function $(el){
    return document.querySelector(el);
}
//multi elements selector

function $$(els){
    return document.querySelectorAll(els);
}

// ajax chart function
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

// this is chort function of events 
function Listen(elemnt){
    this.on = function(event, fun){
        elemnt.addEventListener(event, fun);
    };
}
function $l(elemnt){
    return new Listen(elemnt);
}

// transfome object to array 
function tr_array(selector, object){
    var data = [];
    object.forEach(function(el){
        data.push(el[selector]);
    });
    return data;
}

// join the info 
function joinI(in1, in2){
    var data= [];
    in1.forEach(function(in1x) {
        in2.forEach(function(in2x){
            if(in1x[0] == in2x.user_id){
                if (in1x.fq){
                    in2x.msg= in1x.fq;
                }else{
                    in2x.msg ='';
                }
                in2x.date = in1x.date;
                data.push(in2x);
            }
        });
    });
    return data;
}
// ++++++++++++++++++++++++++++ start work on the broject

//++++++++++++++++++++++++++++++++++++++++++++
/*
----------------------------------------------
           functions                                  |
                     >>>>>>>EREA<<<<<<                |
----------------------------------------------
*/
//get person info
function infoOf(id){
    XHR({
        'mt':'GET',
        'to':'includes/getinfo.php',
        'data':'id='+id,
        'se':function (info){
            info = JSON.parse(info);
            $('.profile img').src= link+info.img;
            $('.profile span').innerText = info.name;
            if(info.controle != 'double'){
                $$('.profile .controle span')[0].innerText = info.controle;
                $$('.profile .controle span')[1].innerText = '';
            }else {
                $$('.profile .controle span')[0].innerText = 'accept';
                $$('.profile .controle span')[1].innerText = 'dont accept';
            }
        }
    });
}

//get persons 
function getBy(file,query,setin,data ,before,callback){
    XHR({
        'mt':'GET',
        'to':'includes/'+file+'.php',
        'data':query,
        'se':function (others){
            others = JSON.parse(others);
            if(others.length != 0){
                if(data == ''){
                    others.forEach(function (ot){
                        var query = '<div class="user" data-id="'+ot.user_id+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span></div>';
                        $('.'+setin+' .users').innerHTML += query;
                    });        
                }else {
                    if(before==''){
                        joinI(data,others).forEach(function(ot){
                            var query = '<div class="user" data-id="'+ot.user_id+'" data-date="'+ot.date+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span> '+ot.msg+'</div>';
                            $('.'+setin+' .users').innerHTML += query;
                        });
                    }else if(before== 'check'){
                        callback(others);
                    }else {
                        joinI(data,others).forEach(function(ot){
                            var query = '<div class="user" data-id="'+ot.user_id+'" data-date="'+ot.date+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span> '+ot.msg+'</div>';  
                            $('.'+setin+' .users').insertAdjacentHTML('afterBegin',query) ;
                        });
                    }
                }
            }
            loading('hide', setin);
            selectUsers();
            //auto select after add new elements
        }
    });
}
// short get others function

function Others(how,lastel,sel){
    var query = 'last='+how+'XS'+lastel;
    getBy('getothers',query , sel, '',''); 
}
//short get notis function

function noti( sel,op,select,before){
    XHR({
        'mt':'GET',
        'to':'includes/noti.php',
        'data':'select='+select+'&me='+op,
        'se':function (notis){
            if(notis != '[]'){
                var n = JSON.parse(notis);
                notis = tr_array(0,n);
                var op = op == 'yes'?'get':'';
                getBy('getothers','last=1XS'+notis,sel,n,before);
            }else {
                loading('hide', sel);
            }
        }
    });
}

// hundle repeat users 
function hundle(data, sel){
    var last = '';
    var res = [];
    data.forEach(function(el){
        if(el[sel] != last){
            res.push(el);
            last = el[sel];
        }
    });
    return res;
}

//hundel friends {for notis}
function checklastRels(frs,sel,select){     
    fs = tr_array(0,frs);
    XHR({
        'mt':'GET',
        'to':'includes/getlast.php',
        'data':'frs='+fs,
        'se':function(last){
            var unrd = 0;
            var data= [];
            if(last!='[]'){
                l = hundle(JSON.parse(last),0);
                
                frs.forEach(function(f){ 
                    var res = {};
                        res.date = f.date;
                        res[0]=f[0];
                        res.fq = 0;
                    l.forEach(function(el){
                        if(res[0]==el[0]){
                            res.fq = 1;
                            unrd+=1;
                        }
                    });
                    
                    data.push(res);
                });
            }else {
                frs.forEach(function(f){              
                    f.fq = 0;
                });
            }  
            data = data.length == 0? frs:data;
            if(getComputedStyle($('.'+sel)).color == 'rgb(0, 255, 255)'){
                getBy('getothers','last=1XS'+fs,'ftdjhtyùjot', 'rdtyujtyijgkyu','check', function(others){
                    
                    if(!select.startsWith("2")){
                        joinI(data, others).forEach(function(ot){
                            var state = ot.msg == 1? '<span class="un"><span>':'' ;
                            var query = '<div class="user" data-id="'+ot.user_id+'" data-date="'+ot.date+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span> '+state+'</div>';
                            $('.'+sel+' .users').innerHTML +=query ;
                        });
                    }else {
                        joinI(data, others).forEach(function(ot){
                            var state = ot.msg == 1? '<span class="un"><span>':'' ;
                            var query = '<div class="user" data-id="'+ot.user_id+'" data-date="'+ot.date+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span> '+state+'</div>';  
                            var old = $('.'+sel+' .users [data-id="'+ot.user_id+'"]');
                            if(old != null){
                                old.remove();
                            }
                            $('.'+sel+' .users').insertAdjacentHTML('afterBegin',query) ;
        
                        });
                    }
                    selectfriends();
                    loading('hide', sel);
                });
            }else {
                if(select.startsWith("2") && unrd!=0 ){   
                    $('.'+sel+' .count').innerText = unrd;
                }
            }
        }
    });

}


//get friends 
function friends(sel, select){
    XHR({
        'mt':'GET',
        'to':'includes/fr.php',
        'data':'select='+select,
        'se':function (fr){
            if(fr != '[]'){
                var f = JSON.parse(fr);
                checklastRels(f,sel, select);
            }else {
                loading('hide', sel);
            }
        }
    });
}
//get online users 
function getonline(sel){
    XHR({
        'mt':'GET',
        'to':'includes/online.php',
        'data':'',
        'se':function (onlines){
            $('.'+sel+' .users').innerHTML ='';
            if(onlines != '""'){
                var on = JSON.parse(onlines);
                getBy('getothers','last=1XS'+on,'ftdjhtyùjot', 'rdtyujtyijgkyu','check', function(others){
                    others.forEach(function(ot){
                        var query = '<div class="user" data-id="'+ot.user_id+'" data-date="'+ot.date+'"><div class="img" style=\'background-image:url("'+link+ot.img+'")\'></div><span>'+ot.name+'</span> </div>';
                        $('.'+sel+' .users').innerHTML +=query ;
                    });
                    loading('hide', sel);
                });
            }else {
                loading('hide', sel);
            }
        }
    });
}


//move element for show some folder 
function  move(how, selector){
    $('.move').style.transform ='translateX('+how+')';
    $$('.noti div').forEach(function(el){
        el.style.color= '#FFF';
    });
    $('.'+selector).style.color= '#0FF';
}

// show and hide loading elemnt
function loading(op, where){
    where = '.'+where;
    if(op == 'show'){
        $(where+' .users').insertAdjacentHTML('afterBegin', '<div class="spin"><i class="fa fa-circle-o-notch fa-spin  fa-fw"></i></div>');
    }else{
        var lo =$(where+' .spin');
        if(lo != null){
            lo.remove();
        }
    }
}

// get more users 
function showmore(parent,data){
    var last = $('.'+parent+' .user:last-child').dataset[data];
    if(parent == 'others'){   
        Others('0',last, parent);
    }else if(parent == 'fr-request'){
        noti( parent, 'yes' ,'1XS'+last, '');
       
    }else {
        friends(parent, '1XS'+last);
    }
}
//++++++++++++++++++++++++++++++++++++++++++++

// this function for moving animation when user click on some icon at noti div 
//  and get data ;

$$('.noti div').forEach(function(el){
    $l(el).on('click', function (){

        // get Select element for continue geting infos
        var selector = this.classList[0];

        // check if there users in the selector ;
        var slen = $$('.'+selector+' .user').length;

        //start hundle events 
        if(selector == 'others'){
             //animation
             move('-66%',selector); 
            //get users 
            if(slen ==0){
                loading('show',selector);
                //if no one in the div 
                Others('0','0', selector);
            }
        }else if(selector=='fr-request'){
            //animation
            move('-0%',selector);
            loading('show',selector);
            // get users 
            if(slen ==  0){  
                //if no one in the div 
                //get notis from my notis
                noti(selector,'yes','0XS0','');
            }else {
                noti(selector,'yes','2XS','1');
            }
            // remove notis count 
            removenoti('1', selector);
        }else{
            $('.'+selector+' .count').innerText = '';
            move('-33%', selector);
        }
    });
});

//listen for click event to show more person
$$('.more').forEach(function(el){
    $l(el).on('click', function(){
            // get Select element for continue geting infos
            var selector = this.parentElement.classList[0];
            if($$('.'+selector+' .user').length != 0){
                loading('show', selector);
                if(selector == 'others'){    
                    showmore(selector,'id');
                }else{
                    showmore(selector, 'date');
                }
            }
    });
});

//listen for click event to show  sent friend request and active users
$$('.move .icon').forEach(function(el){
    $l(el).on('click', function(){

            // get Select element for continue geting infos
            var selector = this.parentElement.classList[0];
            // hundle event
            if(selector=='fr-request'){
                if(getComputedStyle($('.'+selector+' .sent')).display=='none'){
                    $('.'+selector+' .sent').style.display = 'block';
                    // continue if there no one in users box
                    if($$('.'+selector+' .sent .user').length == 0){     
                        loading('show', selector+' .sent ');
                        noti(selector+' .sent','no','0XS0','');
                    }
                }else {
                    $('.'+selector+' .sent').style.display = 'none';
                }  
            }else if(selector=='messages'){
                
                if(getComputedStyle($('.'+selector+' .actives')).display=='none'){
                    $('.'+selector+' .actives').style.display = 'block';
                        loading('show', selector+' .actives ');
                        getonline(selector+' .actives ');
                }else {
                    $('.'+selector+' .actives').style.display = 'none';
                }
            }
    });
});
// get last messages
move('-33%','messages');
loading('show', 'messages');
friends('messages','0XS0');
updateMsgBox();

// get new request messages 
function updateMsgBox(){
    setInterval(function(){
        var frel=$('.messages .users').firstElementChild;
        if( frel!= null  ){
            var date = frel.dataset["date"];
            if(date!= undefined){  
                friends('messages','2XS'+date);
            }
        }else {
            friends('messages','0XS0');
        }
   }, 2000);
}
//                       ^
//auto run selectUsers  |
// update notis 
function updatenoti(file,el){
    XHR({
        'mt':'GET',
        'to':'includes/count.php',
        'data':'file='+file,
        'se':function (cnt){
            if(cnt!= '0'){    
                $('.'+el+' .count').innerText = cnt;
            }
        }
    });
}


//remove notis count
function removenoti(file,el){
    XHR({
        'mt':'GET',
        'to':'includes/remove.php',
        'data':'file='+file,
        'se':function (){
            $('.'+el+' .count').innerText = '';
        }
    });
}
//add new friend 
function addfriend(id){
    XHR({
        'mt':'GET',
        'to':'includes/addfr.php',
        'data':'id='+id,
        'se':function (res){
            if(res == '1'){
                $('.controle span').innerText = 'cancel';
            }
        }
    });
}
//remove friend request
function removeFq(id, op){
    XHR({
        'mt':'GET',
        'to':'includes/removeFq.php',
        'data':'id='+id+'&op='+op,
        'se':function (res){
            if(res == '1'){
                $$('.controle span')[0].innerText = 'add'; 
                $$('.controle span')[1].innerText = '';
                var ch = $('.fr-request [data-id="'+id+'"]');
                if(ch != null){
                    ch.remove(); 
                }
            }
        }
    });
}

//accept friend request
function acceptFq(id){
    XHR({
        'mt':'GET',
        'to':'includes/acceptFq.php',
        'data':'id='+id,
        'se':function (res){
            if(res == '1'){
                removeFq(id,'his');
                var ch = $('.fr-request [data-id="'+id+'"]');
                if(ch != null){
                    ch.remove(); 
                }
            }
        }
    });
}

//remove friend
function removeFr(id){
    XHR({
        'mt':'GET',
        'to':'includes/removeFr.php',
        'data':'id='+id,
        'se':function (res){
            if(res == '1'){
                removeFq(id,'his');
                $('.messages [data-id="'+id+'"]').remove();      
                $('.msg-box').style.display = 'none'; 
                $('.msg-box .info .img').dataset.id ='';
            }
        }
    });
}

setInterval(function(){
    updatenoti('1','fr-request');
}, 3000);

// get info of the user
function selectUsers(){
    $$('.fr-request .user, .others .user , .msg-box .img, .res .user').forEach(function(el){
        el.onclick=function (e){
            e.stopPropagation();
            var id = el.dataset.id;
            if (id != ''){    
                $('.profile').style.display = 'block';
                $('.profile').dataset.id=el.dataset.id ;
                infoOf(el.dataset.id);
            }else {
                alert('unknown user');
            }
        };
    });
}
function selectfriends(){
    $$('.messages .user').forEach(function(el){
         el.onclick=  function(){
            var isun = this.lastElementChild;
            if(isun.classList.contains('un')){
                isun.remove();
            }
            $('.msg-box').style.display = 'block';
            $('.msg-box .info .img').dataset.id =this.dataset.id ;
            $('.msg-box .info .img').style.backgroundImage  =getComputedStyle(this.children[0]).backgroundImage ;
            $('.msg-box .info span').innerText = this.children[1].innerText;
            var box=$('.msgs');
            box.innerHTML = '';
            getMessages(this.dataset.id);
            startUpdate(this.dataset.id);
        };
    });
}
$l($('.profile')).on('click', function(e){
    e.stopPropagation();
});
$l(document).on('click', function(){
    $('.profile').style.display = 'none';
});
$l($('.msg-box .info i')).on('click', function(){
    $('.msg-box').style.display = 'none';
    $('.msg-box .info .img').dataset.id ='';
});
$$('.controle span').forEach(function(el){
    $l(el).on('click', function(){
        var s = this.innerText;
        var id =$('.profile').dataset.id;
        if(s =='add'){
            addfriend(id);
        }else if (s == 'cancel'){
            removeFq(id,'my');
        }else if(s =='dont accept'){
            removeFq(id,'his');
        }else if(s =='accept'){
            acceptFq(id);
        }else {
            removeFr(id);
        }
        $('.profile').style.display='none';
    });
});
/////////////////////////////messages erea ///////////////
function updateMessages(id){
    XHR({
        'mt':'GET',
        'to':'includes/upmessages.php',
        'data':'id='+id,
        'se':function (res){
            if(res != '"[]"'){
                res = JSON.parse(res);
                res.forEach(function(r){
                    var box= $('.msgs');
                    box.insertAdjacentHTML('beforeEnd', '<div class="he">'+r.message+'</div>');
                    box.scrollTo (0, box.scrollHeight);
                });
            }
        }
    });
}
function startUpdate(id){
    var o = setInterval(function (){
        if($('.msg-box .info .img').dataset.id !=''){
            updateMessages(id);
            watch(id);
        }else {
            clearInterval(o);
        }
    }, 1000);
}

//////

function getMessages(id){
    XHR({
        'mt':'GET',
        'to':'includes/messages.php',
        'data':'id='+id,
        'se':function (res){
            if(res != '[]'){
                res = JSON.parse(res);
                var box = $('.msgs');
                res.forEach(function(r){
                    function inserting(where){
                        box.insertAdjacentHTML('afterBegin', '<div class="'+where+'" data-date="'+r.date+'">'+r.message+'</div>');
                    }
                    if(r.from_user==id){
                        inserting('he');
                    }else {
                        inserting('me');
                    }
                });
                box.scrollTo (0, box.scrollHeight);
            }
        }
    });
}
 function sentMessage(id, message){
    XHR({
        'mt':'GET',
        'to':'includes/sent.php',
        'data':'id='+id+'&message='+message,
        'se':function (res){
            box =$('.msgs');
            if(res != '2'){
                var msg = document.createElement('div');
                msg.setAttribute('class', 'me');
                msg.dataset.date = res;
                msg.innerText =message;
                box.appendChild(msg);
            }else if(res == '2'){
                box.innerHTML='<b>this user remove you from his friend</b>';
                $('.messages [data-id="'+id+'"]').remove();
                $('.msg-box .info .img').dataset.id ='';
            }
            box.scrollTo (0, box.scrollHeight);
        }
    });
 }

 //get old messages 
 function oldmsg(id, date ){
    XHR({
        'mt':'GET',
        'to':'includes/oldmessages.php',
        'data':'id='+id+'&sel='+date,
        'se':function (res){
            if(res != '"[]"'){
                res = JSON.parse(res);
                var box = $('.msgs');
                res.forEach(function(r){
                    function inserting(where){
                        box.insertAdjacentHTML('afterBegin', '<div class="'+where+'" data-date="'+r.date+'">'+r.message+'</div>');
                    }
                    if(r.from_user==id){
                        inserting('he');
                    }else {
                        inserting('me');
                    }
            });
        }
    }
    });
 }
 $l($('.msg-box .input .icon')).on('click', function(){
     var id =$('.msg-box .info .img').dataset.id;
     var msg = $('.msg-box .input input').value;
     if(id !='' && msg!=''){
         sentMessage(id,msg);
         $('.msg-box .input input').value='';
     }
});  
$l($('.showmore')).on('click', function (){
    var id =$('.msg-box .info .img').dataset.id;
    var lst = $('.msgs').firstElementChild;
    if(id != ''&& lst != null){
        var date = lst.dataset.date;
        if(date){
            oldmsg(id, date);
        }
    }
});

// watch last mine message is it seen 
function watch(id){
    var msg = $('.msgs').lastElementChild;
    if(msg != null){
        if(msg.classList.contains('me')&& msg.children.length ==0){
            XHR({
                'mt':'GET',
                'to':'includes/isseen.php',
                'data':'id='+id+'&date='+msg.dataset.date,
                'se':function (res){
                    if(res == '1'){
                        msg.innerHTML += ' <i class="fa fa-eye" aria-hidden="true"></i>';
                    }
                }
            });
        }
    }
}

// searching box LAST ONE +_+ HOLLLY 

//for get infos
function getSearchRes(word, callback){
    XHR({
        'mt':'GET',
        'to':'includes/search.php',
        'data':'word='+word,
        'se':function (res){
            callback(res);
        }
    });
}

//lesting for event from search box 
$l($('.fa-search')).on('click', function(){
    var word = $('[type="search"]').value;
    $('.res').innerHTML = ''; 
    //for inserting 
    getSearchRes(word, function (results){
        if(results == 'false'){
            $('.res').innerHTML = '<b>no user found</b>';
        }else {
            resul = JSON.parse(results);
            res = $('.res');
            res.insertAdjacentHTML('beforeEnd', '<div class="user" data-id='+resul.user_id+'><b>'+resul.name+'</b></div>');
            selectUsers();
        }
    });
});


