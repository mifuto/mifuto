
showAddEventSection = () =>{
   
    successFn = function(resp)  {
        var Templates = resp.data['Templates'];
        if(Templates.length == 1){
            emptyForm();
            var sd = $("#submitButton").hasClass("d-none");
            var sdu = $("#updateEventButton").hasClass("d-none");
            // alert(sdu);
            if(sd = true){
                $("#submitButton").removeClass("d-none");
            }
            if(sdu == false){
                $("#updateEventButton").addClass("d-none");
            }
            $("#eventListSection").addClass("d-none");
            $('#addEVT').html('Add Event');
            $("#showFolderName").removeClass('d-none');
            $("#signalbmUploadStatus").width('0%');
            $("#signalbmUploadStatus").html('0%');



           
            $('#eventFormSection').removeClass("d-none");
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Active Mail Templates not available ',
            });
        }

    }
    data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":1, "mail_template": 1 };
    
    apiCall(data,successFn);

}

showEventListSection = () =>{
    emptyForm();
    $("#eventListSection").removeClass("d-none");
    $('#eventFormSection').addClass("d-none");
    $("#hiddenEventId").val("");
}

var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

// saveEvents = () => {
//     //alert("efe");
//     return new swal({
//         title: "Are you sure?",
//         text: "Once deleted, you will not be able to recover this imaginary file!",
//         icon: false,
//         buttons: true,
//         dangerMode: true,
//         showCancelButton: true,
//         confirmButtonText: 'Yes'
//       }).then((willDelete) => {
//          alert("sfdsfsd");
       
//    });
// }

$("#loginForm").submit(function(event) {
    event.preventDefault();
    var form = $("#loginForm");
    var formData = new FormData(form[0]);
    console.log(formData);
    formData.append('function', 'User');
    formData.append('method', 'login');
    formData.append('save', "add");
    successFn = function(resp)  {
        console.log(resp.data);
        if(resp.data != "" && resp.data != null){
            
            $('#loginFailedErr').attr("style", "display: none !important");
            window.location.replace("index.php");
        }else{
            $('#loginFailedErr').attr("style", "display: block !important");
        }

    }
    apiCallForm(formData,successFn);
});

$("#addEventForm").submit(function(event) {
        event.preventDefault();
        var hiddenEventId = $("#hiddenEventId").val();


        if(hiddenEventId ==""){
            $("#coverImage").rules("add", {
                required: true,
                messages: {
                  required: "Please select the material type"
                }
            });
        
            $("#albumPdf").rules("add", {
                required: true,
                messages: {
                  required: "Please select the material type"
                }
            });
            // $("#coverImage").removeAttr('name :coverImage');
            // $("#albumPdf").removeAttr('name: albumPdf');
        }else{
            $("#coverImage").rules("add", {
                required: false,
            });
        
            $("#albumPdf").rules("add", {
                required: false,
            });
        }

    }).validate({
        
        submitHandler: function(form) {
            var hiddenEvent = $("#hiddenEventId").val();
            console.log(hiddenEvent)
            if(hiddenEvent == ""){
                saveEvents();
            }else{
                updateEvents();
            }
            
        },
        rules: {
            usersList: {
                required: true
            },
            eventName: {
                required: true
            },
            venue: {
                required: true
            },
            // pageNumber: {
            //     required: true
            // },
            folderName: {
                required: true
            },
            // coverImage:{
            //     required: true
            // },
            // albumPdf: {
            //     required: true
            // },
            albmWidth: {
                required: true
            },
            albmHeight: {
                required: true
            },
            eventdate: {
                required: true
            },
            description: {
                required: true
            },
            description: {
                required: true
            },
            gridRadios1: {
                required: true
            },
            uploadedDate: {
                required: true
            }
        },
        messages: {
            usersList: {
                required: "Please select the User"
            },
            eventName: {
                required: "Please enter the event name"
            },
            venue: {
                required: "Please enter the venue"
            },
            // pageNumber: {
            //     required: "Please enter the page number"
            // },
            folderName: {
                required: "Please enter the folder name"
            },
            // coverImage: {
            //     required: "Please enter the event files"
            // },
            // albumPdf: {
            //     required: "Please enter the event files"
            // },
            albmWidth: {
                required: "Please enter the event files"
            },
            albmHeight: {
                required: "Please enter the event files"
            },
            eventdate: {
                required: "Please enter the event date"
            },
            description: {
                required: "Please enter the description"
            },
            gridRadios1: {
                required: "Please select the album type"
            },
            uploadedDate: {
                required: "Please enter the uploaded date"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
        }
});

saveEvents = () => {

    
    // $("#coverImage").rules("add", {
    //     required: true,
    //     messages: {
    //       required: "Please select the material type"
    //     }
    // });

    // $("#albumPdf").rules("add", {
    //     required: true,
    //     messages: {
    //       required: "Please select the material type"
    //     }
    // });

    $("#submitButton").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    // event.preventDefault();

    var coverImg = $('#coverImage')[0].files[0];
    var albumPdf = $('#albumPdf')[0].files[0];


    var form = $("#addEventForm");
    var formData = new FormData(form[0]);
    formData.append('function', 'OnlineAlbum');
    formData.append('method', 'saveEvents');
    formData.append('save', "add");
    formData.append('coverImage', coverImg);
    formData.append('albumPdf', albumPdf);
    // console.log(formData);
        return new swal({
            title: "Are you sure?",
            text: "You want to save this event",
            icon: false,
            // buttons: true,
            // dangerMode: true,
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((confirm) => {
                // console.log(confirm.isConfirmed);
                if (confirm.isConfirmed) {
                    $.ajax({
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = ((evt.loaded / evt.total) * 100);
                                    $(".progress-bar").width(percentComplete.toFixed(0) + '%');
                                    $(".progress-bar").html(percentComplete.toFixed(0) +'%');
                                }
                            }, false);
                            return xhr;
                        },
                        type: 'POST',
                        url: 'ajaxHandler.php',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend: function(){
                            $(".progress-bar").width('0%');
                            // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                            $('#signalbmUploadStatus').removeClass('d-none');
                        },
                        error:function(){
                            $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                             $("#submitButton").removeClass("d-none");
                                    $("#submitLoadingButton").addClass("d-none");
                        },
                        success: function(resp){
                            // console.log(resp);
                            resp=JSON.parse(resp);
                            if(resp.status == 1){
                                Swal.fire({
                                    icon: 'success',
                                    // title: resp.data,
                                    title: "Album saved successfully",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // $('#uploadForm')[0].reset();
                                emptyForm();
                                getEvents();
                                $("#eventListSection").removeClass("d-none");
                                $('#eventFormSection').addClass("d-none");
                                $("#submitButton").removeClass("d-none");
                                $("#submitLoadingButton").addClass("d-none");
                                // $("#updateEventButton").removeClass("d-none");
                                // $("#submitLoadingButton").addClass("d-none");
                                }else{
                                    Swal.fire({
                                        icon: 'error',
                                        title: resp.data,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $("#submitButton").removeClass("d-none");
                                    $("#submitLoadingButton").addClass("d-none");
                                }
                                
                            }
                    });
                }else{
                    $("#submitButton").removeClass("d-none");
                    $("#submitLoadingButton").addClass("d-none");
                    $("#hiddenEventId").val("");
                }
        });
}

saveEventsOld = () => {
    $("#submitButton").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    // event.preventDefault();
    var coverImg = $('#coverImage')[0].files[0];
    var albumPdf = $('#albumPdf')[0].files[0];
    var form = $("#addEventForm");
    var formData = new FormData(form[0]);
    formData.append('function', 'OnlineAlbum');
    formData.append('method', 'saveEvents');
    formData.append('save', "add");
    formData.append('coverImage', coverImg);
    formData.append('albumPdf', albumPdf);
    // console.log(formData);
        return new swal({
            title: "Are you sure?",
            text: "You want to save this event",
            icon: false,
            // buttons: true,
            // dangerMode: true,
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((confirm) => {
                // console.log(confirm.isConfirmed);
                if (confirm.isConfirmed) {
                    successFn = function(resp)  {
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            emptyForm();
                            getEvents();
                            $("#eventListSection").removeClass("d-none");
                            $('#eventFormSection').addClass("d-none");
                            $("#submitButton").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                            $("#hiddenEventId").val("");
                            
                            // $("#updateEventButton").removeClass("d-none");
                            // $("#submitLoadingButton").addClass("d-none");
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            $("#submitButton").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                            $("#hiddenEventId").val("");
                        }
                    }
                    apiCallForm(formData,successFn);
                }else{
                    $("#submitButton").removeClass("d-none");
                    $("#submitLoadingButton").addClass("d-none");
                    $("#hiddenEventId").val("");
                }
        });
}

emptyForm = () => {
    $("#usersList").val("");
    $("#eventName").val("");
    $("#venue").val("");
    $("#pageNumber").val("");
    $("#description").val("");
    $("#folderName").val("");
    $("#coverImage").val("");
    $("#albumPdf").val("");
    $("#albmWidth").val("");
    $("#albmHeight").val("");
    $("#eventdate").val("");
    $("#uploadedDate").val("");
}

getEvents = () => {

    var sel_user =  $('#sel_user').val();

    successFn = function(resp)  {
        $('#eventListTable').DataTable().destroy();
        var eventList = resp.data;
        // console.log(resp.data);
        // $('#eventListTable').DataTable( { } );
        $('#eventListTable').DataTable({
            "data": eventList,
            "aaSorting": [],
            "columns": [
              { "data": "id",
              
                "render": function ( data, type, full, meta ) {
                    return  meta.row + 1;
                }
              },
              { "data": "firstname" },
              { "data": "event_name" },
              { "data": "venue" },
              { "data": "event_date" },
            //   { "data": "description" },
              
               
               {"data":null,"render":function(item){
                  
                   var description = item.description;
    
                    // Set the maximum length for the text
                    var maxLength = 60;
                
                    // Trim the text and add ellipsis if needed
                    var trimmedText = description.length > maxLength ? description.substring(0, maxLength) + '...' : description;
              
                  return trimmedText;
                
                    
                    }
                },
              
              
              { "data": "album_type", 
                  render: function ( data ) {
                    //console.log(data);
                    var albumType = "";
                    if(data == 1){
                        albumType = "Portraits Album";
                    }else if(data == 2){
                        albumType = "Landscape album";
                    }else{
                        albumType = "N/A";
                    }
                    return albumType;
                }
              },
                {"data":null,"render":function(item){
                   var $ds = item.commentCounts+" Cmts";
                   $ds += "<br>"+item.shareCounts+" Shares";
                   $ds += "<br>"+item.viewCounts+" Views";
                return $ds;
                    
                    }
                },
                 { "data": "view_token" },
              { "data": "upload_date" },
              { "data": "expiry_date" },
              {"data":null,"render":function(item){
                return '<span class="badge bg-info text-dark" onclick="editEvent('+item.id+');" style="cursor:pointer">edit</span><span class="badge bg-danger" onclick="deleteEvent('+item.id+');" style="cursor:pointer">delete</span><span class="badge bg-primary text-white" onclick="extendEventDate('+item.id+',`'+item.expiry_date+'`);" style="cursor:pointer">extend date</span><span class="badge bg-info text-dark" onclick="PreviewEvent('+item.id+');" style="cursor:pointer">Preview</span>';
                    
                    }
                },
             
            ]
        });
    }
    data = { "function": 'OnlineAlbum',"method": "getEventList" ,"sel_user":sel_user };
    
    apiCall(data,successFn);
}

extendEventDate = (id,date) => {

    successFn = function(resp)  {
        var Templates = resp.data['Templates'];

        if(Templates.length == 1){
            $("#ExpiryDateVal").val(date);
            $("#extendEventDateid").val(id);
            $("#extendEventDateModal").modal('show');


        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Active Mail Templates not available ',
            });
        }
       

    }
    data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":1, "mail_template": 13 };
    
    apiCall(data,successFn);

   
}

saveExtendEventDate = () => {
    var date = $("#ExpiryDateVal").val();
    var id = $("#extendEventDateid").val();

    successFn = function(resp)  {
        if(resp.status == 1){
            Swal.fire({
                icon: 'success',
                title: resp.data,
                showConfirmButton: false,
                timer: 1500
            });
            $("#extendEventDateModal").modal('hide');
            getEvents();
        
            
        }else{
            Swal.fire({
                icon: 'error',
                title: resp.data,
                showConfirmButton: false,
                timer: 1500
            });
        }
       
    }
    data = { "function": 'OnlineAlbum',"method": "saveExtendEventDate","date": date,"id": id };
    
    apiCall(data,successFn);

    
}

deleteEvent = (id) => {

    successFn = function(resp)  {
        var Templates = resp.data['Templates'];

        if(Templates.length == 1){
            
    
     var form = $("#addEventForm");
     var formData = new FormData(form[0]);
     formData.append('function', 'OnlineAlbum');
     formData.append('method', 'deleteEvents');
     formData.append('save', "update");
     formData.append('eventId', id);
     // formData.append('albumPdf', albumPdf);
     // console.log(formData);
         return new swal({
             title: "Are you sure?",
             text: "You want to delete this event",
             icon: false,
             // buttons: true,
             // dangerMode: true,
             showCancelButton: true,
             confirmButtonText: 'Yes'
             }).then((confirm) => {
                 // console.log(confirm.isConfirmed);
                 if (confirm.isConfirmed) {
                     successFn = function(resp)  {
                         if(resp.status == 1){
                             Swal.fire({
                                 icon: 'success',
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });
                             emptyForm();
                             getEvents();
                             
                         }else{
                             Swal.fire({
                                 icon: 'error',
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });
                         }
                     }
                     apiCallForm(formData,successFn);
                 }else{
                     $("#updateEventButton").removeClass("d-none");
                     $("#submitLoadingButton").addClass("d-none");
                 }
         });


        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Active Mail Templates not available ',
            });
        }
       

    }
    data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":1, "mail_template": 4 };
    
    apiCall(data,successFn);



}

editEvent = (id) => {
    // console.log(id);
    $("#signalbmUploadStatus").width('0%');
    $("#signalbmUploadStatus").html('0%');
    $('#addEVT').html('Edit Event');
    $("#hiddenEventId").val(id);
    $("#eventListSection").addClass("d-none");
    $('#eventFormSection').removeClass("d-none");
    $("#submitButton").addClass("d-none");
    $("#updateEventButton").removeClass("d-none");
    
    
    successFn = function(resp)  {
        // $('#eventListTable').DataTable().destroy();
        var eventList = resp.data;
        console.log(eventList);
        console.log(eventList[0]['user_id']);
        // name="gridRadios"
        $('input[name="gridRadios"]').attr("checked", false);

        $("#usersList").val(eventList[0]['user_id']).trigger('change');
        $("#usersList").prop("disabled", true);
        $("#showFolderName").addClass('d-none');


        $("#eventName").val(eventList[0]['event_name']);
        $("#venue").val(eventList[0]['venue']);
        // $("#pageNumber").val(eventList[0]['page_number']);
        $("#description").val(eventList[0]['description']);
        $("#folderName").val(eventList[0]['uploader_folder']);
        if(eventList[0]['album_type'] == "1"){
            $('input[id="gridRadios1"]').attr("checked", true);
        }else if(eventList[0]['album_type'] == "2"){
            $('input[id="gridRadios2"]').attr("checked", true);
        }
        $("#coverImage").val("");
        $("#albumPdf").val("");
        // $("#albmWidth").val(eventList[0]['album_width']);
        // $("#albmHeight").val(eventList[0]['album_height']);
        $("#eventdate").val(eventList[0]['event_date']);
        $("#uploadedDate").val(eventList[0]['upload_date']);
        // $('#eventListTable').DataTable( { } );
    }
    data = { "function": 'OnlineAlbum',"method": "editEvent", "eventId" : id };
    
    apiCall(data,successFn);
}

PreviewEvent = (id) => {
   var albumId = Base64.encode(Date.now()+"_"+id ); 
   openPopup('/online_album_sa.php?pId='+albumId, 1200, 800);

}

openPopup = (url, width, height) => {
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;
  
    var options = 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',resizable=yes,scrollbars=yes';
  
    window.open(url, '_blank', options);
}

$("#updateEventButton").submit(function(event) {
    event.preventDefault();
}).validate({
    submitHandler: function(form) {
        console.log(form[0]);
        updateEvents();
    },
    rules: {
        usersList: {
            required: true
        },
        eventName: {
            required: true
        },
        venue: {
            required: true
        },
        // pageNumber: {
        //     required: true
        // },
        // folderName: {
        //     required: true
        // }, 
        coverImage: {
            required: false
        },
        albumPdf: {
            required: false
        },
        // albmWidth: {
        //     required: true
        // },
        // albmHeight: {
        //     required: true
        // },
        eventdate: {
            required: true
        },
        description: {
            required: true
        },
        description: {
            required: true
        },
        gridRadios1: {
            required: true
        },
        uploadedDate: {
            required: true
        }
    },
    messages: {
        usersList: {
            required: "Please select the User"
        },
        eventName: {
            required: "Please enter the event name"
        },
        venue: {
            required: "Please enter the venue"
        },
        // pageNumber: {
        //     required: "Please enter the page number"
        // },
        // folderName: {
        //     required: "Please enter the folder name"
        // },
        // albmWidth: {
        //     required: "Please enter the event files"
        // },
        // albmHeight: {
        //     required: "Please enter the event files"
        // },
        eventdate: {
            required: "Please enter the event date"
        },
        description: {
            required: "Please enter the description"
        },
        gridRadios1: {
            required: "Please select the album type"
        },
        uploadedDate: {
            required: "Please enter the uploaded date"
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');
    element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
    $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass('is-invalid');
    }
});

updateEvents = () => {



        // $("#coverImage").addAttr('name :coverImage');
        $("#albumPdf").removeAttr('required');
        $("#usersList").prop("disabled", false);
        
   
    
    $("#updateEventButton").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    // event.preventDefault();
    // var coverImg = $('#coverImage')[0].files[0];
    // var albumPdf = $('#albumPdf')[0].files[0];
    var hiddenEventId = $("#hiddenEventId").val();
    var form = $("#addEventForm");
    var formData = new FormData(form[0]);
    formData.append('function', 'OnlineAlbum');
    formData.append('method', 'updateEvents');
    formData.append('save', "update");
    formData.append('eventId', hiddenEventId);
    // formData.append('albumPdf', albumPdf);
    // console.log(formData);
        return new swal({
            title: "Are you sure?",
            text: "You want to save this event",
            icon: false,
            // buttons: true,
            // dangerMode: true,
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((confirm) => {
                // console.log(confirm.isConfirmed);
                if (confirm.isConfirmed) {
                    
                    
                    successFn = function(resp)  {
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            emptyForm();
                            getEvents();
                            $("#eventListSection").removeClass("d-none");
                            $('#eventFormSection').addClass("d-none");
                            $("#updateEventButton").addClass("d-none");
                            $("#submitButton").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $("#updateEventButton").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                        }
                    }
                    apiCallForm(formData,successFn);
                    
                    
                    
                    
                    
                    
                }else{
                    $("#updateEventButton").removeClass("d-none");
                    $("#submitLoadingButton").addClass("d-none");
                    $("#usersList").prop("disabled", true);
                }
        });
}


getcardusers = (selectId) => {

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select User</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);

    }
    data = { "function": 'AlbumSubscription',"method": "getcardusers" };
    
    apiCall(data,successFn);
    
}

getusers = (selectId) => {

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select User</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);

    }
    data = { "function": 'OnlineAlbum',"method": "getUsersList" };
    
    apiCall(data,successFn);
    
}

getuserssimage = (selectId) => {
    $('#subuserDisDiv').addClass('d-none');
      $('#tableDiv').addClass('d-none');
        $('#frstMess').removeClass('d-none');
        $('#finishDiv').addClass('d-none');
        $('#resetDiv').addClass('d-none');
         $('#eventListTab').html('');

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select User</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
      getEventSubUserList("albumSubUserList");
      getEventUserProjectList();

    }
    data = { "function": 'OnlineAlbum',"method": "getUsersList" };
    
    apiCall(data,successFn);
    
}

getusersall = (selectId) => {

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select User</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);

    }
    data = { "function": 'OnlineAlbum',"method": "getUsersAllList" };
    
    apiCall(data,successFn);
    
}

getstaffusers = (selectId) => {
   
    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select Author</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);

    }
    data = { "function": 'OnlineAlbum',"method": "getstaffusers" };
    
    apiCall(data,successFn);
    
}

$("#signAlbumUsersList").on('change',function(event) {
    var selectedUserId = $("#signAlbumUsersList").val();
    if(selectedUserId == ""){
        $("#signAlbumUsersListErr").html("Plese select a user !.");
    }else{
        $("#signAlbumUsersListErr").html("");
    }
    getSignatureALbumProjectList();
    
    backToprojList();
    
    
});

$("#creatNewProjBtn").on('click',function(event) {
    
    var selectedUserId = $("#signAlbumUsersList").val();
    $("#sigAlbmProjName").val('');

    // $('#createSigAlbmProjectForm').get(0).reset();
    // $("form#createSigAlbmProjectForm").validator("destroy");
    if(selectedUserId == ""){
        // alert(111);
        $("#signAlbumUsersListErr").html("Plese select a user !.");
        return false;
    }
    var selectedUserId = $("#signAlbumUsersList").val();
    $("#selectedProjId").val("");
    $("#createProjrctSubmit").html("Create");
    $("#selectedProjUserId").val(selectedUserId);
    $("#coverImageUploadStatus").width('0%');
    $("#coverImageUploadStatus").html('0%');
    $('.ri-close-circle-line').click();
    $("#createSigAlbmProjectForm").removeClass('was-validated');
  
    successFn = function(resp)  {
        var Templates = resp.data['Templates'];

        if(Templates.length == 1){
            $("#signatureAlbumCover").val(null);
            $('#signatureAlbumCover').val('');
         
            $("#createProjectModal").modal('show');
            
            $('#uploadStatus').html('');
            $('#uploadMoreStatus').html('');


        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Active Mail Templates not available ',
            });
        }
       

    }
    data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":2, "mail_template": 5 };
    
    apiCall(data,successFn);


});

$('#signAlbumUsersList').on('change', function() {
    // alert( this.value );
    // getSignatureALbumList();
    
    getSignatureALbumProjectList();
    backToprojList();
});

function generateRandomCode() {
  // Generate a random number between 0 and 999999 (inclusive)
  const randomNumber = Math.floor(Math.random() * 1000000);

  // Convert the random number to a 6-digit code by adding leading zeros
  const sixDigitCode = randomNumber.toString().padStart(6, '0');

  return sixDigitCode;
}

$("#createSigAlbmProjectForm").submit(function(event) {

    event.preventDefault();
    
    // $("#createProjrctSubmit").addClass("d-none");
    // $("#createProjrctSubmitdingButton").removeClass("d-none");
    var form = $("#createSigAlbmProjectForm");
    var formData = new FormData(form[0]);
    var coverFile = $('#signatureAlbumCover')[0].files;
    var selectedProjId = $("#selectedProjId").val();
    // console.log($('#sigAlbmProjName').val());
    
    if($('#sigAlbmProjName').val() == ""){
        return false;
    }
    var msg1= "";
    var msg2= "";
    if(selectedProjId == ""){
        msg1="You want to save this project";
        msg2="Project saved successfully";
        if(coverFile.length == 0){
            $("#signatureAlbumCoverErr").html("Plese upload the cover image!.");
            return false;
        }else if(coverFile.length > 1){
            $("#signatureAlbumCoverErr").html("Plese You can upload only one image !.");
            return false;
        }else{
            $("#signatureAlbumCoverErr").html("");
        }
    }else{
        msg1="You want to update this project";
        msg2="Project updated successfully";
        if(coverFile.length != 0){
            if(coverFile.length > 1){
                $("#signatureAlbumCoverErr").html("Plese You can upload only one image !.");
                return false;
            }else{
                $("#signatureAlbumCoverErr").html("");
            }
        }
    }

    var token = generateRandomCode();  
    
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'saveProjects');
    formData.append('save', "add");
    formData.append('token', token);
    // formData.append('signatureAlbumFiles', zipFile);

    return new swal({
        title: "Are you sure?",
        text: msg1,
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $("#coverImageUploadStatus").width(percentComplete.toFixed(0) + '%');
                                $("#coverImageUploadStatus").html(percentComplete.toFixed(0) +'%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: 'ajaxHandler.php',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $("#coverImageUploadStatus").width('0%');
                        $('#coverImageUploadStatus').removeClass('d-none');
                    },
                    error:function(){
                        $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                        $("#createProjrctSubmit").removeClass("d-none");
                            $("#createProjrctSubmitdingButton").addClass("d-none");
                    },
                    success: function(resp){
                        resp=JSON.parse(resp);
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                title: msg2,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $("#createProjectModal").modal('hide');
                            $('.ri-close-circle-line').click();
                            $("#sigAlbmProjName").val("");
                            $("#createProjrctSubmit").removeClass("d-none");
                            $("#createProjrctSubmitdingButton").addClass("d-none");

                            // setTimeout(function(){
                            //     history.go(0);
                            // },500)
                            getSignatureALbumProjectList();
                            backToprojList();
                            
                            
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $("#createProjrctSubmit").removeClass("d-none");
                            $("#createProjrctSubmitdingButton").addClass("d-none");
                        }
                        
                    }
                });
            }else{
                $("#createProjrctSubmit").removeClass("d-none");
                $("#createProjrctSubmitdingButton").addClass("d-none");
            }
        });


})


extendSADate = (id,date) => {
    successFn = function(resp)  {
        var Templates = resp.data['Templates'];

        if(Templates.length == 1){
            $("#SAExpiryDateVal").val(date);
            $("#SAextendEventDateid").val(id);
            $("#SAextendEventDateModal").modal('show');


        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Active Mail Templates not available ',
            });
        }
       

    }
    data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":2, "mail_template": 12 };
    
    apiCall(data,successFn);

}

saveSAExtendEventDate = () => {
    var date = $("#SAExpiryDateVal").val();
    var id = $("#SAextendEventDateid").val();

    successFn = function(resp)  {
        if(resp.status == 1){
            Swal.fire({
                icon: 'success',
                title: resp.data,
                showConfirmButton: false,
                timer: 1500
            });
            $("#SAextendEventDateModal").modal('hide');
            getSignatureALbumProjectList();

            
        }else{
            Swal.fire({
                icon: 'error',
                title: resp.data,
                showConfirmButton: false,
                timer: 1500
            });
        }
       
    }
    data = { "function": 'SignatureAlbum',"method": "saveExtendSADate","date": date,"id": id };
    
    apiCall(data,successFn);

    
}




getSignatureALbumProjectList = () => {

    var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "June",
    "July", "Aug", "Sept", "Oct", "Nov", "Dec" ];
    

    var userId = $('#signAlbumUsersList').val();
    if(userId == ""){
        $("#eventAddBtnDiv").html("");
        $("#eventAddBtnDiv2").html("");
        $("#signatureAlbumProjContent").html("");
        $("#signatureAlbumProjEmptyDataForUser").removeClass("d-none");
        return false;
    }
    successFn = function(resp)  {
        var projects = resp["data"];       
        $("#signatureAlbumProjEmptyData").addClass("d-none");
        $("#signatureAlbumProjEmptyDataForUser").addClass("d-none");
        if(projects != ""){
            var html = "";
            html += '<div class="row">';
            
            $.each(projects, function(key,value) {

                var date = new Date(value.planExpDate);

                // Get year, month, and day part from the date
                var year = date.toLocaleString("default", { year: "numeric" });
                var month = date.toLocaleString("default", { month: "numeric" });
                var day = date.toLocaleString("default", { day: "2-digit" });

                var formattedDate = day+ ' '+ monthNames[month-1] + ' '+ year;

              
                html += '<div class="col-sm-3">';
                html += '<div class="card">';
                html += '<img src="'+value.cover_img_path+'" class="card-img-top" alt="...">';
                html += '<div class="card-body" style="padding: 0 10px 10px 10px;">';
                html += '<h5 class="card-title">'+value.project_name+'</h5>';

                html += '<div class="post-opt fl-wrap">';
                html += '<ul>';
                html += '<li><i class="fal fa-comments-alt"></i> '+value.eventsCount+' Events</li>';
                html += '<li><i class="fal fa-comments-alt"></i> '+value.imageCount+' Images</li>';
                html += '<li><i class="fal fa-comments-alt"></i> '+value.commentCount+' comments</li>';
               
                html += '<li><span><i class="fal fa-eye"></i> '+value.viewCounts+' view</span></li>';
                 html += '<li><i class="fal fa-comments-alt"></i> '+value.shareCounts+' shares</li>';
                 html += '<li><i class="fal fa-comments-alt"></i> View PIN : '+value.view_token+'</li>';
                
                    var currentdateVal = Base64.encode(Date.now()+"_"+value.id );  
              
                html += '<li><span> <a style="position: unset !important;" target="_blank" href="/signature_album_sa.php?pId='+currentdateVal+'" >Preview</a></span></li>';
                
                
                html += '</ul>';
                html += '</div>';
                
             


                html += '<span class="text-dark" style="font-size: 13px;">Expiry Date: <span class="text-success"> '+formattedDate+'</span></span>';
                
               
                
                html += '<div class="card-text">';
                

                html += ' <a class="nav-link nav-profile d-flex align-items-center btPpop" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>';

                html += '<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">';

                html += '<li><a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="editProject('+value.id+')"><i class="bx bxs-edit"></i><span>Edit Project</span></a></li>';
                html += '<li><hr class="dropdown-divider"></li>';

                html += '<li><a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="viewProjectEvents('+value.id+', '+userId+')"><i class="bi bi-eye"></i><span>View Project</span></a></li>';

                html += '<li><hr class="dropdown-divider"></li>';

                html += '<li><a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="extendSADate('+value.id+',`'+value.planExpDate+'`)"><i class="bi bi-calendar2-plus"></i><span>Extend </span></a></li>';

                html += '<li><hr class="dropdown-divider"></li>';

                html += '<li><a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="deleteProject('+value.id+')"><i class="bi bi-trash"></i><span>Delete Project</span></a></li>';
                html += '</ul>';

                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            })
            html += '</div>';
            $("#signatureAlbumProjContent").html(html)
            setTimeout(function(){
                // $(".proj_links").tooltip();
                $('[data-toggle="popover"]').popover({
                    html: true,
                    toggleEnabled: true,
                    content: function() {
                        return $('#popover-content').html();
                    }
              });
            }, 300)
             
        }else{
            $("#signatureAlbumProjContent").html("");
            $("#signatureAlbumProjEmptyDataForUser").removeClass("d-none");
        }
       
    }
    data = { "function": 'SignatureAlbum',"method": "getSignatureAlbumsProjects", 'userId': userId };

    apiCall(data,successFn);
}

editProject = (projid) => {
    // alert('it works!'+projid);
    successFn = function(resp)  {
        console.log(resp.data);
        var data = resp.data;
        var projId = data[0].id;
        $("#sigAlbmProjName").val(data[0].project_name);
        $("#selectedProjId").val(projId);
        $("#selectedProjUserId").val(data[0].user_id);
        $("#createProjrctSubmit").html("Update");
        $('.ri-close-circle-line').click();
        $("#createSigAlbmProjectForm").removeClass('was-validated');
        $("#createProjectModal").modal('show');
        
        $('#uploadStatus').html('');
        $('#uploadMoreStatus').html('');
    }
    data = { "function": 'SignatureAlbum',"method": "editSignatureAlbumsProjects", 'projId': projid};

    apiCall(data,successFn);
    // $("#createProjectModal").modal('show');

}

selMulImg = () => {
    $('#showMulDltBtn').removeClass('d-none');
    $('#showMulDltSelBtn').addClass('d-none');
  $('[name="multipleImgSelectionChk"]').removeClass('d-none');
  $('#cancelMulDltSelBtn').removeClass('d-none');
    
    
}

cancelMulImg = () => {
    $('#showMulDltBtn').addClass('d-none');
    $('#showMulDltSelBtn').removeClass('d-none');
  $('[name="multipleImgSelectionChk"]').addClass('d-none');
  $('#cancelMulDltSelBtn').addClass('d-none');
    
    
}


delMulImgs = (projid, userId) => {
    
    
     var checkedValues = [];

    // Find all checkboxes with the name "multipleImgSelectionChk" that are checked
    $('input[name="multipleImgSelectionChk"]:checked').each(function() {
      // Add the value of each checked checkbox to the array
      checkedValues.push($(this).val());
    });
    
    if(checkedValues == ""){
         Swal.fire({
            icon: 'error',
            title: "Please select atleast one image",
            showConfirmButton: false,
            timer: 1500
        });
        
        return false;
    }
    
    
     return new swal({
        title: "Are you sure?",
        text: "You want to delete this images",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                successFn = function(resp)  {
                    if(resp.status == 1){
                        Swal.fire({
                            icon: 'success',
                            title: "Successfully deleted images",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                            
                        $('#showMulDltBtn').addClass('d-none');
                        $('#showMulDltSelBtn').removeClass('d-none');
                        $('#cancelMulDltSelBtn').addClass('d-none');
                        $('#cancelMulDltSelBtn').addClass('d-none');
                        // viewProjectEvents(projid, userId);
                        
                        
                         var folder = $('#sel_folder').val();
                        var userId = $('#sel_userId').val();
                        var folder_name = $('#sel_folder_name').val();
                            var albumId = $('#sel_albumId').val();
                            
                            getAlbumFiles(folder, userId, albumId, folder_name);

                       
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: "Failed to delete images",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
                data = { "function": 'SignatureAlbum',"method": "deleteMultipleImageFromAlbum", 'image': checkedValues };
                apiCall(data,successFn);
            }
    });
    
  
}

viewProjectEvents = (projid, userId) => {
    $("#signatureAlbumProjContent").addClass("d-none");
    $("#signatureAlbumProjEventContent").removeClass("d-none");
    $("#card_main_title").html("Events");
    $("#eventAddBtnDiv").removeClass("d-none");
    $("#eventAddBtnDiv2").removeClass("d-none");
    
    $('#sel_project_id').val(projid);
    
    $("#eventAddBtnDiv").html('<button type="button" class="btn btn-primary float-end" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new event" onclick="openCreateNewEventModal('+projid+','+userId+' )"> Create New Event </button>');
    
       var currentdateVal = Base64.encode(Date.now()+"_"+projid );  
            
    
    $("#eventAddBtnDiv2").html('<button type="button" class="btn btn-danger  btn-sm" onclick="backToprojList('+projid+','+userId+' )"> Back </button> <a class="btn btn-primary  btn-sm" target="_blank" href="/signature_album_sa.php?pId='+currentdateVal+'" > Preview </a> <button id="showMulDltSelBtn" type="button" class="btn btn-warning  btn-sm" onclick="selMulImg()"> Select multiple images </button> <button id="showMulDltBtn" type="button" class="btn btn-danger  btn-sm d-none" onclick="delMulImgs('+projid+','+userId+')"> Delete selected images </button>  <button id="cancelMulDltSelBtn" type="button" class="btn btn-warning  btn-sm d-none" onclick="cancelMulImg()"> Cancel selecting images </button> <button id="cancelMulDltSelBtn" type="button" class="btn btn-light  btn-sm " onclick="masonryInitialize();"> Arrange image </button><hr>');

    successFn = function(resp)  {
    
        var events = resp["data"];
        var tabsTitle = "";
        var tabContents = "";
        var count = 0; 
        // console.log(events);
          if(events != ""){
              $("#ProjEventEmptyData").addClass("d-none");
              $.each(events, function(key,value) {
                  
                  var active = "";
                  var tabTactive = "";
                  var folder_name_str = "'"+value.folder_name+"'";
                  var folder_name_str = "'"+value.file_folder+"'";
                  if(count == 0){
                      active = "show active";
                      tabTactive = "show active";
                      getAlbumFiles(value.file_folder, value.user_id, value.id, value.folder_name);
                  }
                  var file_folder = value.file_folder;
                  var valuefolder_name = value.folder_name.replace(/\s/g,'');
                  
                  valuefolder_name = Base64.encode(valuefolder_name );  
                  valuefolder_name = valuefolder_name.replace(/[^a-zA-Z0-9]/g, '');

                  tabsTitle += '<li class="nav-item" role="presentation">';
                  tabsTitle += '<div class="nav-link '+active+'" id="'+valuefolder_name+'-tab" data-bs-toggle="tab" data-bs-target="#'+valuefolder_name+'" role="tab" aria-controls="'+valuefolder_name+'" aria-selected="true">';
                  
                  if(value.status == 1){
                    tabsTitle += '<a href="javascript:void(0)" onclick="getAlbumFiles(\''+file_folder+'\', '+value.user_id+', '+value.id+', \''+value.folder_name+'\')" style="color:#464857">'+value.folder_name+'</a>';
                  }else{
                         tabsTitle += '<a href="javascript:void(0)" onclick="getAlbumFiles(\''+file_folder+'\', '+value.user_id+', '+value.id+', \''+value.folder_name+'\')" style="color:red">'+value.folder_name+'</a>';
                  }
                  
                  tabsTitle += '<a class="createdDiv" href="javascript:void(0)"  onclick="openSignaAlbmImageUploadModal(\''+value.id+'\', '+value.user_id+', \''+value.folder_name+'\', \''+value.file_folder+'\')"style="margin-left: 20px; color:#0d6efd" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Add images to album"><i class="ri-image-add-fill"></i></a>';
                  
                  tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="deleteSignAlbum(\''+value.id+'\', '+value.user_id+', '+value.project_folder_id+')" style="margin-left: 10px; color:#920404" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete album"><i class="ri-delete-bin-3-line"></i></a>';
                  
               
                  if(value.status == 1){
                        tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="setActiveInactive(0,'+value.id+','+projid+','+userId+');" style="margin-left: 10px; color:black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click to hide event"><i class="bi bi-eye"></i></a>';
                  
                  }else{
                        tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="setActiveInactive(1,'+value.id+','+projid+','+userId+');" style="margin-left: 10px; color:black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click to show event"><i class="bi bi-eye-slash"></i></a>';
                  
                  }
                  
                  
                  if(value.imageSel == 1){
                        tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="setActiveInactiveSelecting(0,'+value.id+','+projid+','+userId+');" style="margin-left: 10px; color:black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click to disable image selecting"><input id="imgselChk" type="checkbox" checked disable></a>';
                  
                  }else{
                        tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="setActiveInactiveSelecting(1,'+value.id+','+projid+','+userId+');" style="margin-left: 10px; color:black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click to enable image selecting"><input id="imgselChk" type="checkbox" disable></a>';
                  
                  }
                  
                  tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="changeSACoverImage('+value.id+','+projid+');" style="margin-left: 10px; color:black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click to change cover image"><i class="bi bi-pencil-square"></i></a>';
                  
                
                  tabsTitle += '</div></li>';
  
                  tabContents += '<div class="tab-pane row fade '+tabTactive+'" role="tabpanel" id="'+valuefolder_name+'"></div>';
                  count++;
              });
          }else{
              $("#signatureAlbumEmptyDataForUser").removeClass("d-none");
              $("#ProjEventEmptyData").removeClass("d-none");
          }
  
        $("#signatureAlbumEventTabs").html(tabsTitle);
          //   setTimeout(function(){
        $("#signatureAlbumProjEvntTabContent").html(tabContents);
          // }, 2000);
         
        
          
      }
    
    data = { "function": 'SignatureAlbum',"method": "getProjectEvents", 'projId': projid , 'addview':0,"userIdVal":"" };

    apiCall(data,successFn);
}


setActiveInactive = (status,evtId,projid,userId) => { 
     if(status == 1){
           var sts = "show";
       }else{
           var sts = "hide";
       }
  
    return new swal({
            title: "Are you sure?",
            text: "You want to "+sts+" this event",
            icon: false,
            // buttons: true,
            // dangerMode: true,
            confirmButtonText: 'Yes',
            showCancelButton: true
            }).then((confirm) => {
                  
                   if (confirm.isConfirmed) {
                      
                      
                         successFn = function(resp)  {
                            // console.log("rrerere");
                            if(resp.status == 1){
                               
                                swal.fire({
                                    icon: 'success',
                                    title: "success",
                                    text: "Successfully "+sts+" event",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                viewProjectEvents(projid, userId);
        
                            }else{
                               
                            }
                        }
                        errorFn = function(resp){
                            console.log(resp);
                        }
        
                        data = { "function": 'SignatureAlbum',"method": "hideEvents", 'status': status , 'evtId':evtId};
            
                        apiCall(data,successFn);
                
                
                  
                    }else{
                        console.log("sdsds");
                    }
                   
   
    
                
            });
    
    
   
}



changeSACoverImage = (evtId,projid) => { 
    
     $('#submitLoadingButton13').addClass('d-none');
        $("#submitButton13").removeClass("d-none");
       
        var progressBar = document.getElementById("progress-bar99");
        
    // Set the width of the progress bar to 0%
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
    
    $("#submitButton13").removeClass("d-none");
    $("#uploadLogoFiles").val("");
    $('#uploadLogoFiles').val(null);
    
    $("#chngeImgevtId").val(evtId);
    $("#chngeImgprojid").val(projid);
    
    $("#uploadLogoFilesErr").html("");
    
   $('.ri-close-circle-line').click();
    
    
    $("#changeCoverModal").modal('show');
    
}


function uploadCompanyLogoNow(){
    
    var evtId = $("#chngeImgevtId").val();
    var projid = $("#chngeImgprojid").val();
  
     
     $("#uploadLogoFilesErr").html("");
     
     var files = document.getElementById("uploadLogoFiles").files;
     if (files.length > 0) {
         
        let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      
        formData.append('images[]', file);
        formData.append('evtId', evtId);
        formData.append('projid', projid);
        
        var reader = new FileReader();
        reader.onload = function (e) {
           
            // Upload the image using AJAX
            $.ajax({
                xhr: function() {
                  var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = ((evt.loaded / evt.total) * 100);
                            // Update the ID in the selector to match the HTML element ID
                            $("#progress-bar99").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar99").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/changeSACoverImage.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar99").width('0%');
                    // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                    $('#signalbmEventUploadStatus').removeClass('d-none');
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    
                     swal.fire({
                                    icon: 'success',
                                    title: "success",
                                    text: "Successfully change cover image",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                
                                 $("#changeCoverModal").modal('hide');
                 
                 
                    console.log('Image uploaded:', response);
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
                    $("#submitButton13").removeClass("d-none");
                    $("#submitLoadingButton13").addClass("d-none");
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
     }else{
        $("#uploadLogoFilesErr").html("Please upload the image!.");
        $("#submitButton13").removeClass("d-none");
        $("#submitLoadingButton13").addClass("d-none");
        return false;
    }
     
     
    
     
 }
 






setActiveInactiveSelecting = (status,evtId,projid,userId) => { 
     if(status == 1){
           var sts = "enable image selecting";
       }else{
           var sts = "disable image selecting";
       }
  
    return new swal({
            title: "Are you sure?",
            text: "You want to "+sts,
            icon: false,
            // buttons: true,
            // dangerMode: true,
            confirmButtonText: 'Yes',
            showCancelButton: true
            }).then((confirm) => {
                  
                   if (confirm.isConfirmed) {
                      
                      
                         successFn = function(resp)  {
                            // console.log("rrerere");
                            if(resp.status == 1){
                               
                                swal.fire({
                                    icon: 'success',
                                    title: "success",
                                    text: "Successfully "+sts,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                viewProjectEvents(projid, userId);
        
                            }else{
                               
                            }
                        }
                        errorFn = function(resp){
                            console.log(resp);
                        }
        
                        data = { "function": 'SignatureAlbum',"method": "enableDisEvent", 'status': status , 'evtId':evtId};
            
                        apiCall(data,successFn);
                
                
                  
                    }else{
                        console.log("sdsds");
                         if(status == 0){
                               $('#imgselChk').prop('checked', true) ;
                           }else{
                               $('#imgselChk').prop('checked', false) ;
                           }
                        
                    }
                   
   
    
                
            });
    
    
   
}


deleteProject = (projid) => {
// alert(projid);

successFn = function(resp)  {
    var Templates = resp.data['Templates'];

    if(Templates.length == 1){

        
    var formData = new FormData();
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'deleteProject');
    formData.append('save', "update");
    formData.append('projid', projid);
    
    return new swal({
        title: "Are you sure?",
        text: "You want to delete this project",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                successFn = function(resp)  {
                    if(resp.status == 1){
                        Swal.fire({
                            icon: 'success',
                            title: resp.data,
                            showConfirmButton: false,
                            timer: 1500
                        });

                       // getSignatureALbumList();
                       getSignatureALbumProjectList()
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: resp.data,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
                // data = { "function": 'SignatureAlbum',"method": "deleteProject", 'projid': projid };
                apiCallForm(formData,successFn);
            }
    });
       

    }else{
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Active Mail Templates not available ',
        });
    }
   

}
data = { "function": 'EmailTemplates',"method": "getMailTemplates" , "mail_type":2, "mail_template": 8 };

apiCall(data,successFn);




}

openCreateNewEventModal = (projid, userId) => {
    $("#selectedEventUserId").val(userId);
    $("#selectedProjecEventtId").val(projid);
    $('#createSigAlbmEventForm').removeClass('was-validated');
    $('.ri-close-circle-line').click();
    $("#signalbmEventUploadStatus").width('0%');
    $("#signalbmEventUploadStatus").html('0%');
    $("#createEventModal").modal('show');
    
    $('#uploadStatus').html('');
    $('#uploadMoreStatus').html('');

}

backToprojList = () => {
    $("#signatureAlbumProjContent").removeClass("d-none");
    $("#signatureAlbumProjEventContent").addClass("d-none");
    $("#card_main_title").html("Projects");
    $("#eventAddBtnDiv").addClass("d-none");
    $("#eventAddBtnDiv2").addClass("d-none");
    
}

$("#createSigAlbmEventForm").submit(function(event) {
    
  
    event.preventDefault();
    // $("#createEventSubmit").addClass("d-none");
    // $("#createEventSubmitLoadingButton").removeClass("d-none");
    var form = $("#createSigAlbmEventForm");
    var formData = new FormData(form[0]);
    var eventFile = $('#signatureAlbumEventFiles')[0].files;
    var eventCoverFile = $('#EventCoverImgFile')[0].files;

    if(eventCoverFile.length == 0){
        $("#EventCoverImgFilerr").html("Plese upload the cover image!.");
        return false;
    }else if(eventCoverFile.length > 1){
        $("#EventCoverImgFilerr").html("Plese You can upload only one image !.");
        return false;
    }else{
        $("#EventCoverImgFilerr").html("");
    }
    

    // if(eventFile.length == 0){
    //     $("#signatureAlbumEventFilesErr").html("Plese upload the event images (zip or images)!.");
    //     return false;
    // }else{
    //     $("#signatureAlbumEventFilesErr").html("");
    // }
    console.log(eventFile);
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'saveSignatureAlbum');
    formData.append('save', "add");
    // formData.append('signatureAlbumFiles', zipFile);
// return false;
            return new swal({
                title: "Are you sure?",
                text: "You want to save this event",
                icon: false,
                // buttons: true,
                // dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Yes'
                }).then((confirm) => {
                    // console.log(confirm.isConfirmed);
                    if (confirm.isConfirmed) {
                        $.ajax({
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = ((evt.loaded / evt.total) * 100);
                                        $(".progress-bar").width(percentComplete.toFixed(0) + '%');
                                        $(".progress-bar").html(percentComplete.toFixed(0) +'%');
                                    }
                                }, false);
                                return xhr;
                            },
                            type: 'POST',
                            url: 'ajaxHandler.php',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData:false,
                            beforeSend: function(){
                                $(".progress-bar").width('0%');
                                // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                                $('#signalbmEventUploadStatus').removeClass('d-none');
                            },
                            error:function(){
                                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                                 $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
                            },
                            success: function(resp){
                                // console.log(resp);
                                resp=JSON.parse(resp);
                                if(resp.status == 1){
                                    Swal.fire({
                                        icon: 'success',
                                        // title: resp.data,
                                        title: "Successfully save event",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
        
                                    // $('#uploadForm')[0].reset();
                                    $("#createEventModal").modal('hide');
                                    $("#sigAlbmEventName").val("");
                                    $("#EventCoverImgFile").val("");
                                    $("#signatureAlbumEventFiles").val("");
                                    $("#signatureAlbumFiles").val("");   
                                    $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
        
                                    var project_id = $("#selectedProjecEventtId").val();
                                    var userId = $("#selectedEventUserId").val();
                                    viewProjectEvents(project_id, userId)
        
                                    $("#selectedEventUserId").val("");
                                    $("#selectedProjecEventtId").val("");
        
                                    // getSignatureALbumList();
                                    // setTimeout(function(){
                                    //     history.go(0);
                                    // },500)
                                }else{
                                    Swal.fire({
                                        icon: 'error',
                                        title: "Failed to save event",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
                                }
                                
                            }
                        });
                    }else{
                        $("#createEventSubmit").removeClass("d-none");
                        $("#createEventSubmitLoadingButton").addClass("d-none");
                    }
                });
})


$("#sigAlbmSelectUserForm").submit(function(event) {
    event.preventDefault();
}).validate({
    submitHandler: function(form) {
        crateFolder(); 
    },
    rules: {
        usersList: {
            required: true
        }
    },
    messages: {
        usersList: {
            required: "Please select the User"
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

crateFolder = () => {
    var selectedUserId = $("#signAlbumUsersList").val();
    $("#createFolderModal").modal('show');
    $("#selectedUserId").val(selectedUserId);
};

$("#createSigAlbmFolderForm").submit(function(event) {
    event.preventDefault();
}).validate({
   submitHandler: function(form) {
        saveSignAlbmFolder();
   },
   rules: {
        sigAlbmFolderName: {
           required: true
        },
        signatureAlbumFiles: {
            required: true
        }
   },
   messages: {
        sigAlbmFolderName: {
           required: "Please select the User"
        },
        signatureAlbumFiles: {
            required: "Please select the User"
        }
   },
   errorElement: 'span',
   errorPlacement: function (error, element) {
       error.addClass('invalid-feedback');
       element.closest('.form-group').append(error);
   },
   highlight: function (element, errorClass, validClass) {
       $(element).addClass('is-invalid');
   },
   unhighlight: function (element, errorClass, validClass) {
       $(element).removeClass('is-invalid');
   }
});

saveSignAlbmFolder = () => {
    alert('2222')
    
    return false;
    $("#createFolderSubmit").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    var form = $("#createSigAlbmFolderForm");
    var formData = new FormData(form[0]);
    var zipFile = $('#signatureAlbumFiles')[0].files;
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'saveSignatureAlbum');
    formData.append('save', "add");
    formData.append('signatureAlbumFiles', zipFile);

    return new swal({
        title: "Are you sure?",
        text: "You want to save this folder",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete.toFixed(0) + '%');
                                $(".progress-bar").html(percentComplete.toFixed(0) +'%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: 'ajaxHandler.php',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $(".progress-bar").width('0%');
                        // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                        $('#signalbmUploadStatus').removeClass('d-none');
                    },
                    error:function(){
                        $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                         $("#createFolderSubmit").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                    },
                    success: function(resp){
                        // console.log(resp);
                        resp=JSON.parse(resp);
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Album saved successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            // $('#uploadForm')[0].reset();
                            $("#createFolderModal").modal('hide');
                            $("#selectedUserId").val("");
                            $("#sigAlbmFolderName").val("");
                            $("#signatureAlbumFiles").val("");   
                            $("#createFolderSubmit").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                            getSignatureALbumList();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $("#createFolderSubmit").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                        }
                        
                    }
                });
            }else{
                $("#createFolderSubmit").removeClass("d-none");
                $("#submitLoadingButton").addClass("d-none");
            }
        });
}

saveSignAlbmFolderOld = () => {
    alert('11111')
    return false;

    // alert("sdsds");
    $("#createFolderSubmit").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    var form = $("#createSigAlbmFolderForm");
    var formData = new FormData(form[0]);
    var zipFile = $('#signatureAlbumFiles')[0].files[0];
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'saveSignatureAlbum');
    formData.append('save', "add");
    formData.append('signatureAlbumFiles', zipFile);

    return new swal({
        title: "Are you sure?",
        text: "You want to save this folder",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                successFn = function(resp)  {
                    if(resp.status == 1){
                        Swal.fire({
                            icon: 'success',
                            // title: resp.data,
                            title: "Album saved successfully",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // emptyForm();
                        // getEvents()
                        $("#createFolderModal").modal('hide');
                        $("#selectedUserId").val("");
                        $("#sigAlbmFolderName").val("");
                        $("#signatureAlbumFiles").val("");   
                        $("#createFolderSubmit").removeClass("d-none");
                        $("#submitLoadingButton").addClass("d-none");
                        getSignatureALbumList();
                        // setTimeout(function(){
                        //     location.reload();
                        //     // $('#signAlbumUsersList').on('change');
                        // },200)
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: resp.data,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $("#createFolderSubmit").removeClass("d-none");
                        $("#submitLoadingButton").addClass("d-none");
                    }
                }
                apiCallForm(formData,successFn);
            }else{
                $("#createFolderSubmit").removeClass("d-none");
                $("#submitLoadingButton").addClass("d-none");
            }
    });
}

deleteSignAlbum = (id, userId, project_id) => {
    // alert(id);
     var form = $("#addEventForm");
     var formData = new FormData(form[0]);
     formData.append('function', 'SignatureAlbum');
     formData.append('method', 'deleteSignatureAlbum');
     formData.append('save', "update");
     formData.append('albumId', id);
     // formData.append('albumPdf', albumPdf);
     // console.log(formData);
         return new swal({
             title: "Are you sure?",
             text: "You want to delete this album",
             icon: false,
             // buttons: true,
             // dangerMode: true,
             showCancelButton: true,
             confirmButtonText: 'Yes'
             }).then((confirm) => {
                 // console.log(confirm.isConfirmed);
                 if (confirm.isConfirmed) {
                     successFn = function(resp)  {
                         if(resp.status == 1){
                             Swal.fire({
                                 icon: 'success',
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });

                            // getSignatureALbumList();
                            viewProjectEvents(project_id, userId)
                             
                         }else{
                             Swal.fire({
                                 icon: 'error',
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });
                         }
                     }
                     apiCallForm(formData,successFn);
                 }
         });
}




getSignatureALbumList = () => {    
    // var userId = this.value;
    var imageList = $('#imageList');
    imageList.html('');
    
    
    var userId = $('#signAlbumUsersList').val();
    if(userId != ""){
        $("#signatureAlbumEmptyData").addClass("d-none");
        $("#signatureAlbumTabContent").removeClass("d-none");
    }

    successFn = function(resp)  {
    
      var users = resp["data"];
      var tabsTitle = "";
      var tabContents = "";
      var count = 0; 
    //   console.log(users);
        if(users != ""){
            $("#signatureAlbumEmptyDataForUser").addClass("d-none");
            $.each(users, function(key,value) {
                
                var active = "";
                var tabTactive = "";
                var folder_name_str = "'"+value.folder_name+"'";
                var folder_name_str = "'"+value.file_folder+"'";
                if(count == 0){
                    active = "show active";
                    tabTactive = "show active";
                    getAlbumFiles(value.file_folder, value.user_id, value.id, value.folder_name);
                }

                var jjj = value.file_folder;
                tabsTitle += '<li class="nav-item" role="presentation">';
                tabsTitle += '<div class="nav-link '+active+'" id="'+value.folder_name+'-tab" data-bs-toggle="tab" data-bs-target="#'+value.folder_name+'" role="tab" aria-controls="'+value.folder_name+'" aria-selected="true">';
                tabsTitle += '<a href="javascript:void(0)" onclick="getAlbumFiles(\''+jjj+'\', '+value.user_id+', '+value.id+', \''+value.folder_name+'\')" style="color:#464857">'+value.folder_name+'</a>';
                
                tabsTitle += '<a class="createdDiv" href="javascript:void(0)"  onclick="openSignaAlbmImageUploadModal(\''+value.id+'\', '+value.user_id+', \''+value.folder_name+'\', \''+value.file_folder+'\')"style="margin-left: 20px; color:#0d6efd" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Add images to album"><i class="ri-image-add-fill"></i></a>';
                
                tabsTitle += '<a class="createdDiv" href="javascript:void(0)" onclick="deleteSignAlbum(\''+value.id+'\', '+value.project_folder_id+')" style="margin-left: 10px; color:#920404" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete album"><i class="ri-delete-bin-3-line"></i></a>';
                
                tabsTitle += '</div></li>';

                tabContents += '<div class="tab-pane row fade '+tabTactive+'" role="tabpanel" id="'+value.folder_name+'"></div>';
                count++;
            });
        }else{
            $("#signatureAlbumEmptyDataForUser").removeClass("d-none");
        }

      $("#signatureAlbumTabs").html(tabsTitle);
        //   setTimeout(function(){
      $("#signatureAlbumTabContent").html(tabContents);
        // }, 2000);
      
        
    }
    data = { "function": 'SignatureAlbum',"method": "getSignatureAlbums", 'userId': userId };
    
    apiCall(data,successFn);
}

openSignaAlbmImageUploadModal = (id, userId, folder_name, file_folder) => {
    console.log(folder_name);
    $("#uploadsigAlbmFolderName").val(folder_name);
    $("#selectedUplSigUserId").val(userId);
    $("#selectedUplSigAlbmId").val(id);
    $("#selectedUplSigfile_folder").val(file_folder);
    $('.ri-close-circle-line').click();
    $("#uploadImageModal").modal('show');
     var imageList = $('#imageList');
    imageList.html('');
    
    $('#uploadStatus').html('');
    $('#uploadMoreStatus').html('');
    
    $('#rUplSigFilesSubmit').addClass('d-none');
    $('#disUploadImgTitlenew').html('');
    
    
    
    
    // $("#progress-bar").width('0%');
    // $("#progress-bar").html('0%');
     var progressBar = document.getElementById("progress-bar");
        
    // Set the width of the progress bar to 0%
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
    
}

$("#uploadSigExtrafilesForm").submit(function(event) {
    event.preventDefault();
}).validate({
   submitHandler: function(form) {
    addImagestoAlbum();
   },
   rules: {
    uploadSignatureAlbumFiles: {
            required: true
        }
   },
   messages: {
    uploadSignatureAlbumFiles: {
            required: "Please select the files"
        }
   },
   errorElement: 'span',
   errorPlacement: function (error, element) {
       error.addClass('invalid-feedback');
       element.closest('.form-group').append(error);
   },
   highlight: function (element, errorClass, validClass) {
       $(element).addClass('is-invalid');
   },
   unhighlight: function (element, errorClass, validClass) {
       $(element).removeClass('is-invalid');
   }
});

addImagestoAlbum = () => {
    return false;

    $("#uplSigFilesSubmit").addClass("d-none");
    $("#uplSigFilesLoadingButton").removeClass("d-none");
    var folderName = $("#uploadsigAlbmFolderName").val();
    var userId = $("#selectedUplSigUserId").val();
    var id = $("#selectedUplSigAlbmId").val();
    var form = $("#uploadSigExtrafilesForm");
    var formData = new FormData(form[0]);
    var zipFile = $('#uploadSignatureAlbumFiles')[0].files;
    // var zipFile = $('#uploadSignatureAlbumFiles').val();

    if(zipFile.length == 0){
        $("#uploadSignatureAlbumFilesErr").html("Plese upload the event images (zip or images)!.");
        $("#uplSigFilesSubmit").removeClass("d-none");
        $("#uplSigFilesLoadingButton").addClass("d-none");
        return false;
    }else{
        $("#uploadSignatureAlbumFilesErr").html("");
    }

   
    // var fileList = [];
    // for (var i = 0; i < zipFile.length; i++)
    // {
    //     fileList.push($('#uploadSignatureAlbumFiles')[0].files[i]);
    // }
    // console.log(fileList);
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'saveSignatureAlbumExtraFiles');
    formData.append('save', "add");
    // formData.append('signatureAlbumFiles', fileList);
    // formData.append('signatureAlbumId', id);
    // formData.append('signatureAlbumUser', userId);
    formData.append('folderName', folderName);
    

    return new swal({
        title: "Are you sure?",
        text: "You want to save this folder",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                $.ajax({
                    xhr: function() {
                       var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                // Update the ID in the selector to match the HTML element ID
                                $("#progress-bar").width(percentComplete.toFixed(0) + '%');
                                $("#progress-bar").html(percentComplete.toFixed(0) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: 'ajaxHandler.php',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $("#progress-bar").width('0%');
                        // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                        $('#signalbmEventUploadStatus').removeClass('d-none');
                    },
                    error:function(){
                        $('#uploadMoreStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                         $("#uplSigFilesSubmit").removeClass("d-none");
                            $("#uplSigFilesLoadingButton").addClass("d-none");
                            
                            
                    },
                    success: function(resp){
                        // console.log(resp);
                        resp=JSON.parse(resp);
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Event saved successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // $('#uploadForm')[0].reset();
                           $("#uploadImageModal").modal('hide');
                            $("#selectedUplSigUserId").val("");
                            $("#uploadsigAlbmFolderName").val("");
                            $("#uploadSignatureAlbumFiles").val("");   
                            $("#uplSigFilesSubmit").removeClass("d-none");
                            $("#uplSigFilesLoadingButton").addClass("d-none");
                            
                          
                            getSignatureALbumList();
                            // getSignatureALbumList();
                            // setTimeout(function(){
                            //     history.go(0);
                            // },500)
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $("#uplSigFilesSubmit").removeClass("d-none");
                            $("#uplSigFilesLoadingButton").addClass("d-none");
                        }
                        
                    }
                });
                
              
            }else{
                $("#uplSigFilesSubmit").removeClass("d-none");
                $("#uplSigFilesLoadingButton").addClass("d-none");
            }
    });
}



getAlbumFiles = (folder, userId, albumId, folder_name) => {
    
   $('#imageLoadDiv').addClass('d-none');
   
   $('#imageInfo').html('');
    // var file_folder = userId+"_"+folder;
    // console.log("222222", userId);
     cancelMulImg();
    var img_folder = userId+"_"+folder_name;
    var file_folder = folder;
    
    $('#sel_folder').val(folder);
    $('#sel_userId').val(userId);
    $('#sel_folder_name').val(folder_name);
    $('#sel_img_folder').val(img_folder);
    
    
    
    
    $('#sel_file_folder').val(file_folder);
    $('#sel_albumId').val(albumId);
    $('#sel_isHide').val(1);
   
  
    
    $('#sel_numberOfLoading').val(0);
    var sel_numberOfLoading = 0;
    
   
    
     var valuefolder_name = folder_name.replace(/\s/g,'');
    valuefolder_name = Base64.encode(valuefolder_name );  
    valuefolder_name = valuefolder_name.replace(/[^a-zA-Z0-9]/g, '');
    
     $("#"+valuefolder_name).html('');
    
    
    successFn = function(resp)  {
      
        
        var imageslength = resp.data[0]['total_count'];
        
        
          var tabContents = '';
         tabContents = '<div class="row" ><div class="badge bg-info text-dark col-2" style="margin-left: 20px;" id="imgCunt">Total Images : '+imageslength+'</div>';
        
        
        tabContents += '<div class="col-9" align="right" style="margin-left: 20px;"><a href="javascript:void(0)" onclick="getAlbumFiles(\''+folder+'\', '+userId+', '+albumId+', \''+folder_name+'\')" style="color:blue"><i class="bi bi-arrow-clockwise"></i> refresh</a></div></div>';
        
        
        //  tabContents += '<div id="masonryGallery'+valuefolder_name+'" name="imageDisplayDiv" ></div>';
         
         tabContents += '<div class="col-12"><ul class="rem-masonry my-masonry full-width" id="masonryGallery'+valuefolder_name+'" ></ul></div>';
        
        
        
     
        // tabContents += '<ul class="rem-masonry my-masonry full-width" id="img_'+valuefolder_name+'" >';
        
        
        // tabContents += '</ul>';
        
        
       


      
        $("#"+valuefolder_name).html(tabContents);
       
                
                
                
        getAlbumImagesPagenation();
      
    }
    data = {  "function": 'SignatureAlbum',"method": "getFilesFromFolderCount", "folderName": img_folder, "albumId": albumId, "isHide":1, "start": ''   };
    apiCall(data,successFn);

    
    
    
    
    
   
    
    return false;
    
    
    
    
    
    var data = { "function": 'SignatureAlbum',"method": "getFilesFromFolder", "folderName": file_folder, "albumId": albumId, "isHide":1};
        $.ajax({
            url: "ajaxHandler.php",
            type:"POST",
            data : data,
            async: true,
            success: function(result){
                
                result=JSON.parse(result);
                console.log(result.data);
                var images = result.data;
                
                // var tabContents = '<div>'+file_folder+'</div>';
                var tabContents = '';
                var tabContents = '<div class="row" ><div class="badge bg-info text-dark col-2" style="margin-left: 20px;">Total Images : '+images.length+'</div>';
                
                
                tabContents += '<div class="col-9" align="right" style="margin-left: 20px;"><a href="javascript:void(0)" onclick="getAlbumFiles(\''+folder+'\', '+userId+', '+albumId+', \''+folder_name+'\')" style="color:blue"><i class="bi bi-arrow-clockwise"></i> refresh</a></div></div>';
                
                
                
                
                
                // var tabContents = '<div class="col-sm-12" style="height: 50px;"><button type="button" class="btn btn-danger float-end m-l-2" onclick="deleteSignatureAlbum(\''+userId+'\');">Delete</button>';
                // tabContents += '<button type="button" class="btn btn btn-primary float-end" onclick="addMoreAlbumImages(\''+userId+'\');" style="margin-right: 10px;">Add images</button></div>';
                tabContents += '<ul class="rem-masonry my-masonry full-width" >';
                // var tabContents = '';
                var gdggd = 0;
                $.each(images, function(key1,value1) {
                //     // console.log(value1);
                    var file_name = value1.file_name;
                    var img =value1.thumb_image_path;
                    var imgId = value1.id;
                    tabContents += '<li><img src="'+img+'" alt="masonry"><input type="checkbox" value="'+imgId+'" name="multipleImgSelectionChk" class="d-none" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20px; height: 20px;"> <a class="d-flex align-items-center" href="javascript:void(0);" onclick="deleteImage('+imgId+',\''+file_folder+'\','+userId+','+albumId+',\''+folder_name+'\')" style="position: absolute; top: 5px; right: 5px; background: #e0e0e0; padding: 3px 8px;; border-radius: 50% 50% 46%;"><i class="bi bi-trash"></i></a></li>';
                    // <div>'+gdggd+'</div>
                    // tabContents += '<div class="col-sm-2"><img src="'+img+'" class="img-fluid" alt="Responsive image"></div>';
                    gdggd++;
                });
                tabContents += '</ul>';

                var valuefolder_name = folder_name.replace(/\s/g,'');
                valuefolder_name = Base64.encode(valuefolder_name );  
                valuefolder_name = valuefolder_name.replace(/[^a-zA-Z0-9]/g, '');


              
                // console.log(folder_name);
                $("#"+valuefolder_name).html(tabContents);
                setTimeout(function(){
                    masonryInitialize();
                },images.length*10)
                
                // console.log(tabContents)
                // $("body").tooltip({ selector: '[data-toggle=tooltip]' });
                $('body').tooltip({
                    selector: '.createdDiv'
                });
            },
            error: function(result) {
                alert(2);
            }
          });
          
          
          
          
          
}

function loadMoreImages(){
    $('#imageLoadDiv').addClass('d-none');
    var sel_numberOfLoading = $('#sel_numberOfLoading').val();
    var newOffset = Number(sel_numberOfLoading) + 1;
    $('#sel_numberOfLoading').val(newOffset);
    getAlbumImagesPagenation();
}

function getAlbumImagesPagenation(){
    
   
    var folder = $('#sel_folder').val();
    var userId = $('#sel_userId').val();
    var folder_name = $('#sel_folder_name').val();
        var img_folder = $('#sel_img_folder').val();

    
    
    
    var file_folder = $('#sel_file_folder').val();
    var albumId = $('#sel_albumId').val();
    var isHide = $('#sel_isHide').val();
  
    var sel_numberOfLoading = $('#sel_numberOfLoading').val();
    
    var offset = Number(sel_numberOfLoading) * 100;
    
     
    
    var data = { "function": 'SignatureAlbum',"method": "setSignatureAlbumUsingPagenation", "folderName": file_folder, "albumId": albumId, "isHide":1 , "start": '', "limit": 100 ,"offset":offset};
        $.ajax({
            url: "ajaxHandler.php",
            type:"POST",
            data : data,
            async: true,
            success: function(result){
                
                
                 result=JSON.parse(result);
                // console.log(result.data);
                var images = result.data;
                
                  var valuefolder_name = folder_name.replace(/\s/g,'');
                valuefolder_name = Base64.encode(valuefolder_name );  
                valuefolder_name = valuefolder_name.replace(/[^a-zA-Z0-9]/g, '');
                

               
                
                var gdggd = 0;
                $.each(images, function(key1,value1) {
                    var file_name = value1.file_name;
                    var img =value1.thumb_image_path;
                    var imgId = value1.id;
                    
                    var tabContents = '';
                    
                    
                    //  tabContents += '<div class="grid-item">';
                    //                 tabContents += '<a class="d-flex align-items-center" href="javascript:void(0);" onclick="deleteImage('+imgId+',\''+file_folder+'\','+userId+','+albumId+',\''+folder_name+'\')" style="position: absolute; top: 5px; right: 5px; background: #e0e0e0; padding: 3px 8px;; border-radius: 50% 50% 46%;"><i class="bi bi-trash"></i></a>';
                                   
                            
                    //         tabContents += '<a class=" elem" ><img src="'+img+'" class="image"></img><input type="checkbox" value="'+imgId+'" name="multipleImgSelectionChk" class="d-none" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20px; height: 20px;"></a>';
                            
                         
                            
                    //         tabContents += '</div>';
                    
                    
                    
                      tabContents += '<li><img src="'+img+'" alt="masonry"><input type="checkbox" value="'+imgId+'" name="multipleImgSelectionChk" class="d-none" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20px; height: 20px;"> <a class="d-flex align-items-center" href="javascript:void(0);" onclick="deleteImage('+imgId+',\''+file_folder+'\','+userId+','+albumId+',\''+folder_name+'\')" style="position: absolute; top: 5px; right: 5px; background: #e0e0e0; padding: 3px 8px;; border-radius: 50% 50% 46%;"><i class="bi bi-trash"></i></a></li>';
                    
                    
                    
                    
                    
                    $("#masonryGallery"+valuefolder_name).append(tabContents);
                    
                    gdggd++;
                });
              
                //  $("#masonryGallery"+valuefolder_name).justifiedGallery({ margins:0, lastRow : 'nojustify', rowHeight : 250});
                
                setTimeout(function(){
                    masonryInitialize();
                },500)
                
                setTimeout(function(){
                    masonryInitialize();
                },5000)
                
                setTimeout(function(){
                    masonryInitialize();
                },50000)
                
                setTimeout(function(){
                    masonryInitialize();
                },100000)
                
                setTimeout(function(){
                    masonryInitialize();
                },500000)
                
                // console.log(tabContents)
                // $("body").tooltip({ selector: '[data-toggle=tooltip]' });
                $('body').tooltip({
                    selector: '.createdDiv'
                });
                 
                 
                  successFn = function(resp)  {
                      
                      $('#imgCunt').html('Total Images : '+resp.data[0]['total_count']);
                      
                      
                      
                            var totalDisImgs =  ( Number(sel_numberOfLoading) + 1 ) * 100;
                            
                            var totalDisImgsCnt = (( Number(sel_numberOfLoading) ) * 100 ) + gdggd ;
                            
                            $('#imageInfo').html('Showing '+totalDisImgsCnt+' of '+resp.data[0]['total_count']+' images');

                            if(totalDisImgs < Number(resp.data[0]['total_count']) ){
                                 $('#imageLoadDiv').removeClass('d-none');
                                 
                            }else{
                                $('#imageLoadDiv').addClass('d-none');
                            }
                            
                           
                          
                        }
                        data = {  "function": 'SignatureAlbum',"method": "getFilesFromFolderCount", "folderName": img_folder, "albumId": albumId, "isHide":1, "start": ''   };
                        apiCall(data,successFn);
                
                
                
                
                
              
              
            },
            error: function(result) {
                //alert(2);
            }
          });
          
          
}







function deleteImage(id, folder, userId, albumId, folder_name){
    
    var project_id = $('#sel_project_id').val();
    
    var formData = new FormData();
    formData.append('function', 'SignatureAlbum');
    formData.append('method', 'deleteImageFromAlbum');
    formData.append('save', "update");
    formData.append('image', id);
    //formData.append('imageUrl', imgUrl);
    // formData.append('albumPdf', albumPdf);
    // console.log(formData);
    return new swal({
     title: "Are you sure?",
     text: "You want to delete this image",
     icon: false,
     // buttons: true,
     // dangerMode: true,
     showCancelButton: true,
     confirmButtonText: 'Yes'
     }).then((confirm) => {
         // console.log(confirm.isConfirmed);
         if (confirm.isConfirmed) {
             successFn = function(resp)  {
                 if(resp.status == 1){
                     Swal.fire({
                         icon: 'success',
                         title: "Image deleted successfully ",
                         showConfirmButton: false,
                         timer: 1500
                     });
                    
                    // getSignatureALbumList();
                    // viewProjectEvents(project_id, userId)
                    getAlbumFiles(folder, userId, albumId, folder_name);
                
                    
                    // getAlbumImagesPagenation();
                     
                 }else{
                     Swal.fire({
                         icon: 'error',
                         title: resp.data,
                         showConfirmButton: false,
                         timer: 1500
                     });
                 }
             }
             apiCallForm(formData,successFn);
         }
    });
}

masonryInitialize = () => {
    $('.rem-masonry').masonry({
        width: "160px",
        padding: "10px"
    });
}

$('#signAlbmStatusFilter').on('change', function() {
    var selVal = $('#signAlbmStatusFilter').val();
    getuSignatureAlbumUers(selVal)
});



getuSignatureAlbumUers = (statusFilter) => {
    successFn = function(resp)  {
        $('#signatureAlbumUsersList').DataTable().destroy();
        var eventList = resp.data;
        console.log(resp);
        // $('#eventListTable').DataTable( { } );
        $('#signatureAlbumUsersList').DataTable({
            "data": eventList,
            "aaSorting": [],
            "columns": [
            //   { "data": "user_id" },
              { "data": null,
                render: function ( data ) {
                    console.log(data);
                    return data['firstname']+" "+data['lastname'];
                }
              },
               { "data": "email" },
                 { "data": "phonenumber" },
                  { "data": "created_date" },
               
                 { "data": "status",
                render: function ( data ) {
                    console.log(data);
                    var status = "";
                    if(data == 1){
                        status='<span class="badge bg-success">Online</span>';
                    }else if(data == 2){
                        status='<span class="badge bg-warning">Deadline</span>';
                    }else if(data == 3){
                        status='<span class="badge bg-danger">not Active</span>';
                    }

                        return status;
                } 
              },
              
               
            //   { "data": "shares" },
            //   { "data": "views" },
             
            //   { "data": "imagecount" }
            //   { "data": "id",
            //     render: function ( data ) {
            //         console.log(data);
            //         return '<span class="badge bg-info text-dark">edit</span><span class="badge bg-danger">delete</span>';
            //     }
            //   }
            ]
        });

    }
    data = { "function": 'SignatureAlbum',"method": "getSignatureAlbumList", "statusFilter": statusFilter };
    
    apiCall(data,successFn);
}


// getFilesFromFolder = (file_folder, tabTactive, folderName) => {
//     var tabContents = "";
//     var data = { "function": 'SignatureAlbum',"method": "getFilesFromFolder", "folderName": file_folder};
//     $.ajax({
//         url: "ajaxHandler.php",
//         type:"POST",
//         data : data,
//         async: true,
//         success: function(result){
//             var images = result.data;
           
//             // alert(1);
            
//             tabContents += '<div class="tab-pane fade '+tabTactive+'" id="'+folderName+'" role="tabpanel" aria-labelledby="'+folderName+'-tab"> Upload photos/Drag Here ====== <div class="row mt-3">';
//             $.each(images, function(key,value) {
//                 console.log(value);
                
//                 tabContents += '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>';
                
//             });
//             tabContents += '</div></div>';
//             console.log(tabContents)
           
//         },
//         error: function(result) {
//             alert(2);
//         }
//       });

      
        
//     setTimeout(function(){
//         return "sdsds";
//     }, 5000);
//     // successFn = function(resp)  {
//     // //   return resp.data;
//     // var images = resp.data;
//     // var tabContents = "";
//     //     $.each(images, function(key,value) {
//     //         console.log(value);
//     //         tabContents += '<div class="tab-pane fade '+tabTactive+'" id="'+value+'" role="tabpanel" aria-labelledby="'+folderName+'-tab"> Upload photos/Drag Here ====== <div class="row mt-3">';
//     //         tabContents += '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>';
//     //         tabContents += '</div></div>';
//     //     });
//     //     return tabContents;
//     // }
//     // data = { "function": 'SignatureAlbum',"method": "getFilesFromFolder", "folderName": file_folder};
    
//     // apiCall(data,successFn);
    
// }