
    
$( document ).ready(function() {
    getusers();
    getEvents();
});


showAddEventSection = () =>{
    // alert("ddfdfd");
    $("#eventListSection").addClass("d-none");
    $('#eventFormSection').removeClass("d-none");
}

showEventListSection = () =>{
    $("#eventListSection").removeClass("d-none");
    $('#eventFormSection').addClass("d-none");
}

saveEvents = () => {
    //alert("efe");
    return new swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: false,
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
      }).then((willDelete) => {
         alert("sfdsfsd");
       
   });
}

    $("#addEventForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        submitHandler: function(form) {
            console.log(form[0]);
            saveEvents();
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
            pageNumber: {
                required: true
            },
            folderName: {
                required: true
            },
            eventFiles: {
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
            pageNumber: {
                required: "Please enter the page number"
            },
            folderName: {
                required: "Please enter the folder name"
            },
            eventFiles: {
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

// $("#addEventForm").submit(function(event) {
saveEvents = () => {
    $("#submitButton").addClass("d-none");
    $("#submitLoadingButton").removeClass("d-none");
    // event.preventDefault();
    var zipFile = $('#eventFiles')[0].files[0];
    var form = $("#addEventForm");
    var formData = new FormData(form[0]);
    formData.append('function', 'OnlineAlbum');
    formData.append('method', 'saveEvents');
    formData.append('save', "add");
    formData.append('eventFiles', zipFile);
    console.log(formData);
        return new swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: false,
            // buttons: true,
            // dangerMode: true,
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((confirm) => {
                if (confirm) {
                    successFn = function(resp)  {
                        if(resp.status == 1){
                            Swal.fire({
                                icon: 'success',
                                title: resp.data,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            emptyForm();
                            getEvents()
                            $("#submitButton").removeClass("d-none");
                            $("#submitLoadingButton").addClass("d-none");
                        } 
                    }
                    apiCallForm(formData,successFn);
                }
        });
}
// });

emptyForm = () => {
    $("#usersList").val("");
    $("#eventName").val("");
    $("#venue").val("");
    $("#pageNumber").val("");
    $("#description").val("");
    $("#folderName").val("");
    $("#eventFiles").val("");
    $("#eventdate").val("");
    $("#uploadedDate").val("");
}

getEvents = () => {
    successFn = function(resp)  {
        $('#eventListTable').DataTable().destroy();
        var eventList = resp.data;
        // console.log(resp.data);
        // $('#eventListTable').DataTable( { } );
        $('#eventListTable').DataTable({
            "data": eventList,
            "aaSorting": [],
            "columns": [
              { "data": "firstname" },
              { "data": "event_name" },
              { "data": "venue" },
              { "data": "event_date" },
              { "data": "description" },
              { "data": "album_type" },
              { "data": "upload_date" }
            //   { "data": "id",
            //     render: function ( data ) {
            //         console.log(data);
            //         return '<span class="badge bg-info text-dark">edit</span><span class="badge bg-danger">delete</span>';
            //     }
            //   }
            ]
        });
    }
    data = { "function": 'OnlineAlbum',"method": "getEventList" ,"sel_user":"" };
    
    apiCall(data,successFn);
}


getusers = () => {

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select User</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.firstname+" "+ value.lastname +"</option>";
      });

      $("#usersList").html(options);

    }
    data = { "function": 'OnlineAlbum',"method": "getUsersList" };
    
    apiCall(data,successFn);
    
}