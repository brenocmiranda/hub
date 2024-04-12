
<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('js/jquery.toast.js')}}"></script>

@if (session('create'))
    <script>
        $.toast({
            heading: 'Cadastro',
            text: 'Seu cadastro foi realizado com sucesso.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('edit'))
    <script>
        $.toast({
            heading: 'Atualização',
            text: 'Informações atualizadas com sucesso.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('destroy'))
    <script>
        $.toast({
            heading: 'Exclusão',
            text: 'O registro foi removido com sucesso.',
            icon: 'error',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('recovery'))
    <script>
        $.toast({
            heading: 'Redefinição',
            text: 'O seu e-mail de redefinição foi enviado.',
            icon: 'info',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('duplicate'))
    <script>
        $.toast({
            heading: 'Duplicação',
            text: 'O seu registro foi duplicado no banco.',
            icon: 'warning',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('retryAll'))
    <script>
        $.toast({
            heading: 'Tentar novamente todos',
            text: 'Foi realizada uma nova tentativa de envio de todos os registros.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('retry'))
    <script>
        $.toast({
            heading: 'Tentar novamente',
            text: 'Foi realizada uma nova tentativa de envio do registro.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@if (session('resend'))
    <script>
        $.toast({
            heading: 'Reenvio',
            text: 'Seus dados foram reenviados com sucesso.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    </script>
@endif

@yield('js')

</body>
</html>