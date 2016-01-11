<div class="auLogin"><input type="text" id="login" class="loginFormEl formIconLogin" placeholder="Логин"></div>
<div class="auPass"><input type="password" id="password"  class="loginFormEl formIconPass" placeholder="Пароль"></div>
<div class="auRe">
    <label>
        <input type="checkbox" id="remember">
        <span class="auReLabel">Запомнить меня</span>
    </label>
</div>

<div class="btnWrap clearFix">
    <!-- <a href="#" class="passLink">Забыли пароль?</a> -->
    <a href="#" class="enterLink" id="enterLink">Войти</a>
</div>

<script>
    $(function(){
        $('#enterLink').on('click', function(e){
            e.preventDefault();
            var login = $('#login').val();
            var password = $('#password').val();
            var remember = 0;
            if( $('#remember').prop('checked') ) {
                remember = 1;
            }
            $.ajax({
                url: '/backend/ajax/login',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    login: login,
                    password: password,
                    remember: remember
                },
                success: function(data){
                    if( data.success ) {
                        window.location.reload();
                    }
                }
            });
        });
    });
</script>