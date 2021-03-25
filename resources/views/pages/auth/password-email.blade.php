<html>
<div style="margin:0;padding:0">
  <div class="adM">
  </div>
  <div style="background-color:rgb(255,255,255);font-size:14px;color:#333">
    <div class="adM">

    </div>
    <table style="table-layout:fixed;width:100%">
      <tbody>
        <tr>
          <td>
            <table align="center" style="margin:0 auto;font-size:inherit;line-height:inherit;text-align:center;border-spacing:0;border-collapse:collapse;padding:0;border:0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;height:16px" height="16"></td>
                </tr>
                <tr>
                  <td style="width:685px">
                    <table style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;font-size:inherit;line-height:18px;padding:0px;border:0px">
                      <tbody>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="text-align:right;width:600px"><img width="22" height="26" src="{{asset('assets/images/password-reset-ico.png')}}" style="width:10%;height:10%" class="CToWUd"></td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;padding-top:44px;text-align:left;font-size:14px;color:#333">
                            Prezado(a) Usuário,
                          </td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;text-align:left;font-size:14px;color:#333;padding:17px 0 0 0">
                          Alguém solicitou o reset da sua senha na plataforma BI - EXPLORER
                          </td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;text-align:left;font-size:14px;color:#333;padding:18px 0 0 0">
                            <div><span>
                                Data e hora:
                              </span><span>
                              {{date("d/m/Y H:i", strtotime($now))}}
                              </span></div>
                          </td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;text-align:left;font-size:14px;color:#333;padding:18px 0 0 0">
                            Caso seja você que tenha solicitado por favor clique no link a seguir para redefinir sua senha.
                            <br><br>
                            <a href="{{$host}}/reset-password/{{$token}}" class="btn btn-primary mr-2 mb-2 mb-md-0">Redefinir Senha</a>
                          </td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>

                          <td style="font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;text-align:left;font-size:14px;color:#333;padding:18px 0 15px 0">
                           Caso não tenha sido você que tenha solicitado isso, favor ignore este e-mail.
                           </td>
                          <td style="width:40px"></td>
                        </tr>
                        <tr>
                          <td style="width:40px"></td>
                          <td style="text-align:left;font-family:'Lucida Grande',Helvetica,Arial,sans-serif;line-height:18px;color:#333;padding:3px 0 19px 0;font-size:14px">
                            Atenciosamente,
                          </td>
                          <td style="width:40px"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>