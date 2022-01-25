var toast = document.getElementById('toastmsg');

// (function() {
//     toast.style.display="none";
 
//  })();

function copyfunc(tdval,btnid){
    var btnids=document.getElementById(btnid);
    document.getElementById('toastmsg').style.display="flex";
    //btnids.classList.add('activebtn');
    var node = document.createElement( "textarea" );
    //btnids.innerHTML = "copied";
    
   setTimeout(function(){ 
    document.getElementById('toastmsg').style.display="none";
    }, 2000);
    //document.getElementById(btnid).classList.add('copied');
    node.innerHTML = tdval;

    document.body.appendChild( node ); 
    node.select();  

try{ 
    var success = document.execCommand( "copy" );
} 
catch(e){ 
    console.log( "browser not compatible" );
} 
document.body.removeChild( node );
}