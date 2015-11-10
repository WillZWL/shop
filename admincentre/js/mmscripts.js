/*
	Copyright (c) 2005-2012, Guru Sistemas and/or Gustavo Adolfo Arcila Trujillo
	All rights reserved.
	www.gurusistemas.com
	
	phpMyDataGrid Professional IS NOT FREE, may not be re-sold or redistributed as a single library.
	
	If you want to use phpMyDataGrid Professional on any of your projects, you Must purchase a license.
	
	You can buy the full source code or encoded version at http://www.gurusistemas.com/
	also can try the donationware version, which can be downloaded from http://www.gurusistemas.com/
	
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  "AS IS"  AND ANY EXPRESS  OR  IMPLIED WARRANTIES, INCLUDING, 
	BUT NOT LIMITED TO,  THE IMPLIED WARRANTIES  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT
	SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,  INDIRECT,  INCIDENTAL, SPECIAL, EXEMPLARY,  OR CONSEQUENTIAL 
	DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF  USE, DATA, OR PROFITS;  OR BUSINESS 
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
	OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 
	
	For more info, samples, tips, screenshots, help, contact, support, please visit phpMyDataGrid site  
	http://www.gurusistemas.com/
*/
var MM_parameters = '';var MM_om=false;var MM_nombreDiv;

function MM_esNS(){
    return (navigator.userAgent.toLowerCase().indexOf("opera")!=-1 || navigator.product == 'Gecko');
} 

function Mac(){	
    if (!document.getElementById("DG_dgactive")) {
		var newDivData = document.createElement("input");
        newDivData.type= 'hidden';
        newDivData.name = 'DG_dgactive';
        newDivData.id = 'DG_dgactive';
        newDivData.value = '';
		document.getElementsByTagName("body")[0].appendChild(newDivData);
    }; 
    return DG_gvv('DG_dgactive'); 
};

function MM_ocultar(MM_DivName){
	if (!document.getElementById("DivActiveDiv" + Mac())) {
		var newDivData = document.createElement("input");
        newDivData.type= 'hidden';
        newDivData.name = 'DivActiveDiv' + Mac();
        newDivData.id = 'DivActiveDiv' + Mac();
        newDivData.value = '';
		document.getElementsByTagName("body")[0].appendChild(newDivData);
	};
	var MM_DivName = document.getElementById("DivActiveDiv"+Mac()).value;
	if( MM_om == false ){
		if (MM_DivName!='') document.getElementById(MM_DivName).style.visibility = "hidden";
	}else{
		MM_nombreDiv=MM_DivName;
		setTimeout('document.getElementById("'+MM_nombreDiv+'").style.visibility = "hidden";',200);
		return true;
	};
};

function MM_mostrar(MM_DivName,e, param, yOffset, xOffset) {
	MM_parameters=param;
	if (typeof(yOffset)=='undefined') yOffset = 0;
	if (typeof(xOffset)=='undefined') xOffset = 0;
	Da= DG_goo("DivActiveDiv" + Mac());
	if(Da.value != "") MM_ocultar(Da.value);
	Da.value = MM_DivName;

    obj = DG_goo(MM_DivName);
/*  This caused an IE7-IE8 Bug. Why this was here?
    parent = obj.parentNode;
    
    if (typeof(parent.id)!='undefined' && parent.id.toLowerCase() != "body"){
        parent.removeChild(obj);
        document.body.appendChild(obj);
    }
*/
  
    var posX = 0, posY = 0;
	if( typeof( window.pageYOffset ) == 'number' ) {
		//Netscape compliant
		posY = window.pageYOffset;
		posX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		//DOM compliant
		posY = document.body.scrollTop;
		posX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		//IE6 standards compliant mode
		posY = document.documentElement.scrollTop;
		posX = document.documentElement.scrollLeft;
	}
	if (MM_esNS()){
		mouseX=e.clientX;
		mouseY=e.clientY;
		if(window.navigator.vendor != "Apple Computer, Inc."){
			mouseX += posX;
			mouseY += posY;
		};
		obj.style.left = (mouseX - xOffset) + 'px';
		obj.style.top  = (mouseY - yOffset) + 'px';
	}else{
		obj.style.pixelLeft = event.clientX - xOffset + posX;
		obj.style.pixelTop = event.clientY - yOffset + posY;
	};
	
	obj.style.visibility = "visible";
	return false;
}
function MM_process(action, type){
	if(type=='J'){
		eval(action);
	}else{
		window.location=action+MM_parameters;
	};
};

if ( typeof window.onload == 'function' ) {
	old_onload = window.onload;
	window.onload = function(){
		old_onload();
		if ( typeof document.body.onmousedown == 'function' ) {
			old_mousedown = document.body.onmousedown;
			document.body.onmousedown = function(){ old_mousedown(); MM_ocultar(); }
		}else{
			document.body.onmousedown = MM_ocultar;
		};
	};
}else{
	window.onload = function(){
		if ( typeof document.body.onmousedown == 'function' ) {
			old_mousedown = document.body.onmousedown;
			document.body.onmousedown = function(){ old_mousedown(); MM_ocultar(); };
		}else{
			document.body.onmousedown = MM_ocultar;
		};
	};
};