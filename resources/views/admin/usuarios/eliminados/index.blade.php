@extends('layouts._includes.admin.body')
@section('titulo', 'Usuários Eliminados')

@section('conteudo')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12 my-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="mb-4">Lista de Usuários Eliminados</h4>

                                <!-- Tabela -->
                                <table class="table table-borderless table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->name }} {{ $usuario->sobrenome }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-toggle more-horizontal"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted sr-only">Ações</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <!-- Restaurar Usuário -->
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.usuarios.restore', $usuario->id) }}">
                                                                {{ __('Restaurar') }}
                                                            </a>

                                                            <!-- Excluir Permanentemente -->
                                                            <a class="dropdown-item text-danger"
                                                                href="{{ route('admin.usuarios.forceDelete', $usuario->id) }}"
                                                                onclick="return confirm('Tem certeza que deseja excluir permanentemente?')">
                                                                {{ __('Excluir Permanentemente') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhum usuário eliminado encontrado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Paginação -->
                                <div class="d-flex justify-content-center">
                                    {{ $usuarios->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mensagens de Sucesso e Erro --}}
    @if (session('usuario.restore.success'))
        <script>
            Swal.fire('Usuário restaurado com sucesso!', '', 'success');
        </script>
    @endif

    @if (session('usuario.restore.error'))
        <script>
            Swal.fire('Erro ao restaurar usuário!', '', 'error');
        </script>
    @endif

    @if (session('usuario.purge.success'))
        <script>
            Swal.fire('Usuário excluído permanentemente!', '', 'success');
        </script>
    @endif

    @if (session('usuario.purge.error'))
        <script>
            Swal.fire('Erro ao excluir permanentemente!', '', 'error');
        </script>
    @endif
@endsection
