/*
#################################
sample auto complete
---------------------------------
<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
  <div class="col-md-4" style="vertical-align: middle;text-align: right;">
    <label class="control-label ">BM ECNO : <span style='color:red'>*</span></label>
  </div>
  <div class="col-md-6">
    <input type="text" class="form-control cls_required auto_complete" id="txt_bmsrno" name="txt_bmsrno" style="text-transform: uppercase;" maxlength="100" />
  </div>
</div>
##################################
sample attachemnts
----------------------------------
<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
  <div class="col-md-4" style="vertical-align: middle;text-align: right;">
    <label class="col-md-12 control-label">Adavance Receipt : <span style='color:red'>*</span></label>
  </div>
  <div class="col-md-6" >
     <div class="col-md-10" style="padding-left:0px;">
      <input type="text" disabled class="col-md-4 form-control cls_required" id="txt_file1" name="txt_file1" style="text-transform: uppercase;" maxlength="100" />
     </div>
     <div class="col-md-2">
       <div class="btn btn-danger btn-file"> <i class="glyphicon glyphicon-folder-open"></i><input name="advance_receipt" id="file-simple" type="file" onchange="loadfile(this,'txt_file1','attach_1');"></div>
     </div>
             <div  class="row" >
                     <img id="attach_1" src="" style="display:none;width:100px;height:100px;border-radius: 10px;padding:3%;border:2px solid #adadad;margin-left: 10%;" />
             </div>
  </div>
</div>
########################################

*/
function auto_complete(){

  $('.auto_complete').each(function(){
 
    $('#load_page').fadeIn('fast');
    var element=this;
        $(this).autocomplete({

          source: function( request, response ) {

            $.ajax({
              url : 'approval-desk-test/ajax/ajax_employee_details.php',
              dataType: "json",
              data: {
                 name_startsWith: request.term,
                 type: 'employee'
              },
              success: function( data ) 
              {
                $('#load_page').fadeOut('fast');
                //$(element).css("background","#adadad");
				if(data=='')
				{
					$(element).val('');
				}
				response( $.map( data, function( item ) {

					return {
					  label: item,
					  value: item
					}
				}));
			  }

            });
          },

          autoFocus: true,
          minLength: 1
        });
      });
  $('#load_page').fadeOut('fast');
 }
 function auto_complete1(){

  $('.auto_complete1').each(function(){
 
    $('#load_page').fadeIn('fast');
    var element=this;
        $(this).autocomplete({

          source: function( request, response ) {

            $.ajax({
              url : 'ajax/ajax_employee_details.php',
              dataType: "json",
              data: {
                 name_startsWith: request.term,
                 type: 'employee'
              },
              success: function( data ) 
              {
                $('#load_page').fadeOut('fast');
                //$(element).css("background","#adadad");
        if(data=='')
        {
          $(element).val('');
        }
        response( $.map( data, function( item ) {

          return {
            label: item,
            value: item
          }
        }));
        }

            });
          },

          autoFocus: true,
          minLength: 1
        });
      });
  $('#load_page').fadeOut('fast');
 }
 function loadfile(ele,name_id,preview_id){
  var name=$(ele).val();
  name=name.slice((Math.max(0, name.lastIndexOf(".")) || Infinity) + 1);
    var reader  = new FileReader();
    reader.onload = function (ele) 
    {
        
        $('#'+preview_id).attr('src',ele.target.result);
        $('#'+preview_id).css('display','block');
    }

  if(name=='jpg' || name=='png' || name=='jpeg' || name=='bmp')
  {
        reader.readAsDataURL(ele.files[0]);
    $('#'+name_id).val($(ele).val());
        //reader.readAsDataURL(ele);
        $('#'+preview_id).css('display','block');
  }
    else if(name == 'pdf' || name=='doc' || name=='docx' )
    {
        $('#'+name_id).val($(ele).val());
        $('#'+preview_id).css('display','none');
    }
  else
  {
    $(ele).val('');
    $.alert({
            title:"<span style='color : orange;'>Warning !</span>",
            content:"Only pdf and documents are Allowed"
        });
  }
}
/*
var dragSrcEl = null;

function handleDragStart(e) {
  // Target (this) element is the source node.
  dragSrcEl = this;
  $(this).css('background','#ce7676');
  //console.log("hi");
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/html', this.outerHTML);

  this.classList.add('dragElem');
}
function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault(); // Necessary. Allows us to drop.
  }
  this.classList.add('over');

  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

  return false;
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
}

function handleDragLeave(e) {
  this.classList.remove('over');
}

function handleDrop(e) {
  // this/e.target is current target element.
  
  //this.classList.remove('dragElem');
  if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  }
  if (dragSrcEl != this) {
    this.parentNode.removeChild(dragSrcEl);
    var dropHTML = e.dataTransfer.getData('text/html');
    this.insertAdjacentHTML('beforebegin',dropHTML);
    var dropElem = this.previousSibling;
    addDnDHandlers(dropElem);
  }
  this.classList.remove('over');
  return false;
}

function handleDragEnd(e) {
  this.classList.remove('over');
  this.classList.remove('dragElem');

}

function addDnDHandlers(elem) {
  elem.addEventListener('dragstart', handleDragStart, false);
  elem.addEventListener('dragenter', handleDragEnter, false)
  elem.addEventListener('dragover', handleDragOver, false);
  elem.addEventListener('dragleave', handleDragLeave, false);
  elem.addEventListener('drop', handleDrop, false);
  elem.addEventListener('dragend', handleDragEnd, false);

}

//////////////////// Drag Usage /////////////////////////////////////////
var cols1 = document.querySelectorAll('#branches .column');
[].forEach.call(cols1, addDnDHandlers);
///////////////////////////////////////////////////////////////////////////*/
