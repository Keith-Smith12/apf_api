@extends('layouts._includes.admin.body')
@section('titulo', 'Listar Usuários')

@section('conteudo')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12 my-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="toolbar">
                                    <form class="form">
                                        <div class="form-row">
                                            <div class="form-group col-auto mr-auto">
                                                <label class="my-1 mr-2 sr-only"
                                                    for="inlineFormCustomSelectPref1">Show</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPref1">
                                                    <option value="">...</option>
                                                    <option value="1">12</option>
                                                    <option value="2" selected>32</option>
                                                    <option value="3">64</option>
                                                    <option value="3">128</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-auto">
                                                <label for="search" class="sr-only">Search</label>
                                                <input type="text" class="form-control" id="search1" value=""
                                                    placeholder="Search">
                                            </div>

                                            <div class="col-auto">
                                                {{-- @can('user-create') --}}
                                                <a href="#" class="btn botao" data-toggle="modal"
                                                    data-target="#ModalCreate" style="color:white">
                                                    {{ __('Adicionar') }}
                                                </a>
                                                {{-- @endcan --}}
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Tabela -->
                                <table class="table table-borderless table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>NOME</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->name }} {{ $usuario->sobrenome }}</td>
                                                <td>{{ $usuario->email }} </td>
                                                {{-- Ajustado para garantir que exibe nome e sobrenome corretamente --}}
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-toggle more-horizontal"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted sr-only">Action</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                                data-target="#ModalEdit{{ $usuario->id }}">{{ __('Editar') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.usuarios.restore', $usuario->id) }}">{{ __('Restaurar') }}</a>
                                                                <a class="dropdown-item text-danger"
                                                                    href="{{ route('admin.usuarios.forceDelete', $usuario->id) }}">{{ __('Excluir Permanentemente') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.usuarios.destroy', $usuario->id) }}">{{ __('Remover') }}</a>
                                                         
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                                <nav aria-label="Table Paging" class="mb-0 text-muted">
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de criação --}}
    <div class="modal fade text-left" id="ModalCreate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Adicionar Usuário') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.usuarios.create.index')
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#ModalCreate').on('show.bs.modal', function(event) {
                $.get('/usuario/create', function(response) {
                    $('#ModalCreate .modal-body').html(response);
                });
            });
        });
    </script>

    {{-- Mensagens de Sucesso e Erro --}}
    @if (session('usuario.destroy.success'))
        <script>
            Swal.fire('Usuário removido com sucesso!', '', 'success');
        </script>
    @endif

    @if (session('usuario.destroy.error'))
        <script>
            Swal.fire('Erro ao remover usuário!', '', 'error');
        </script>
    @endif

    @if (session('usuario.purge.success'))
        <script>
            Swal.fire('Usuário purgado com sucesso!', '', 'success');
        </script>
    @endif

    @if (session('usuario.purge.error'))
        <script>
            Swal.fire('Erro ao purgar usuário!', '', 'error');
        </script>
    @endif

@endsection
