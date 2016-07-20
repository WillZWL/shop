/*
*	jQuery文件上传插件,封装UI,上传处理操作采用Baidu WebUploader;
*/
(function( $ ) {
    $.fn.extend({
		/*
		*	上传方法 opt为参数配置;
		*	serverCallBack回调函数 每个文件上传至服务端后,服务端返回参数,无论成功失败都会调用 参数为服务器返回信息;
		*/
        imgUpload:function( opt, serverCallBack ) {
 			if ( typeof opt != "object" ) {
				alert('ERROR!');
				return;
			}
			var $fileInput = $(this);
			var $fileInputId = $fileInput.attr('id');
			//组装参数;
			if( opt.url ) {
				opt.server = opt.url;
				delete opt.url;
			}

			if( opt.success ) {
				var successCallBack = opt.success;
				delete opt.success;
			}

			if( opt.error ) {
				var errorCallBack = opt.error;
				delete opt.error;
			}

			//迭代出默认配置
			$.each( getOption( '#'+$fileInputId ),function( key, value ){
					opt[ key ] = opt[ key ] || value;
			});

			if ( opt.buttonText ) {
				opt['pick']['label'] = opt.buttonText;
				delete opt.buttonText;
			}

			var webUploader = getUploader( opt );

			if ( !WebUploader.Uploader.support() ) {
				alert( ' Sorry, Not support your browser');
				return false;
       		}

			//绑定文件加入队列事件;
			webUploader.on('fileQueued', function( file ) {
				createBox( $fileInput, file ,webUploader);

			});

			//进度条事件
			webUploader.on('uploadProgress',function( file, percentage  ){
				var $fileBox = $('#fileBox_'+file.id);
				var $imgBar = $fileBox.find('.imgBar');
				$imgBar.show();
				percentage = percentage*100;
				showImgProgress( percentage.toFixed(2), $imgBar);

			});

			//全部上传结束后触发;
			webUploader.on('uploadFinished', function(){
				$fileInput.next('.parentFileBox').children('.imgButton').remove();
			});
			//绑定发送至服务端返回后触发事件;
			webUploader.on('uploadAccept', function( object ,data ){
				if ( serverCallBack ) serverCallBack( data );
			});

			//上传成功后触发事件;
			webUploader.on('uploadSuccess',function( file, response ){
				var $fileBox = $('#fileBox_'+file.id);
				var $imgBar = $fileBox.find('.imgBar');
				$fileBox.removeClass('imgUploadHover');
				$imgBar.fadeOut( 1000 ,function(){
					$fileBox.children('.imgSuccess').show();
				});
				if ( successCallBack ) {
					successCallBack( response );
				}
			});

			//上传失败后触发事件;
			webUploader.on('uploadError',function( file, reason ){
				var $fileBox = $('#fileBox_'+file.id);
				var $imgBar = $fileBox.find('.imgBar');
				showImgProgress( 0, $imgBar , 'Upload Failed!' );
				var err = 'Upload Failed! File:'+file.name+' Error Code:'+reason;
				if ( errorCallBack ) {
					errorCallBack( err );
				}
			});

			// 上传文件时附带图片的相关信息
			webUploader.on('uploadBeforeSend', function( block, data ){
				var file = block.file;
				var $fileBox = $('#fileBox_'+file.id);
				var $imgInfo = $fileBox.find('.imgInfo');
				var $file_id = file.id;
				var $link = $imgInfo.find('input:text[name=link]').val();
				var $target_type = $imgInfo.find('.target_type').val();
				var $image_alt = $imgInfo.find('input:text[name=image_alt]').val();
				data.file_id = $file_id;
				data.link = $link;
				data.target_type = $target_type;
				data.image_alt = $image_alt;
			});

			//选择文件错误触发事件;
			webUploader.on('error', function( code ) {
				var text = '';
				switch( code ) {
					case  'F_DUPLICATE' : text = 'The file has been selected!' ;
					break;
					case  'Q_EXCEED_NUM_LIMIT' : text = 'Upload file number over limit!' ;
					break;
					case  'F_EXCEED_SIZE' : text = 'The file size over limit!';
					break;
					case  'Q_EXCEED_SIZE_LIMIT' : text = 'All files total size exceeds the limit!';
					break;
					case 'Q_TYPE_DENIED' : text = 'The file type is not correct or is an empty file!';
					break;
					default : text = 'Unknow Error!';
 					break;
				}
            	alert( text );
        	});
        }
    });

	//Web Uploader默认配置;
	function getOption(objId) {
		/*
		*	配置文件同webUploader一致,这里只给出默认配置.
		*	具体参照:http://fex.baidu.com/webuploader/doc/index.html
		*/
		return {
			//按钮容器;
			pick:{
				id:objId,
				label:"Choose Image"
			},
			//类型限制;
			accept:{
				title:"Images",
				extensions:"gif,jpg,jpeg,bmp,png",
				mimeTypes:"image/*"
			},
			//配置生成缩略图的选项
			thumb:{
				width:170,
				height:150,
				// 图片质量，只有type为`image/jpeg`的时候才有效。
				quality:70,
				// 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
				allowMagnify:false,
				// 是否允许裁剪。
				crop:true,
				// 为空的话则保留原有图片格式。
				// 否则强制转换成指定的类型。
				type:"image/jpeg"
			},
			//文件上传方式
			method:"POST",
			//服务器地址;
			server:"",
			//是否已二进制的流的方式发送文件，这样整个上传内容php://input都为文件内容
			sendAsBinary:false,
			chunked:true,
			// 分片大小
			chunkSize:512 * 1024,
			//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
			fileNumLimit:50,
			fileSizeLimit:5000 * 1024,
			fileSingleSizeLimit:500 * 1024
		};
	}

	//实例化Web Uploader
	function getUploader( opt ) {
		return new WebUploader.Uploader( opt );;
	}

	//操作进度条;
	function showImgProgress( progress, $imgBar, text ) {

		if ( progress >= 100 ) {
			progress = progress + '%';
			text = text || 'Upload Complete';
		} else {
			progress = progress + '%';
			text = text || progress;
		}
		var $imgProgress = $imgBar.find('.imgProgress');
		var $imgProgressText = $imgBar.find('.imgProgressText');
		$imgProgress.width( progress );
		$imgProgressText.text( text );
	}

	//取消事件;
	function removeLi ( $li ,file_id ,webUploader) {
		webUploader.removeFile( file_id );
		if ( $li.siblings('li').length <= 0 ) {
			$li.parents('.parentFileBox').remove();
		} else {
			$li.remove();
		}
	}

	//创建文件操作div;
	function createBox( $fileInput, file, webUploader ) {

		var file_id = file.id;
		var $parentFileBox = $fileInput.next('.parentFileBox');
		//添加父系容器;
		if ( $parentFileBox.length <= 0 ) {
			var div = '<div class="parentFileBox"> \
						<ul class="fileBoxUl" id="sortable"></ul>\
					</div>';
			$fileInput.after( div );
			$parentFileBox = $fileInput.next('.parentFileBox');
		}

		//创建按钮
		if ( $parentFileBox.find('.imgButton').length <= 0 ) {
			var div = '<div class="imgButton"> \
						<a class="imgStart" href="javascript:void(0)">Start Upload</a> \
						<a class="imgCancelAll" href="javascript:void(0)">Cancel All</a> \
					</div>';
			$parentFileBox.append( div );
			var $startButton = $parentFileBox.find('.imgStart');
			var $cancelButton = $parentFileBox.find('.imgCancelAll');
			//开始上传,暂停上传,重新上传事件;
			var uploadStart = function (){
				webUploader.upload();
				$startButton.text('Pause').one('click',function(){
						webUploader.stop();
						$(this).text('Continue').one('click',function(){
								uploadStart();
						});
				});
			}
			//绑定开始上传按钮;
			$startButton.one('click',uploadStart);
			//绑定取消全部按钮;
			$cancelButton.bind('click',function(){
				var fileArr = webUploader.getFiles( 'queued' );
				$.each( fileArr ,function( i, v ){
					removeLi( $('#fileBox_'+v.id), v.id, webUploader );
				});
			});
		}

		//添加子容器;
		var li = '<li id="fileBox_'+file_id+'" class="imgUploadHover"> \
					<div class="viewThumb"></div> \
					<div class="imgCancel"></div> \
					<div class="imgSuccess"></div> \
					<div class="imgFileName">'+file.name+'</div>\
					<div class="imgBar"> \
							<div class="imgProgress"></div> \
							<div class="imgProgressText">0%</div> \
					</div> \
					<div class="imgInfo"> \
						<p><b>Target Url:</b> <input type="text" name="link" class="input" ></p> \
						<p><b>Target Type: </b><select name="target_type" class="target_type"> \
							<option value="2">open in same window</option> \
							<option value="1">open in new window</option> \
							</select> \
						</p>	\
						<p><b>Image Alt: </b><input type="text" name="image_alt" class="input" ></p> \
					</div> \
				</li>';

		$parentFileBox.children('.fileBoxUl').append( li );

		//父容器宽度;
		var $width = $('.fileBoxUl>li').length * 550;
		var $maxWidth = $fileInput.parent().width();
		$width = $maxWidth > $width ? $width : $maxWidth;
		// $parentFileBox.width( $width );
		var $fileBox = $parentFileBox.find('#fileBox_'+file_id);
		//绑定取消事件;
		var $imgCancel = $fileBox.children('.imgCancel').one('click',function(){
			removeLi( $(this).parent('li'), file_id, webUploader );
		});
		if ( file.type.split("/")[0] != 'image' ) {
			var liClassName = getFileTypeClassName( file.name.split(".").pop() );
			$fileBox.addClass(liClassName);
			return;
		}
		//生成预览缩略图;
		webUploader.makeThumb( file, function( error, dataSrc ) {
			if ( !error ) {
				$fileBox.find('.viewThumb').append('<img src="'+dataSrc+'" >');
			}
		});
	}

	//获取文件类型;
	function getFileTypeClassName ( type ) {
		var fileType = {};
		var suffix = '_img_bg';
		fileType['pdf'] = 'pdf';
		fileType['zip'] = 'zip';
		fileType['rar'] = 'rar';
		fileType['csv'] = 'csv';
		fileType['doc'] = 'doc';
		fileType['xls'] = 'xls';
		fileType['xlsx'] = 'xls';
		fileType['txt'] = 'txt';
		fileType = fileType[type] || 'txt';
		return 	fileType+suffix;
	}
})( jQuery );