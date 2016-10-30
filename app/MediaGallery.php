<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ResizeImage;

class MediaGallery extends Model
{
	//返回图片上传用户
    public function user() {
		return $this->hasOne('App\User');
	}
	
	//返回所属产品
	public function product() {
		return $this->belongsTo('App\Product');
	}
	
	//保存产品图
	public static function saveMedia($file, $productId, $userId, $sort, $label) {
		if (!in_array($file->getMimeType(), ['image/png', 'image/gif', 'image/jpeg'])) {
			return;
		}
		
		$imagePath = public_path() . '/productImages';
		$thumbPath = public_path() . '/productThumbnails';
		$subDir = '/' . substr($productId, 0, 1) . '/' . $productId;
		$fileName = date('his') . '_' . str_random(6) . '.' . strtolower($file->getClientOriginalExtension());
		
		
		$image = $file->move($imagePath . $subDir, $fileName);			
		$resizeImage = new ResizeImage();
		$resizeImage->resizeImage($image, 100, 100,$thumbPath . $subDir . '/' . $fileName);
		
		$imageData = new MediaGallery;
		$imageData->product_id = $productId;
		$imageData->user_id = $userId;
		$imageData->path = $subDir . '/' . $fileName;
		$imageData->sort = $sort;
		$imageData->label = $label;
		$imageData->save();	
	}
	
	//删除部分文件文件
	public static function deleteMedia($pathArray) {
		if (empty($pathArray)) {
			return;
		}
		if (!is_array($pathArray)) {
			$pathArray[] = $pathArray; 
		}
		
		foreach ($pathArray as $path) {
			$imagePath = public_path() . '/productImages' . $path;
			$thumbPath = public_path() . '/productThumbnails' . $path;
			
			@unlink($imagePath);
			@unlink($thumbPath);	
		}
	}
}
