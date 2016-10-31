$(function() {

	$(".date-time").datetimepicker({
		format: "yyyy-mm-dd hh:ii:ss"
	});
	
	
	
});
//删除提示
function confirmDel(url, content) {
	content = content ? content : '确认删除吗'; 
	showConfirm(content,function() {del(url)});
}

//删除
function del(url) {
	closeCusDialog1();
	loading();
	$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			async: false,
			success: function (data) {
				loaded();
				if (data.status == "OK") {
					location.reload();
				} else {
					showMsg('NG', data.msg ? data.msg : '删除失败');
				}
			}
	});
}
//ajax获取信息
function getByUrl(url) {
	content = false;
	$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			async: false,
			success: function (data) {
				content = data;
			}
	});
	
	return content;
}
//弹出窗口----添加点击事件监听 ，异步提交表单 for form show in modal dialog
function addAjaxListenerForModal(btnSelector, modalSelector) {
	$(btnSelector).click(function (){
		
		loading();
		$(modalSelector + " form").ajaxSubmit({
			success: function(response) {	
				if (response.status == 'OK') {
					$(modalSelector).modal('hide');
					showMsg(response.status, response.msg);
					if ($(modalSelector + " form").data('fresh')) {
						location.reload();
					}
					if ($(modalSelector + " form").data('href')) {
						location.href = $(modalSelector + " form").data('href');
					}
				} else {
					$(modalSelector + " form .error_msg").html(response.msg);
				}
				
				loaded();
				/**
				//loaded();
				$(formSelector + " .error_msg").html('');
				//showMsg(response.status, response.msg);
				//新增成功时刷新
				if (response.status == 'OK' && $(modalSelector + " form").data('fresh')) {
					location.reload();
				}
				*/
			}
		});
	});
}

//添加点击事件监听 ，异步提交表单， 额外操作 for normal form
function addAjaxListener(btnSelector, formSelector) {
	$(btnSelector).click(function (){
		loading();
		
		//清空错误提示 并隐藏
		$(formSelector + " .alert-danger").html("");
		$(formSelector + " .alert-danger").addClass("hidden");
		
		$(formSelector).ajaxSubmit({
			success: function(response) {
				loaded();
				if (response.status == 'OK') {
					showMsg(response.status, response.msg);
					//执行额外操作
					if ($(formSelector).data('fresh')) {
						location.reload();
					}
					if ($(formSelector).data('href')) {
						location.href = $(formSelector).data('href');
					}
				} else {
					//更新错误提示 并显示
					$(formSelector + " .alert-danger").html(response.msg);
					$(formSelector + " .alert-danger").removeClass("hidden");
				}
			}
		});
	});
}

//重置表单，并决定 是否在成功操作后刷新页面
function resetForm(formSelector, refreshFlg) {
	$(formSelector).resetForm();
	$(formSelector + " .error_msg").html("");
	$(formSelector).data("fresh", refreshFlg);
}
