$(function() {
	//状态 改变效果及初始化
	$("#status-toggle .btn").each(function() {
		$(this).bind("click", function() {
			changeStatus($(this));
		});
	});
	if (product_status == undefined) {
		var product_status = 1;
	}
	changeStatus($("#status-toggle .btn:nth-child(" + product_status + ")"));
});

function addProductImage(file, index) {
	if (!/\/(?:jpg|jpeg|png|gif)/i.test(file.type)) return;
	
	//生成ID
	var imageId = 'image_' + index;
	
	//先行插入图片占位----解决reader.onload时间不定会打破原有顺序
	var childPreview  = $("<div class='productPreviewSub'><div>" + 
							//"<img class='img-responsive' src='/images/cool_loading.gif' />" +
							"<img id='" + imageId + "' class='img-responsive' src='' />" +
							"<i class='fa fa-2x fa-times-circle' onclick='$(this).parents(" + '"' + ".productPreviewSub" + '"' + ").remove();'></i>" + 
							"<input name='sort[]' type='number' class='form-control' placeholder='顺序' />" + 
							"<input name='label[]' type='text' maxlength='64' class='form-control' placeholder='标签' />" + 
							"</div></div>");
		$("#productPreview").append(childPreview);
		
	var reader = new FileReader();
	//为reader添加自定义属性保证显示顺序与实际选择顺序一致
	reader.imageId = imageId;
	reader.onload = function (event) {
		//return this.result;
		$("#" + imageId).attr('src', this.result);
	}
	reader.readAsDataURL(file);
	return imageId;
}

function changeStatus(ele) {
	//选择
	ele.find("input").prop("checked", true);
	//样式切换
	ele.parent().find("label").removeClass("btn-info");
	ele.addClass("btn-info");
}

function reLoadAttributes(response) {
	if (response.status && response.status == "OK") {			
		//清空原列表
		$('#setAttributes').html('');
		var rowDatas = response.data;
		for (var arrKey in rowDatas) {
			var rowData = rowDatas[arrKey];
			var trHtml = "<tr>";
			trHtml += "<td><input type='hidden' name='attributeId[]' value=" + rowData.id + " />" + rowData.id  + "</td>";
			trHtml += "<td>" + rowData.code + "</td>";
			trHtml += "<td>" + rowData.label + "</td>";
			console.log('value  ' + rowData.value);
			var attributeValue = rowData.value ? rowData.value : '';
			if (rowData.type == 'dropdown') {
				trHtml += "<td><select name='attributeValue[]' class='form-control'>";
				for (var optionIndex = 0; optionIndex < rowData.options.length; optionIndex ++) {
					var isOptionSelected = attributeValue == rowData.options[optionIndex].option_value ? ' selected' : '';
					trHtml += "<option value='" + rowData.options[optionIndex].option_value + "'" + 
								isOptionSelected + 
								">" + rowData.options[optionIndex].option_label+ "</option>";
				}
				trHtml += "</select></td>";
			} else {
				trHtml += "<td><input type='text' name='attributeValue[]' class='form-control' value='" + attributeValue + "' /></td>";
			}
			
			trHtml += "</tr>";
			
			$('#setAttributes').append($(trHtml));
		}
	} else {
		showMsg("NG", response.msg ? response.msg : "貌似出了点小故障。");
	}
}
