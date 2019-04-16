module.exports={
   postConfirmRoute:function (request) {
       Vue.swal({
           title:'Are you Sure?',
           text: request.text?request.text:'',
           type: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: request.confirmText,
           allowOutsideClick:false
       }).then((result)=>{
           if (result.value){
               axios.post(request.url,request.data)
                   .then((response)=>{
                       request.successAction(request.object);
                       if (request.successAlert.alert) {
                           Vue.swal(
                               request.successAlert.title,
                               request.successAlert.message,
                               'success'
                           );
                       }
                       if (request.log) {
                           console.log(response.data);
                       }
                   })
                   .catch((error)=>{
                       if (request.errorAlert) {
                           Vue.swal(
                               'Error!',
                               'Something Went Wrong',
                               'error'
                           );
                       }
                       if (request.log) {
                           console.log(error);
                       }
                   });
           }
       });
   },
    getConfirmRoute:function (request) {
        Vue.swal({
            title:'Are you Sure?',
            text: request.text?request.text:'',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: request.confirmText,
            allowOutsideClick:false
        }).then((result)=>{
            if (result.value){
                axios.get(request.url)
                    .then((response)=>{
                        request.successAction(response);
                        if (request.successAlert.alert) {
                            Vue.swal(
                                request.successAlert.title,
                                request.successAlert.message,
                                'success'
                            );
                        }
                        if (request.log) {
                            console.log(response.data);
                        }
                    })
                    .catch((error)=>{
                        if (request.errorAlert) {
                            Vue.swal(
                                'Error!',
                                'Something Went Wrong',
                                'error'
                            );
                        }
                        if (request.log) {
                            console.log(error);
                        }
                    });
            }
        });
    }
};