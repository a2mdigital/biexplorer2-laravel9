$(function() {
 //AO ABRIR A PAGINA DO PARCEIRO VERIFICO QUAL TEMA ESTÁ SETADO
 var menuColorParceiro = $("#customColorMenuParceiro").val();
    
 if(menuColorParceiro == 1){
  
   $('body').removeClass('sidebar-light sidebar-dark');
   $('body').addClass('sidebar-dark');
   $('#navbar-superior-parceiro').addClass('dark-nav-bar');
   $('#dropdown-menu-parceiro').addClass('dropdown-menu-user');
 }else{
   $('body').removeClass('sidebar-light sidebar-dark');
   $('body').addClass('sidebar-light');
   $('#navbar-superior-parceiro').removeClass('dark-nav-bar');
   $('#dropdown-menu-parceiro').removeClass('dropdown-menu-user');
 }


 $("#customColorMenuParceiro").change(function(){
   var userParceiroId = $(this).attr('data-id');
  
   //VERIFICA SE UTILIZA FILTRO
   if($(this).prop("checked") == true){
     //se utiliza filtro mostro os campos
     $('body').removeClass('sidebar-light sidebar-dark');
     $('body').addClass('sidebar-dark');
     $('#navbar-superior-parceiro').addClass('dark-nav-bar');
     $('#dropdown-menu-parceiro').addClass('dropdown-menu-user');
     //salva no banco o tema do usuário
     var _token = $('meta[name="_token"]').attr('content');
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': _token
           }
       });
       $.ajax({
         url: '/parceiro/admin/custom-menu-color/salvar',
         type: 'PUT',
         data: {
             'user': userParceiroId,
             'color': 1
         },
         success: function(data){
             console.log(data);
         }
     });
   }else{
     $('body').removeClass('sidebar-light sidebar-dark');
     $('body').addClass('sidebar-light');
     $('#navbar-superior-parceiro').removeClass('dark-nav-bar');
     $('#dropdown-menu-parceiro').removeClass('dropdown-menu-user');
      //salva no banco o tema do usuário
      var _token = $('meta[name="_token"]').attr('content');
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': _token
          }
      });
      $.ajax({
        url: '/parceiro/admin/custom-menu-color/salvar',
        type: 'PUT',
        data: {
            'user': userParceiroId,
            'color': 0
        },
        success: function(data){
            console.log(data);
        }
    });
   }
});

       //MENU ABERTO OU CONTRAIDO
       
       var customMenuParceiro = $("#customMenuParceiro").val();
      
       if(customMenuParceiro == 1){
       
         $('body').addClass('sidebar-folded');
         $('.sidebar-toggler').addClass('active');
       }else{
         $('body').removeClass('sidebar-folded');
         $('.sidebar-toggler').addClass('not-active');
     
       }
       
       
        $("#customMenuParceiro").change(function(){
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
               url: '/parceiro/admin/custom-menu-contraido/salvar',
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
               url: '/parceiro/admin/custom-menu-contraido/salvar',
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

///

});