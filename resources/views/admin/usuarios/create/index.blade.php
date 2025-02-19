{{-- @extends('layouts._includes.admin.body') --}}
{{-- @section('titulo','Cadastrar categoriaTituloHabitantes') --}}

{{-- @section('conteudo') --}}
    <div class="card shadow mb-4">
        {{-- <div class="card-header">
        <strong class="card-title">Cadastrar Categoria Titulo Habitantes</strong>
        </div> --}}
        <form action="{{route('admin.usuarios.store')}}" method="post">
            @csrf
            <div class="card-body">
                @include('_form.usuario.index')
                <button type="submit" class="btn btn-primary w-md">Cadastrar</button>
            </div>
        </form>
    </div>

@if (session('usuarios.create.success'))
    <script>
        Swal.fire(
            'Categoria Titulo Habitante Cadastrada com sucesso!',
            '',
            'success'
        )
    </script>
@endif
@if (session('usuarios.create.error'))
    <script>
        Swal.fire(
            'Erro ao Cadastrar Categoria Titulo Habitante!',
            '',
            'error'
        )
    </script>
@endif

{{-- @endsection --}}
