/* jce - 2.8.18 | 2020-09-29 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2020 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var DOM=tinymce.DOM,Event=tinymce.dom.Event,extend=tinymce.extend;tinymce.create("tinymce.plugins.ImageManager",{init:function(ed,url){function isMceItem(node){return node.className.indexOf("mce-item-")!==-1}function openDialog(){var node=ed.selection.getNode();isMceItem(node)||ed.windowManager.open({file:ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.display&plugin=image",size:"mce-modal-portrait-full"},{plugin_url:url})}function getImageProps(value){return new Promise(function(resolve,reject){if(!value)return resolve();var img=new Image;img.onload=function(){resolve({width:img.width,height:img.height})},img.onerror=function(){reject()},img.src=ed.documentBaseURI.toAbsolute(value)})}function insertImage(args){var node=ed.selection.getNode();node&&"IMG"===node.nodeName?ed.dom.setAttribs(node,args):(ed.execCommand("mceInsertContent",!1,'<img id="__mce_tmp" src="" />',{skip_undo:1}),node=ed.dom.get("__mce_tmp"),ed.dom.setAttribs(node,args),ed.dom.setAttrib(node,"id","")),ed.selection.select(node),ed.undoManager.add(),ed.nodeChanged()}function getDataAndInsert(args){var params=ed.getParam("imgmanager",{});return new Promise(function(resolve,reject){params.always_include_dimensions?(ed.setProgressState(!0),getImageProps(args.src).then(function(data){ed.setProgressState(!1),insertImage(extend(args,data)),resolve()},function(){ed.setProgressState(!1),reject()})):(insertImage(args),resolve())})}this.editor=ed;var self=this;ed.addCommand("mceImageManager",function(){openDialog()}),ed.addCommand("mceImage",function(){openDialog()}),ed.addButton("imgmanager",{title:"imgmanager.desc",cmd:"mceImage"}),ed.onNodeChange.add(function(ed,cm,n){cm.setActive("imgmanager","IMG"==n.nodeName&&!isMceItem(n))}),ed.onPreInit.add(function(){var params=ed.getParam("imgmanager",{});if(params.basic_dialog===!0){var urlCtrl,captionCtrl,cm=ed.controlManager,form=cm.createForm("image_form"),args={label:ed.getLang("url","URL"),name:"url",clear:!0};params.basic_dialog_filebrowser&&tinymce.extend(args,{picker:!0,picker_icon:"image",onpick:function(){ed.execCommand("mceFileBrowser",!0,{caller:"imgmanager",callback:function(selected,data){if(data.length){var src=data[0].url,title=data[0].title;urlCtrl.value(src),title=title.replace(/\.[^.]+$/i,""),descriptionCtrl.value(title),window.setTimeout(function(){urlCtrl.focus()},10)}},filter:params.filetypes||"images"})}}),urlCtrl=cm.createUrlBox("image_url",args),form.add(urlCtrl),descriptionCtrl=cm.createTextBox("image_description",{label:ed.getLang("image.description","Description"),name:"alt",clear:!0}),form.add(descriptionCtrl),ed.addCommand("mceImage",function(){var node=ed.selection.getNode();isMceItem(node)||ed.windowManager.open({title:ed.getLang("image.desc","Image"),items:[form],size:"mce-modal-landscape-small",open:function(){var label=ed.getLang("insert","Insert"),node=ed.selection.getNode(),src="",alt="",caption=!1;if(node&&"IMG"===node.nodeName){var src=ed.dom.getAttrib(node,"src");src&&(label=ed.getLang("update","Update"));var alt=ed.dom.getAttrib(node,"alt"),figcaption=ed.dom.getNext(node,"figcaption");figcaption&&(caption=!0)}urlCtrl.value(src),descriptionCtrl.value(alt),captionCtrl&&captionCtrl.checked(caption),window.setTimeout(function(){urlCtrl.focus()},10),DOM.setHTML(this.id+"_insert",label)},buttons:[{title:ed.getLang("common.cancel","Cancel"),id:"cancel"},{title:ed.getLang("insert","Insert"),id:"insert",onsubmit:function(e){var data=form.submit(),node=ed.selection.getNode();if(Event.cancel(e),!data.url)return node&&"IMG"===node.nodeName&&ed.dom.remove(node),!1;var args={src:data.url,alt:data.alt};args=extend(args,self.getAttributes(params.attributes||{})),getDataAndInsert(args).then(function(){if(node=ed.selection.getNode(),captionCtrl){var figcaption=ed.dom.getNext(node,"figcaption");data.caption&&data.alt?figcaption?figcaption.textContent=data.alt:(ed.selection.select(node),ed.formatter.apply("figure",{caption:data.alt})):figcaption&&(ed.dom.remove(figcaption.parentNode,1),ed.dom.remove(figcaption))}})},classes:"primary",scope:self}]})})}}),ed.onInit.add(function(){ed&&ed.plugins.contextmenu&&ed.plugins.contextmenu.onContextMenu.add(function(th,m,e){m.add({title:"imgmanager.desc",icon:"imgmanager",cmd:"mceImage"})})})},getAttributes:function(data){var ed=this.editor,attr={style:{}},supported=["alt","title","id","dir","class","usemap","style","longdesc","loading"],attribs=data.attributes||{};return attribs.style&&tinymce.is(attribs.style,"string")&&(attribs.style=ed.dom.parseStyle(attribs.style)),attribs.styles&&tinymce.is(attribs.styles,"object")&&(attribs.style=extend(attribs.styles,attribs.style||{}),delete attribs.styles),attribs.style&&(attribs.style=ed.dom.serializeStyle(attribs.style)),tinymce.each(supported,function(key){tinymce.is(attribs[key])&&(attr[key]=attribs[key])}),attr},insertUploadedFile:function(o){var ed=this.editor,data=this.getUploadConfig();if(data&&data.filetypes&&new RegExp(".("+data.filetypes.join("|")+")$","i").test(o.name)){var args={src:o.file,alt:o.alt||o.name,style:{}};return args=extend(args,this.getAttributes(o)),ed.dom.create("img",args)}return!1},getUploadURL:function(file){var ed=this.editor,data=this.getUploadConfig();return!!(data&&data.filetypes&&new RegExp(".("+data.filetypes.join("|")+")$","i").test(file.name))&&ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.display&plugin=image"},getUploadConfig:function(){var ed=this.editor,data=ed.getParam("imgmanager",{});return data.upload||{}}}),tinymce.PluginManager.add("imgmanager",tinymce.plugins.ImageManager)}();