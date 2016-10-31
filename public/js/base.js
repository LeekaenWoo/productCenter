function copyToClipboard(maintext){
	var  copytextstr = $("#"+maintext).text();
	if (window.clipboardData){
		window.clipboardData.setData("Text", copytextstr);
	}else if (window.netscape){
		  try{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}catch(e){
			alert("该浏览器不支持一键复制！请手工复制文本～");
		}

		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip) return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans) return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext=copytextstr;
		str.data=copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid=Components.interfaces.nsIClipboard;
		if (!clip) return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
	alert("复制成功！");
}


/*alert*/
function showInfo(content){
	createDialogElemet({"type":0,"content":content});
	showCusDialog1(false,null);
}

function showSuccesssInfo(content,btn_handler) {
	createDialogElemet({"type":1,"content":content});
	showCusDialog1(false,btn_handler);
}

function showFailInfo(content) {
	createDialogElemet({"type":2,"content":content});
	showCusDialog1(false,null);
}

function showConfirm(content,btn_handler) {
	createDialogElemet({"type":3,"content":content});
	showCusDialog1(true,btn_handler);
}

function showOtherInfo(title,content,btn_handler) {
	createDialogElemet({"type":4,"title":title,"content":content});
	showCusDialog1(false,btn_handler);
}

function createDialogElemet(opts) {
	if ($("#cus_dialog_1").length > 0) {
		$("#cus_dialog_1").remove();
	}
	
	
	type_class_array = new Array(
							"cus_dialog_info",
							"cus_dialog_success",
							"cus_dialog_fail",
							"cus_dialog_confirm",
							"cus_dialog_other"
						);
	type_title_array = new Array(
							"提示",
							"成功提示",
							"错误提示",
							"确认提示",
							"提示"
						);
	//type 0,1,2,3 info success fail confirm
	var defaults = {"type":0,"content":"抱歉，好象出了点问题^^"};
	var options = $.extend({},defaults, opts);
	
	var ele_html = "<div id='cus_dialog_1' class='cus_dialog " + type_class_array[options.type] + "' style='display:none'>" +
					"<div class='cus_dialog_header'>" + 
					(opts.title || type_title_array[options.type]) + 
					"<a class='close' onclick='closeCusDialog1();' aria-hidden='true'>&times;</a>" +
					"</div>" + 
					"<div class='cus_dialog_content'>" + options.content + "</div>" + 
					"</div>";
					
	$(document.body).append($(ele_html));
}

function showCusDialog1(show_cancel,btn_handler) {
	if (btn_handler == null) {
		btn_handler = closeCusDialog1;
	}
	
	if (show_cancel) {
		$('#cus_dialog_1').dialog({
		modal: true,
	    autoOpen: true,
	    width: 600,
	    position:'center',
		
		show: {
				effect: "blind",
				duration: 500
		},
		hide: {
				effect: "blind",
				duration: 500
		},
		
	    resizable: false,
	    buttons: {
	        "确认": function () {
					btn_handler();
			},
	        "取消": function () {
					closeCusDialog1();
		        }
		    }
		});
	} else {
		$('#cus_dialog_1').dialog({
		modal: true,
	    autoOpen: true,
	    width: 600,
	    position:'center',
	    /*
		show: {
				effect: "blind",
				duration: 1000
		},
		hide: {
				effect: "explode",
				duration: 1000
		},
		*/
	    resizable: false,
	    buttons: {
			"确认": function () {
		            btn_handler();
				 }
			}
		});
	}
}

function closeCusDialog1() {
	if ($("#cus_dialog_1").length > 0) {
		$("#cus_dialog_1").dialog('close');
	}
}
/*alert end*/

function loading() {
	$(".loading").show();
}
function loaded() {
	$(".loading").fadeOut();
}

function showMsg(status, msg) {
	var gritter_class = (status == 'NG' || status == 'gritter-error') ? 'gritter-error' : 'gritter-success';	
	$.gritter.add({  
		title: '提示',  
		text: msg,  
		sticky: false,  
		time: 3500,  
		speed:500,  
		position: 'bottom-left',  
		class_name: gritter_class//gritter-center   
	}); 
}
