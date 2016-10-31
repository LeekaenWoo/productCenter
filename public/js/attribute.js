function allowDrop(ev)
{
	ev.preventDefault();
}
function drag(ev)
{
	ev.dataTransfer.setData("Text",ev.target.id);
}

function dropToSelected(ev)
{
	ev.preventDefault();
	var id=ev.dataTransfer.getData("Text");
	ev.target.appendChild(document.getElementById(id));
}

//move unselected to selected when editing
function moveToSelected(originalId, oEntityId) {
	var selector = "#unSelectedAttr li[id$=-" + originalId + "]";
	//增加原有属性标识
	var moveEle = $(selector).addClass('oldAttr').data("entityId", oEntityId);
	$("#selectedAttr").append(moveEle);
	$(selector).remove();
	//$("#selectedAttr");

}

function dropToUnselected(ev) {
	ev.preventDefault();
	var id=ev.dataTransfer.getData("Text");
	var target = $('#' + id);
	//自定义的格式，必定有-
	var groupIdLength = id.indexOf('-');
	var $groupId = id.substring(0,groupIdLength);
	//去除 '-'
	var attrId = id.substring(groupIdLength + 1);
	
	
	//编辑时判断 是否原有ENTITY里的属性，是则AJAX操作并更新
	/**
	if (target.hasClass('oldAttr')) {		var response = getByUrl('../entity/delete/' + target.data("entityId"));
		showMsg(response.status ? response.status : 'NG', response.msg ? response.msg : '网络/系统故障');
		//移除成功， 失败直接返回拖曳无效
		if (response.status == "OK") {
			target.removeClass('oldAttr');
		} else {
			return false;
		}
		
	}
	*/
	
	
	//已选择到未选择 自动进入相应分组--或未分组
	var groupEle;
	if (groupEle = $("#" + $groupId)) {
		groupEle.append(target);
	} else {
		$('#ungroup').append(target);
	}
}