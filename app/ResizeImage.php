<?php

namespace App;

//使用如下类就可以生成图片缩略图,
class ResizeImage
{
  //图片类型
  var $type;
  //实际宽度
  var $width;
  //实际高度
  var $height;
  //改变后的宽度
  var $resizeWidth;
  //改变后的高度
  var $resizeHeight;
  //是否裁图
  var $cut;
  //源图象
  var $srcImg;
  //目标图象地址
  var $dstImg;
  //临时创建的图象
  var $im;
  //根据类型使用不同的创建图像函数，缩略图与原图格式一致
  var $imgCreateFunc;
  
  public function resizeImage($img, $width, $height,$dstPath, $cut = false)
  {
    $this->srcImg = $img;
    $this->resizeWidth = $width;
    $this->resizeHeight = $height;
    $this->cut = $cut;
	
    //图片的类型
	$this->type = strtolower(substr(strrchr($this->srcImg,"."),1));
  
    //初始化图象
    $this->initiImg();
    //目标图象地址
    $this->dstImg($dstPath);
    $this->width = imagesx($this->im);
    $this->height = imagesy($this->im);
    //生成图象
    $this->newImg();
    ImageDestroy ($this->im);
  }
  
  public function newImg()
  {
    //改变后的图象的比例
    $resize_ratio = ($this->resizeWidth) / ($this->resizeHeight);
    //实际图象的比例
    $ratio = ($this->width)/($this->height);
    if($this->cut)
    //裁图
    {
      if($ratio>=$resize_ratio)
      //高度优先
      {
        $newImg = imagecreatetruecolor($this->resizeWidth,$this->resizeHeight);
        imagecopyresampled($newImg, $this->im, 0, 0, 0, 0, $this->resizeWidth,$this->resizeHeight, (($this->height)*$resize_ratio), $this->height);
      }
      if($ratio<$resize_ratio)
      //宽度优先
      {
        $newImg = imagecreatetruecolor($this->resizeWidth,$this->resizeHeight);
        imagecopyresampled($newImg, $this->im, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->width, (($this->width)/$resize_ratio));
      }
    }
    else
    //不裁图
    {
      if($ratio>=$resize_ratio)
      {
        $newImg = imagecreatetruecolor($this->resizeWidth,($this->resizeWidth)/$ratio);
        imagecopyresampled($newImg, $this->im, 0, 0, 0, 0, $this->resizeWidth, ($this->resizeWidth)/$ratio, $this->width, $this->height);
      }
      if($ratio<$resize_ratio)
      {
        $newImg = imagecreatetruecolor(($this->resizeHeight)*$ratio,$this->resizeHeight);
        imagecopyresampled($newImg, $this->im, 0, 0, 0, 0, ($this->resizeHeight)*$ratio, $this->resizeHeight, $this->width, $this->height);
      }
    }
	call_user_func($this->imgCreateFunc, $newImg, $this->dstImg);
  }
  
  //初始化图象
  public function initiImg()
  {
    if($this->type=="jpg")
    {
      $this->im = imagecreatefromjpeg($this->srcImg);
	  $this->imgCreateFunc = 'imagejpeg';
    }
    if($this->type=="gif")
    {
      $this->im = imagecreatefromgif($this->srcImg);
	   $this->imgCreateFunc = 'imagegif';
    }
    if($this->type=="png")
    {
      $this->im = imagecreatefrompng($this->srcImg);
	   $this->imgCreateFunc = 'imagepng';
    }
  }
  
  //图象目标地址
  public function dstImg($dstPath)
  {
 	if ( ($dotPos = strrpos($dstPath, '.')) !== false ) {
		$dstPath = substr($dstPath, 0, $dotPos);
	}
	
	$this->dstImg = $dstPath . "." . $this->type;   
  }
}
