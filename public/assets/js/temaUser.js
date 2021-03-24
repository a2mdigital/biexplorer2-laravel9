$(function() {
 
    //AO ABRIR A PAGINA DOS USUARIOS VERIFICO QUAL TEMA ESTÁ SETADO
    var menuColorUser = $("#customColorMenuUser").val();
    
    if(menuColorUser == 1){
    
      $('body').removeClass('sidebar-light sidebar-dark');
      $('#navbar-superior-user p').removeClass('titulo-light titulo-dark');
      $('body').addClass('sidebar-dark');
      $('#navbar-superior-user').addClass('dark-nav-bar');
      $('#navbar-superior-user p').addClass('titulo-dark');
      $('#dropdown-menu-user').addClass('dropdown-menu-user');
    }else{
      $('body').removeClass('sidebar-light sidebar-dark');
      $('body').addClass('sidebar-light');
      $('#navbar-superior-user').removeClass('dark-nav-bar');
      $('#dropdown-menu-user').removeClass('dropdown-menu-user');
      $('#navbar-superior-admin p').removeClass('titulo-light titulo-dark');
      $('#navbar-superior-admin p').removeClass('titulo-dark');
      $('#navbar-superior-admin p').addClass('titulo-light');
    }
    
     $("#customColorMenuUser").change(function(){
      var userId = $(this).attr('data-id');
      //VERIFICA SE A COR É DARK OU LIGHT
      if($(this).prop("checked") == true){
        //se utiliza filtro mostro os campos
        $('body').removeClass('sidebar-light sidebar-dark');
        $('body').addClass('sidebar-dark');
        $('#navbar-superior-user').addClass('dark-nav-bar');
        $('#dropdown-menu-user').addClass('dropdown-menu-user');
        //salva no banco o tema do usuário
        var _token = $('meta[name="_token"]').attr('content');
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': _token
              }
          });
          $.ajax({
            url: '/users/tenant/user/custom-menu-color/salvar',
            type: 'PUT',
            data: {
                'user': userId,
                'color': 1
            },
            success: function(data){
                console.log(data);
            }
        });
      }else{
        $('body').removeClass('sidebar-light sidebar-dark');
        $('body').addClass('sidebar-light');
        $('#navbar-superior-user').removeClass('dark-nav-bar');
        $('#dropdown-menu-user').removeClass('dropdown-menu-user');
        //salva no banco o tema do usuário
        var _token = $('meta[name="_token"]').attr('content');
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': _token
              }
          });
          $.ajax({
            url: '/users/tenant/user/custom-menu-color/salvar',
            type: 'PUT',
            data: {
                'user': userId,
                'color': 0
            },
            success: function(data){
                console.log(data);
            }
        });
      }
   });

   //MENU ABERTO OU CONTRAIDO
   var customMenuUser = $("#customMenuUser").val();
    
   if(customMenuUser == 1){
   
     $('body').addClass('sidebar-folded');
     $('.sidebar-toggler').addClass('active');
   }else{
     $('body').removeClass('sidebar-folded');
     $('.sidebar-toggler').addClass('not-active');
 
   }
   
    $("#customMenuUser").change(function(){
     var userId = $(this).attr('data-id');
     //VERIFICA SE A COR É DARK OU LIGHT
     if($(this).prop("checked") == true){
       //se utiliza filtro mostro os campos
       $('body').addClass('sidebar-folded');
       $('.sidebar-toggler').addClass('active');
       //salva no banco o tema do usuário
       
       var _token = $('meta[name="_token"]').attr('content');
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': _token
             }
         });
         $.ajax({
           url: '/users/tenant/user/custom-menu-contraido/salvar',
           type: 'PUT',
           data: {
               'user': userId,
               'contraido': 1
           },
           success: function(data){
               console.log(data);
           }
       });
       
     }else{
      $('body').removeClass('sidebar-folded');
      $('.sidebar-toggler').addClass('not-active');
       //salva no banco o tema do usuário
       
       var _token = $('meta[name="_token"]').attr('content');
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': _token
             }
         });
         $.ajax({
           url: '/users/tenant/user/custom-menu-contraido/salvar',
           type: 'PUT',
           data: {
               'user': userId,
               'contraido': 0
           },
           success: function(data){
               console.log(data);
           }
       });
        
     }
  });


});