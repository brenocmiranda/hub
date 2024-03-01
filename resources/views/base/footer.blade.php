
<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('js/jquery.toast.js')}}"></script>

<script>
    @if (session('create'))
        $.toast({
            heading: 'Cadastro',
            text: 'Seu cadastro foi realizado com sucesso.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    @endif

    @if (session('edit'))
        $.toast({
            heading: 'Atualização',
            text: 'Informações atualizadas com sucesso.',
            icon: 'success',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    @endif

    @if (session('destroy'))
        $.toast({
            heading: 'Arquivar',
            text: 'O cadastro foi desativado com sucesso.',
            icon: 'error',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    @endif

    @if (session('reset'))
        $.toast({
            heading: 'Redefinição',
            text: 'O seu e-mail de redefinição foi enviado.',
            icon: 'info',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            allowToastClose: true,
            position: 'bottom-right',
        });
    @endif
</script>

@yield('js')

</body>
</html>